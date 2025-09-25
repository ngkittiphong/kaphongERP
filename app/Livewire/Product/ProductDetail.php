<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use App\Models\WarehouseProduct;


class ProductDetail extends Component
{
    public $product;
    public $productId;
    public $showAddEditProductForm = false;
    public $productTypes = [];
    public $productGroups = [];
    public $productStatuses = [];
    public $vats = [];
    public $withholdings = [];
    
    // Warehouse product data
    public $warehouseProducts = [];
    public $totalQuantity = 0;
    public $totalValue = 0;
    public $averageBuyPrice = 0;
    public $averageSalePrice = 0;

    // Form fields
    public $name;
    public $sku_number;
    public $serial_number;
    public $product_type_id;
    public $product_group_id;
    public $product_group_name;
    public $product_status_id;
    public $unit_name;
    public $buy_price;
    public $buy_vat_id;
    public $buy_withholding_id;
    public $buy_description;
    public $sale_price;
    public $sale_vat_id;
    public $sale_withholding_id;
    public $sale_description;
    public $minimum_quantity;
    public $maximum_quantity;
    public $product_cover_img;

    protected $listeners = [
        'ProductSelected' => 'loadProduct',
        'showAddProductForm' => 'displayAddProductForm',
        'showEditProductForm' => 'displayEditProductForm',
        'refreshComponent' => '$refresh',
        'createProduct' => 'createProduct',
        'updateProduct' => 'updateProduct',
        'deleteProduct' => 'deleteProduct'
    ];

    public function mount($productId = null)
    {
        \Log::info("ProductDetail Component Mounted");
        if ($productId) {
            $this->loadProduct($productId);
        }
        $this->productTypes = ProductType::all();
        $this->productGroups = ProductGroup::all();
        $this->productStatuses = ProductStatus::all();
        $this->vats = Vat::all();
        $this->withholdings = Withholding::all();
    }

    public function loadProduct($productId)
    {
        \Log::info("ProductDetail Component Mounted");
        $this->showAddEditProductForm = false;
        $this->productId = $productId;
        $this->product = Product::with([
            'type',
            'group',
            'status',
            'subUnits',
            'inventories',
            'warehouseProducts.warehouse.branch'
        ])->find($productId);
        
        // Load warehouse product data
        $this->loadWarehouseProductData();
        
        $this->dispatch('productSelected', product: $this->product);
    }
    
    /**
     * Load warehouse product data for the current product
     */
    public function loadWarehouseProductData()
    {
        if (!$this->product) {
            return;
        }
        
        // Get all warehouse products for this product
        $this->warehouseProducts = WarehouseProduct::with(['warehouse.branch'])
            ->where('product_id', $this->product->id)
            ->where('balance', '>', 0) // Only show warehouses with stock
            ->get();
        
        // Calculate totals
        $this->totalQuantity = $this->warehouseProducts->sum('balance');
        $this->totalValue = $this->warehouseProducts->sum(function ($wp) {
            return $wp->balance * $wp->avr_remain_price;
        });
        
        // Calculate weighted average prices
        if ($this->totalQuantity > 0) {
            $this->averageBuyPrice = $this->warehouseProducts->sum(function ($wp) {
                return $wp->balance * $wp->avr_buy_price;
            }) / $this->totalQuantity;
            
            $this->averageSalePrice = $this->warehouseProducts->sum(function ($wp) {
                return $wp->balance * $wp->avr_sale_price;
            }) / $this->totalQuantity;
        }
    }

    public function render()
    {
        return view('livewire.product.product-detail');
    }

    public function displayAddProductForm()
    {
        \Log::info("Livewire Event Received: showAddProductForm");
        $this->showAddEditProductForm = true;
        $this->resetErrorBag();
        $this->reset([
            'name', 'sku_number', 'serial_number', 'product_type_id', 
            'product_group_id', 'product_group_name', 'product_status_id', 'unit_name', 
            'buy_price', 'buy_vat_id', 'buy_withholding_id', 'buy_description',
            'sale_price', 'sale_vat_id', 'sale_withholding_id', 'sale_description',
            'minimum_quantity', 'maximum_quantity', 'product_cover_img'
        ]);
        
        // Set default values
        $this->buy_price = 0.00;
        $this->sale_price = 0.00;
        
        $this->product = null;
        $this->dispatch('addProduct');
    }

