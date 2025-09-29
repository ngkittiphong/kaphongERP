<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use App\Models\ProductSubUnit;
use App\Models\Inventory;
use App\Models\CheckStockDetail;
use App\Models\CheckStockReport;
use App\Models\WarehouseProduct;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\Company;
use App\Models\MoveType;
use App\Models\WarehouseStatus;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data for relationships
        $this->productType = ProductType::create([
            'name' => 'Electronics',
            'sign' => 'electronics',
            'color' => 'blue'
        ]);
        
        $this->productGroup = ProductGroup::create([
            'name' => 'Computers',
            'sign' => 'computers',
            'color' => 'green'
        ]);
        
        $this->productStatus = ProductStatus::create([
            'name' => 'Active',
            'sign' => 'active',
            'color' => 'green'
        ]);
        
        $this->buyVat = Vat::create([
            'name' => 'VAT 7%',
            'price_percent' => 7.0
        ]);
        
        $this->saleVat = Vat::create([
            'name' => 'VAT 7%',
            'price_percent' => 7.0
        ]);
        
        $this->buyWithholding = Withholding::create([
            'name' => 'Withholding 3%',
            'price_percent' => 3.0
        ]);
        
        $this->saleWithholding = Withholding::create([
            'name' => 'Withholding 3%',
            'price_percent' => 3.0
        ]);
        
        // Create additional test data for warehouse tests
        $this->userStatus = UserStatus::create([
            'name' => 'Active',
            'sign' => 'active',
            'color' => 'green'
        ]);
        
        $this->userType = UserType::create([
            'name' => 'Admin',
            'sign' => 'admin',
            'color' => 'blue'
        ]);
        
        $this->user = User::create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'user_status_id' => $this->userStatus->id,
            'user_type_id' => $this->userType->id,
        ]);
        
        $this->company = Company::create([
            'company_name_th' => 'บริษัททดสอบ',
            'company_name_en' => 'Test Company',
            'tax_no' => '1234567890123',
        ]);
        
        $this->branch = Branch::create([
            'company_id' => $this->company->id,
            'branch_code' => 'BR001',
            'name_th' => 'สาขาหลัก',
            'name_en' => 'Main Branch',
            'is_active' => true,
            'is_head_office' => true,
        ]);
        
        $this->warehouseStatus = WarehouseStatus::create([
            'name' => 'Active',
            'sign' => 'active',
            'color' => 'green'
        ]);
        
        $this->moveType = MoveType::create([
            'name' => 'Purchase',
            'sign' => 'purchase',
            'color' => 'blue'
        ]);
        
        // Create a warehouse for check stock report
        $this->testWarehouse = Warehouse::create([
            'name' => 'Test Warehouse',
            'warehouse_status_id' => $this->warehouseStatus->id,
            'branch_id' => $this->branch->id,
            'user_create_id' => $this->user->id,
            'main_warehouse' => true,
            'date_create' => Carbon::now()
        ]);
        
        $this->checkStockReport = CheckStockReport::create([
            'user_create_id' => $this->user->id,
            'warehouse_id' => $this->testWarehouse->id,
            'datetime_create' => Carbon::now(),
            'closed' => false
        ]);
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $productData = [
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'serial_number' => 'SN001',
            'name' => 'Test Product',
            'product_cover_img' => 'test-image.jpg',
            'unit_name' => 'piece',
            'buy_price' => 100.50,
            'buy_vat_id' => $this->buyVat->id,
            'buy_withholding_id' => $this->buyWithholding->id,
            'buy_description' => 'Buy description',
            'sale_price' => 150.75,
            'sale_vat_id' => $this->saleVat->id,
            'sale_withholding_id' => $this->saleWithholding->id,
            'sale_description' => 'Sale description',
            'minimum_quantity' => 10,
            'maximum_quantity' => 100,
            'date_create' => Carbon::now()
        ];

        $product = Product::create($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('SKU001', $product->sku_number);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(100.50, $product->buy_price);
        $this->assertEquals(150.75, $product->sale_price);
        $this->assertEquals(10, $product->minimum_quantity);
        $this->assertEquals(100, $product->maximum_quantity);
        
        $this->assertDatabaseHas('products', [
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'buy_price' => 100.50,
            'sale_price' => 150.75,
        ]);
    }

    /** @test */
    public function it_has_fillable_fields()
    {
        $product = new Product();
        $expectedFillable = [
            'product_type_id',
            'product_group_id',
            'product_status_id',
            'sku_number',
            'serial_number',
            'name',
            'product_cover_img',
            'unit_name',
            'buy_price',
            'buy_vat_id',
            'buy_withholding_id',
            'buy_description',
            'sale_price',
            'sale_vat_id',
            'sale_withholding_id',
            'sale_description',
            'minimum_quantity',
            'maximum_quantity',
            'date_create'
        ];

        $this->assertEquals($expectedFillable, $product->getFillable());
    }

    /** @test */
    public function it_casts_attributes_correctly()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'buy_price' => '100.50',
            'sale_price' => '150.75',
            'minimum_quantity' => '10',
            'maximum_quantity' => '100',
            'date_create' => '2023-01-01 10:00:00'
        ]);

        $this->assertIsFloat($product->buy_price);
        $this->assertIsFloat($product->sale_price);
        $this->assertIsInt($product->minimum_quantity);
        $this->assertIsInt($product->maximum_quantity);
        $this->assertInstanceOf(Carbon::class, $product->date_create);
        
        $this->assertEquals(100.50, $product->buy_price);
        $this->assertEquals(150.75, $product->sale_price);
        $this->assertEquals(10, $product->minimum_quantity);
        $this->assertEquals(100, $product->maximum_quantity);
    }

    /** @test */
    public function it_disables_timestamps()
    {
        $product = new Product();
        $this->assertFalse($product->timestamps);
    }

    /** @test */
    public function it_belongs_to_product_type()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        $this->assertInstanceOf(ProductType::class, $product->type);
        $this->assertEquals($this->productType->id, $product->type->id);
        $this->assertEquals('Electronics', $product->type->name);
    }

    /** @test */
    public function it_belongs_to_product_group()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        $this->assertInstanceOf(ProductGroup::class, $product->group);
        $this->assertEquals($this->productGroup->id, $product->group->id);
        $this->assertEquals('Computers', $product->group->name);
    }

    /** @test */
    public function it_belongs_to_product_status()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        $this->assertInstanceOf(ProductStatus::class, $product->status);
        $this->assertEquals($this->productStatus->id, $product->status->id);
        $this->assertEquals('Active', $product->status->name);
    }

    /** @test */
    public function it_belongs_to_buy_vat()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'buy_vat_id' => $this->buyVat->id,
        ]);

        $this->assertInstanceOf(Vat::class, $product->buyVat);
        $this->assertEquals($this->buyVat->id, $product->buyVat->id);
        $this->assertEquals('VAT 7%', $product->buyVat->name);
        $this->assertEquals(7.0, $product->buyVat->price_percent);
    }

    /** @test */
    public function it_belongs_to_sale_vat()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'sale_vat_id' => $this->saleVat->id,
        ]);

        $this->assertInstanceOf(Vat::class, $product->saleVat);
        $this->assertEquals($this->saleVat->id, $product->saleVat->id);
        $this->assertEquals('VAT 7%', $product->saleVat->name);
        $this->assertEquals(7.0, $product->saleVat->price_percent);
    }

    /** @test */
    public function it_belongs_to_buy_withholding()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'buy_withholding_id' => $this->buyWithholding->id,
        ]);

        $this->assertInstanceOf(Withholding::class, $product->buyWithholding);
        $this->assertEquals($this->buyWithholding->id, $product->buyWithholding->id);
        $this->assertEquals('Withholding 3%', $product->buyWithholding->name);
        $this->assertEquals(3.0, $product->buyWithholding->price_percent);
    }

    /** @test */
    public function it_belongs_to_sale_withholding()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'sale_withholding_id' => $this->saleWithholding->id,
        ]);

        $this->assertInstanceOf(Withholding::class, $product->saleWithholding);
        $this->assertEquals($this->saleWithholding->id, $product->saleWithholding->id);
        $this->assertEquals('Withholding 3%', $product->saleWithholding->name);
        $this->assertEquals(3.0, $product->saleWithholding->price_percent);
    }

    /** @test */
    public function it_has_many_sub_units()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        // Create sub units
        ProductSubUnit::create([
            'product_id' => $product->id,
            'name' => 'Box',
            'quantity' => 12,
            'price' => 10.0
        ]);

        ProductSubUnit::create([
            'product_id' => $product->id,
            'name' => 'Case',
            'quantity' => 24,
            'price' => 20.0
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->subUnits);
        $this->assertCount(2, $product->subUnits);
        $this->assertEquals('Box', $product->subUnits->first()->name);
        $this->assertEquals('Case', $product->subUnits->last()->name);
    }

    /** @test */
    public function it_has_many_inventories()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        // Create inventories
        Inventory::create([
            'product_id' => $product->id,
            'move_type_id' => $this->moveType->id,
            'quantity_move' => 10,
            'avr_buy_price' => 100.0,
            'avr_sale_price' => 150.0,
            'avr_remain_price' => 100.0,
            'date_activity' => Carbon::now()
        ]);

        Inventory::create([
            'product_id' => $product->id,
            'move_type_id' => $this->moveType->id,
            'quantity_move' => -5,
            'avr_buy_price' => 100.0,
            'avr_sale_price' => 150.0,
            'avr_remain_price' => 100.0,
            'date_activity' => Carbon::now()
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->inventories);
        $this->assertCount(2, $product->inventories);
        $this->assertEquals(10, $product->inventories->first()->quantity_move);
        $this->assertEquals(-5, $product->inventories->last()->quantity_move);
    }

    /** @test */
    public function it_has_many_check_stock_details()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        // Create check stock details
        CheckStockDetail::create([
            'product_id' => $product->id,
            'check_stock_report_id' => $this->checkStockReport->id,
            'user_check_id' => $this->user->id,
            'product_scan_num' => 100,
            'datetime_scan' => Carbon::now()
        ]);

        CheckStockDetail::create([
            'product_id' => $product->id,
            'check_stock_report_id' => $this->checkStockReport->id,
            'user_check_id' => $this->user->id,
            'product_scan_num' => 50,
            'datetime_scan' => Carbon::now()
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->checkStockDetails);
        $this->assertCount(2, $product->checkStockDetails);
        $this->assertEquals(100, $product->checkStockDetails->first()->product_scan_num);
        $this->assertEquals(50, $product->checkStockDetails->last()->product_scan_num);
    }

    /** @test */
    public function it_has_many_warehouse_products()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        // Create warehouses first
        $warehouse1 = Warehouse::create([
            'name' => 'Main Warehouse',
            'warehouse_status_id' => $this->warehouseStatus->id,
            'branch_id' => $this->branch->id,
            'user_create_id' => $this->user->id,
            'main_warehouse' => true,
            'date_create' => Carbon::now()
        ]);

        $warehouse2 = Warehouse::create([
            'name' => 'Secondary Warehouse',
            'warehouse_status_id' => $this->warehouseStatus->id,
            'branch_id' => $this->branch->id,
            'user_create_id' => $this->user->id,
            'main_warehouse' => false,
            'date_create' => Carbon::now()
        ]);

        // Create warehouse products
        WarehouseProduct::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse1->id,
            'balance' => 100,
            'avr_buy_price' => 50.0,
            'avr_sale_price' => 75.0,
            'avr_remain_price' => 25.0
        ]);

        WarehouseProduct::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse2->id,
            'balance' => 50,
            'avr_buy_price' => 45.0,
            'avr_sale_price' => 70.0,
            'avr_remain_price' => 20.0
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->warehouseProducts);
        $this->assertCount(2, $product->warehouseProducts);
        $this->assertEquals(100, $product->warehouseProducts->first()->balance);
        $this->assertEquals(50, $product->warehouseProducts->last()->balance);
    }

    /** @test */
    public function it_belongs_to_many_warehouses()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        // Create warehouses
        $warehouse1 = Warehouse::create([
            'name' => 'Main Warehouse',
            'warehouse_status_id' => $this->warehouseStatus->id,
            'branch_id' => $this->branch->id,
            'user_create_id' => $this->user->id,
            'main_warehouse' => true,
            'date_create' => Carbon::now()
        ]);

        $warehouse2 = Warehouse::create([
            'name' => 'Secondary Warehouse',
            'warehouse_status_id' => $this->warehouseStatus->id,
            'branch_id' => $this->branch->id,
            'user_create_id' => $this->user->id,
            'main_warehouse' => false,
            'date_create' => Carbon::now()
        ]);

        // Attach warehouses to product
        $product->warehouses()->attach($warehouse1->id, [
            'balance' => 100,
            'avr_buy_price' => 50.0,
            'avr_sale_price' => 75.0,
            'avr_remain_price' => 25.0
        ]);

        $product->warehouses()->attach($warehouse2->id, [
            'balance' => 50,
            'avr_buy_price' => 45.0,
            'avr_sale_price' => 70.0,
            'avr_remain_price' => 20.0
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $product->warehouses);
        $this->assertCount(2, $product->warehouses);
        $this->assertEquals('Main Warehouse', $product->warehouses->first()->name);
        $this->assertEquals('Secondary Warehouse', $product->warehouses->last()->name);
        
        // Test pivot data
        $this->assertEquals(100, $product->warehouses->first()->pivot->balance);
        $this->assertEquals(50.0, $product->warehouses->first()->pivot->avr_buy_price);
        $this->assertEquals(75.0, $product->warehouses->first()->pivot->avr_sale_price);
        $this->assertEquals(25.0, $product->warehouses->first()->pivot->avr_remain_price);
    }

    /** @test */
    public function it_can_update_product()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'buy_price' => 100.0,
            'sale_price' => 150.0,
        ]);

        $product->update([
            'name' => 'Updated Product',
            'buy_price' => 120.0,
            'sale_price' => 180.0,
        ]);

        $this->assertEquals('Updated Product', $product->fresh()->name);
        $this->assertEquals(120.0, $product->fresh()->buy_price);
        $this->assertEquals(180.0, $product->fresh()->sale_price);
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'buy_price' => 120.0,
            'sale_price' => 180.0,
        ]);
    }

    /** @test */
    public function it_can_delete_product()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
        ]);

        $productId = $product->id;
        $product->delete();

        $this->assertDatabaseMissing('products', ['id' => $productId]);
        $this->assertNull(Product::find($productId));
    }

    /** @test */
    public function it_can_find_product_by_sku()
    {
        Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product 1',
        ]);

        Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU002',
            'name' => 'Test Product 2',
        ]);

        $product = Product::where('sku_number', 'SKU001')->first();
        
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('SKU001', $product->sku_number);
        $this->assertEquals('Test Product 1', $product->name);
    }

    /** @test */
    public function it_can_scope_products_by_type()
    {
        // Create another product type
        $anotherType = ProductType::create([
            'name' => 'Books',
            'sign' => 'books',
            'color' => 'red'
        ]);

        Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Electronics Product',
        ]);

        Product::create([
            'product_type_id' => $anotherType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU002',
            'name' => 'Book Product',
        ]);

        $electronicsProducts = Product::whereHas('type', function ($query) {
            $query->where('name', 'Electronics');
        })->get();

        $this->assertCount(1, $electronicsProducts);
        $this->assertEquals('Electronics Product', $electronicsProducts->first()->name);
    }

    /** @test */
    public function it_can_scope_products_by_status()
    {
        // Create another product status
        $inactiveStatus = ProductStatus::create([
            'name' => 'Inactive',
            'sign' => 'inactive',
            'color' => 'red'
        ]);

        Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Active Product',
        ]);

        Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $inactiveStatus->id,
            'sku_number' => 'SKU002',
            'name' => 'Inactive Product',
        ]);

        $activeProducts = Product::whereHas('status', function ($query) {
            $query->where('name', 'Active');
        })->get();

        $this->assertCount(1, $activeProducts);
        $this->assertEquals('Active Product', $activeProducts->first()->name);
    }

    /** @test */
    public function it_can_calculate_price_with_vat()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'buy_price' => 100.0,
            'sale_price' => 150.0,
            'buy_vat_id' => $this->buyVat->id,
            'sale_vat_id' => $this->saleVat->id,
        ]);

        // Test buy price with VAT
        $buyPriceWithVat = $product->buy_price * (1 + $product->buyVat->price_percent / 100);
        $this->assertEquals(107.0, $buyPriceWithVat);

        // Test sale price with VAT
        $salePriceWithVat = $product->sale_price * (1 + $product->saleVat->price_percent / 100);
        $this->assertEquals(160.5, $salePriceWithVat);
    }

    /** @test */
    public function it_can_validate_minimum_maximum_quantities()
    {
        $product = Product::create([
            'product_type_id' => $this->productType->id,
            'product_group_id' => $this->productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'sku_number' => 'SKU001',
            'name' => 'Test Product',
            'minimum_quantity' => 10,
            'maximum_quantity' => 100,
        ]);

        $this->assertGreaterThan($product->minimum_quantity, $product->maximum_quantity);
        $this->assertEquals(10, $product->minimum_quantity);
        $this->assertEquals(100, $product->maximum_quantity);
    }
}
