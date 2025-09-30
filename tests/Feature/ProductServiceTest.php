<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\ProductService;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;
    protected $productType;
    protected $productStatus;
    protected $vat;
    protected $withholding;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->productService = app(ProductService::class);
        
        // Create test data
        $this->productType = ProductType::create(['name' => 'Test Type']);
        $this->productStatus = ProductStatus::create(['name' => 'Active']);
        $this->vat = Vat::create(['name' => 'Test VAT', 'rate' => 7]);
        $this->withholding = Withholding::create(['name' => 'Test Withholding', 'rate' => 3]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_all_products()
    {
        // Create test products manually since factory doesn't exist
        $productGroup = ProductGroup::create(['name' => 'Test Group']);
        
        Product::create([
            'name' => 'Test Product 1',
            'sku_number' => 'SKU001',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        Product::create([
            'name' => 'Test Product 2',
            'sku_number' => 'SKU002',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        Product::create([
            'name' => 'Test Product 3',
            'sku_number' => 'SKU003',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $products = $this->productService->getAllProducts();

        $this->assertCount(3, $products);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $products);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_create_form_data()
    {
        $formData = $this->productService->getCreateFormData();

        $this->assertArrayHasKey('types', $formData);
        $this->assertArrayHasKey('groups', $formData);
        $this->assertArrayHasKey('statuses', $formData);
        $this->assertArrayHasKey('vats', $formData);
        $this->assertArrayHasKey('withholdings', $formData);
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $formData['types']);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $formData['groups']);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $formData['statuses']);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $formData['vats']);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $formData['withholdings']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_product_with_existing_group()
    {
        // Create a product group
        $productGroup = ProductGroup::create(['name' => 'Test Group']);

        $data = [
            'name' => 'Test Product',
            'sku_number' => 'SKU001',
            'serial_number' => 'SN001',
            'product_type_id' => $this->productType->id,
            'product_group_name' => 'Test Group',
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'buy_vat_id' => $this->vat->id,
            'buy_withholding_id' => $this->withholding->id,
            'buy_description' => 'Test buy description',
            'sale_price' => 150.00,
            'sale_vat_id' => $this->vat->id,
            'sale_withholding_id' => $this->withholding->id,
            'sale_description' => 'Test sale description',
            'minimum_quantity' => 10,
            'maximum_quantity' => 100,
            'product_cover_img' => 'test-image.jpg',
        ];

        $result = $this->productService->createProduct($data);

        $this->assertTrue($result['success']);
        $this->assertEquals('Product created successfully.', $result['message']);
        $this->assertInstanceOf(Product::class, $result['product']);
        $this->assertEquals('Test Product', $result['product']->name);
        $this->assertEquals($productGroup->id, $result['product']->product_group_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_product_with_new_group()
    {
        $data = [
            'name' => 'Test Product',
            'sku_number' => 'SKU002',
            'serial_number' => 'SN002',
            'product_type_id' => $this->productType->id,
            'product_group_name' => 'New Test Group',
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
        ];

        $result = $this->productService->createProduct($data);

        $this->assertTrue($result['success']);
        $this->assertEquals('Test Product', $result['product']->name);
        
        // Check that the new group was created
        $newGroup = ProductGroup::where('name', 'New Test Group')->first();
        $this->assertNotNull($newGroup);
        $this->assertEquals($newGroup->id, $result['product']->product_group_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_a_product_with_sub_units()
    {
        $data = [
            'name' => 'Test Product with Sub Units',
            'sku_number' => 'SKU003',
            'product_type_id' => $this->productType->id,
            'product_group_name' => 'Test Group',
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'sub_units' => [
                [
                    'serial_number' => 'SUB001',
                    'name' => 'Sub Unit 1',
                    'buy_price' => 50.00,
                    'sale_price' => 75.00,
                    'quantity_of_minimum_unit' => 2,
                ],
                [
                    'serial_number' => 'SUB002',
                    'name' => 'Sub Unit 2',
                    'buy_price' => 30.00,
                    'sale_price' => 45.00,
                    'quantity_of_minimum_unit' => 3,
                ],
            ],
        ];

        $result = $this->productService->createProduct($data);

        $this->assertTrue($result['success']);
        $this->assertCount(2, $result['product']->subUnits);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_validates_required_fields_when_creating_product()
    {
        $data = [
            'name' => '', // Empty name should fail
            'product_type_id' => $this->productType->id,
            'product_group_name' => 'Test Group',
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'sale_price' => 150.00,
        ];

        $result = $this->productService->createProduct($data);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Validation failed', $result['message']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_update_a_product()
    {
        // Create a product first
        $product = Product::create([
            'name' => 'Original Product',
            'sku_number' => 'SKU004',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Original Group'])->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $data = [
            'name' => 'Updated Product',
            'sku_number' => 'SKU004-UPDATED',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $product->product_group_id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 120.00,
            'sale_price' => 180.00,
        ];

        $result = $this->productService->updateProduct($product, $data);

        $this->assertTrue($result['success']);
        $this->assertEquals('Product updated successfully.', $result['message']);
        
        $product->refresh();
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals('SKU004-UPDATED', $product->sku_number);
        $this->assertEquals(120.00, $product->buy_price);
        $this->assertEquals(180.00, $product->sale_price);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_soft_delete_a_product()
    {
        // Create inactive status
        $inactiveStatus = ProductStatus::create(['name' => 'Inactive']);
        
        // Create a product
        $product = Product::create([
            'name' => 'Product to Delete',
            'sku_number' => 'SKU005',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Test Group'])->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $result = $this->productService->softDeleteProduct($product);

        $this->assertTrue($result['success']);
        $this->assertEquals('Product status changed to inactive successfully.', $result['message']);
        
        $product->refresh();
        $this->assertEquals($inactiveStatus->id, $product->product_status_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_product_details()
    {
        // Create a product with relationships
        $productGroup = ProductGroup::create(['name' => 'Test Group']);
        $product = Product::create([
            'name' => 'Test Product',
            'sku_number' => 'SKU006',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $productGroup->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $productDetails = $this->productService->getProductDetails($product);

        $this->assertInstanceOf(Product::class, $productDetails);
        $this->assertTrue($productDetails->relationLoaded('type'));
        $this->assertTrue($productDetails->relationLoaded('group'));
        $this->assertTrue($productDetails->relationLoaded('status'));
        $this->assertTrue($productDetails->relationLoaded('subUnits'));
        $this->assertTrue($productDetails->relationLoaded('inventories'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_find_product_by_id()
    {
        $product = Product::create([
            'name' => 'Findable Product',
            'sku_number' => 'SKU007',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Test Group'])->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $foundProduct = $this->productService->findProduct($product->id);

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals($product->id, $foundProduct->id);
        $this->assertEquals('Findable Product', $foundProduct->name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_search_products()
    {
        // Create test products
        Product::create([
            'name' => 'Searchable Product One',
            'sku_number' => 'SEARCH001',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Test Group'])->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        Product::create([
            'name' => 'Another Product',
            'sku_number' => 'SEARCH002',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Test Group'])->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $results = $this->productService->searchProducts('Searchable');

        $this->assertCount(1, $results);
        $this->assertEquals('Searchable Product One', $results->first()->name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_products_by_status()
    {
        // Use existing statuses or create new ones
        $activeStatus = ProductStatus::where('name', 'Active')->first() ?? ProductStatus::create(['name' => 'Active']);
        $inactiveStatus = ProductStatus::where('name', 'Inactive')->first() ?? ProductStatus::create(['name' => 'Inactive']);

        // Create products with different statuses
        Product::create([
            'name' => 'Active Product',
            'sku_number' => 'ACTIVE001',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Test Group'])->id,
            'product_status_id' => $activeStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        Product::create([
            'name' => 'Inactive Product',
            'sku_number' => 'INACTIVE001',
            'product_type_id' => $this->productType->id,
            'product_group_id' => ProductGroup::create(['name' => 'Test Group'])->id,
            'product_status_id' => $inactiveStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $activeProducts = $this->productService->getProductsByStatus('Active');
        $inactiveProducts = $this->productService->getProductsByStatus('Inactive');

        $this->assertCount(1, $activeProducts);
        $this->assertCount(1, $inactiveProducts);
        $this->assertEquals('Active Product', $activeProducts->first()->name);
        $this->assertEquals('Inactive Product', $inactiveProducts->first()->name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_products_by_group()
    {
        $group1 = ProductGroup::create(['name' => 'Group One']);
        $group2 = ProductGroup::create(['name' => 'Group Two']);

        // Create products in different groups
        Product::create([
            'name' => 'Product in Group One',
            'sku_number' => 'GROUP001',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $group1->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        Product::create([
            'name' => 'Product in Group Two',
            'sku_number' => 'GROUP002',
            'product_type_id' => $this->productType->id,
            'product_group_id' => $group2->id,
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
            'date_create' => now(),
        ]);

        $groupOneProducts = $this->productService->getProductsByGroup($group1->id);
        $groupTwoProducts = $this->productService->getProductsByGroup($group2->id);

        $this->assertCount(1, $groupOneProducts);
        $this->assertCount(1, $groupTwoProducts);
        $this->assertEquals('Product in Group One', $groupOneProducts->first()->name);
        $this->assertEquals('Product in Group Two', $groupTwoProducts->first()->name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_handles_database_transactions_correctly()
    {
        // Test that if an error occurs, the transaction is rolled back
        $data = [
            'name' => 'Test Product',
            'sku_number' => 'SKU008',
            'product_type_id' => 999999, // Invalid ID that should cause failure
            'product_group_name' => 'Test Group',
            'product_status_id' => $this->productStatus->id,
            'unit_name' => 'pcs',
            'buy_price' => 100.00,
            'sale_price' => 150.00,
        ];

        $result = $this->productService->createProduct($data);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Validation failed', $result['message']);
        
        // Verify no product was created
        $this->assertDatabaseMissing('products', ['sku_number' => 'SKU008']);
    }
}