    public function displayEditProductForm()
    {
        \Log::info("Livewire Event Received: showEditProductForm");
        $this->showAddEditProductForm = true;
        $this->resetErrorBag();
        \Log::info("Product ID: " . $this->productId);
        \Log::info("Product: " . json_encode($this->product));
        $this->name = $this->product->name;
        $this->sku_number = $this->product->sku_number;
        $this->serial_number = $this->product->serial_number;
        $this->product_type_id = $this->product->product_type_id;
        $this->product_group_id = $this->product->product_group_id;
        $this->product_status_id = $this->product->product_status_id;
        $this->unit_name = $this->product->unit_name;
        $this->buy_price = $this->product->buy_price;
        $this->buy_vat_id = $this->product->buy_vat_id;
        $this->buy_withholding_id = $this->product->buy_withholding_id;
        $this->buy_description = $this->product->buy_description;
        $this->sale_price = $this->product->sale_price;
        $this->sale_vat_id = $this->product->sale_vat_id;
        $this->sale_withholding_id = $this->product->sale_withholding_id;
        $this->sale_description = $this->product->sale_description;
        $this->minimum_quantity = $this->product->minimum_quantity;
        $this->maximum_quantity = $this->product->maximum_quantity;
        $this->product_cover_img = $this->product->product_cover_img;
        $this->dispatch('addProduct');
    }

    public function createProduct()
    {
        \Log::info("createProduct method called");
        try {
            // Create a new request instance with all form data
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => $this->name,
                'sku_number' => $this->sku_number,
                'serial_number' => $this->serial_number,
                'product_type_id' => $this->product_type_id,
                'product_group_name' => $this->product_group_name,
                'product_status_id' => $this->product_status_id,
                'unit_name' => $this->unit_name,
                'buy_price' => $this->buy_price,
                'buy_vat_id' => $this->buy_vat_id,
                'buy_withholding_id' => $this->buy_withholding_id,
                'buy_description' => $this->buy_description,
                'sale_price' => $this->sale_price,
                'sale_vat_id' => $this->sale_vat_id,
                'sale_withholding_id' => $this->sale_withholding_id,
                'sale_description' => $this->sale_description,
                'minimum_quantity' => $this->minimum_quantity,
                'maximum_quantity' => $this->maximum_quantity,
                'product_cover_img' => $this->product_cover_img,
            ]);

            // Log the request data
            \Log::info("Request data: " . $request->product_group_name);

            // Call the ProductController's store method
            $controller = new \App\Http\Controllers\ProductController();
            $response = $controller->store($request);
            $responseData = json_decode($response->getContent());

            if ($responseData->success) {
                \Log::info("Product created successfully");
                $this->showAddProductForm = false;
                $this->dispatch('refreshComponent');
                $this->dispatch('showSuccessMessage', message: $responseData->message);
                return redirect()->route('menu_product');
            } else {
                \Log::info("Error creating product createProduct: " . $responseData->message);
                $this->dispatch('showErrorMessage', message: $responseData->message);
            }
        } catch (\Exception $e) {
            \Log::info("Error creating product Exception: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Error creating product: ' . $e->getMessage());
        }
    }

    public function updateProduct()
    {
        \Log::info("updateProduct method called");
        try {
            // Create a new request instance with all form data
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => $this->name,
                'sku_number' => $this->sku_number,
                'serial_number' => $this->serial_number,
                'product_type_id' => $this->product_type_id,
                'product_group_id' => $this->product_group_id,
                'product_status_id' => $this->product_status_id,
                'unit_name' => $this->unit_name,
                'buy_price' => $this->buy_price,
                'buy_vat_id' => $this->buy_vat_id,
                'buy_withholding_id' => $this->buy_withholding_id,
                'buy_description' => $this->buy_description,
                'sale_price' => $this->sale_price,
                'sale_vat_id' => $this->sale_vat_id,
                'sale_withholding_id' => $this->sale_withholding_id,
                'sale_description' => $this->sale_description,
                'minimum_quantity' => $this->minimum_quantity,
                'maximum_quantity' => $this->maximum_quantity,
                'product_cover_img' => $this->product_cover_img,
            ]);

            // Call the ProductController's update method
            $controller = new \App\Http\Controllers\ProductController();
            $response = $controller->update($request, $this->product);
            $responseData = json_decode($response->getContent());

            if ($responseData->success) {
                \Log::info("Product updated successfully");
                $this->showAddEditProductForm = false;
                $this->dispatch('refreshComponent');
                $this->dispatch('showSuccessMessage', message: $responseData->message);
                return redirect()->route('menu_product');
            } else {
                \Log::info("Error updating product: " . $responseData->message);
                $this->dispatch('showErrorMessage', message: $responseData->message);
            }
        } catch (\Exception $e) {
            \Log::info("Error updating product: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Error updating product: ' . $e->getMessage());
        }
    }

    
} 
