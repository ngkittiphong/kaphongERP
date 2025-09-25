<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\InventoryService;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\WarehouseStatus;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $inventoryService;
    protected $warehouse;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->inventoryService = new InventoryService();
        
        // Create test data
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create user
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'profile_id' => 1,
            'user_status_id' => 1,
            'user_type_id' => 1
        ]);

        // Create company
        $company = Company::create([
            'company_name_en' => 'Test Company',
            'company_name_th' => 'บริษัททดสอบ',
            'tax_no' => '1234567890123'
        ]);

        // Create branch
        $branch = Branch::create([
            'company_id' => $company->id,
            'branch_code' => 'BR001',
            'name_en' => 'Test Branch',
            'name_th' => 'สาขาทดสอบ',
            'address_en' => 'Test Address',
            'phone_number' => '0123456789',
            'email' => 'test@example.com',
            'is_active' => 1,
            'is_head_office' => 1
        ]);

        // Create warehouse status
        $warehouseStatus = WarehouseStatus::create([
            'name' => 'Active',
            'sign' => '+',
            'color' => 'green'
        ]);

        // Create warehouse
        $this->warehouse = Warehouse::create([
            'branch_id' => $branch->id,
            'user_create_id' => $user->id,
            'main_warehouse' => 1,
            'name' => 'Test Warehouse',
            'date_create' => now(),
            'warehouse_status_id' => $warehouseStatus->id,
            'avr_remain_price' => 0
        ]);

        // Create product type
        $productType = ProductType::create([
            'name' => 'Test Type'
        ]);

        // Create product group
        $productGroup = ProductGroup::create([
            'name' => 'Test Group'
        ]);

        // Create product status
        $productStatus = ProductStatus::create([
            'name' => 'Active'
        ]);

        // Create product
        $this->product = Product::create([
            'product_type_id' => $productType->id,
            'product_group_id' => $productGroup->id,
            'product_status_id' => $productStatus->id,
            'sku_number' => 'TEST-001',
            'name' => 'Test Product',
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'minimum_quantity' => 10,
            'maximum_quantity' => 1000,
            'date_create' => now()
        ]);
    }

    public function test_stock_in_operation()
    {
        $data = [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'quantity' => 100,
            'unit_price' => 50.00,
            'sale_price' => 75.00,
            'detail' => 'Test Stock In'
        ];

        $result = $this->inventoryService->stockIn($data);

        $this->assertTrue($result['success']);
        $this->assertEquals(100, $result['new_balance']);
        $this->assertEquals('Stock In completed successfully', $result['message']);

        // Verify warehouse product was created
        $warehouseProduct = \App\Models\WarehouseProduct::where('warehouse_id', $this->warehouse->id)
            ->where('product_id', $this->product->id)
            ->first();

        $this->assertNotNull($warehouseProduct);
        $this->assertEquals(100, $warehouseProduct->balance);
        $this->assertEquals(50.00, $warehouseProduct->avr_buy_price);
        $this->assertEquals(75.00, $warehouseProduct->avr_sale_price);
    }

    public function test_stock_out_operation()
    {
        // First, add some stock
        $this->inventoryService->stockIn([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'quantity' => 100,
            'unit_price' => 50.00,
            'sale_price' => 75.00,
            'detail' => 'Initial Stock'
        ]);

        // Then, stock out
        $data = [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'quantity' => 30,
            'detail' => 'Test Stock Out'
        ];

        $result = $this->inventoryService->stockOut($data);

        $this->assertTrue($result['success']);
        $this->assertEquals(70, $result['new_balance']);
        $this->assertEquals('Stock Out completed successfully', $result['message']);
    }

    public function test_stock_adjustment_operation()
    {
        // First, add some stock
        $this->inventoryService->stockIn([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'quantity' => 100,
            'unit_price' => 50.00,
            'sale_price' => 75.00,
            'detail' => 'Initial Stock'
        ]);

        // Then, adjust
        $data = [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'new_quantity' => 80,
            'detail' => 'Test Adjustment'
        ];

        $result = $this->inventoryService->stockAdjustment($data);

        $this->assertTrue($result['success']);
        $this->assertEquals(80, $result['new_balance']);
        $this->assertEquals(-20, $result['adjustment']);
        $this->assertEquals('Stock Adjustment completed successfully', $result['message']);
    }

    public function test_insufficient_stock_out()
    {
        // Try to stock out without having stock
        $data = [
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'quantity' => 50,
            'detail' => 'Test Stock Out'
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found in warehouse');

        $this->inventoryService->stockOut($data);
    }

    public function test_get_stock_balance()
    {
        // Add stock
        $this->inventoryService->stockIn([
            'warehouse_id' => $this->warehouse->id,
            'product_id' => $this->product->id,
            'quantity' => 50,
            'unit_price' => 25.00,
            'sale_price' => 35.00,
            'detail' => 'Test Stock'
        ]);

        $balance = $this->inventoryService->getStockBalance($this->warehouse->id, $this->product->id);

        $this->assertEquals(50, $balance);
    }

    public function test_stock_balance_without_product()
    {
        $balance = $this->inventoryService->getStockBalance($this->warehouse->id, $this->product->id);

        $this->assertEquals(0, $balance);
    }
}