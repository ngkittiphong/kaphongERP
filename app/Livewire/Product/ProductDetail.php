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
use App\Models\Warehouse;
use App\Livewire\Product\ProductStockCard;
use App\Services\InventoryService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Schema;


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
    public $unitNames = [];
    public $warehouses = [];
    
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
    public $candidateProductNo;
    public $sale_withholding_id;
    public $sale_description;
    public $minimum_quantity;
    public $maximum_quantity;
    public $product_cover_img;

    // Stock adjustment modal properties
    public $showStockModal = false;
    public $selectedWarehouseId = null;
    public $selectedWarehouseName = '';
    public $operationType = '';
    public $quantity = 0;
    public $unitName = '';
    public $unitPrice = 0;
    public $salePrice = 0;
    public $detail = '';
    public $currentStock = 0;
    public $isStockInModal = false; // Flag to indicate if this is a stock-in modal for new products

    // Sub-unit modal properties
    public $showSubUnitModal = false;
    public $subUnitId = null; // for edit
    public $subUnitName = '';
    public $subUnitBuyPrice = 0;
    public $subUnitSalePrice = 0;
    public $subUnitQuantity = 1; // quantity_of_minimum_unit
    public $subUnitBarcode = '';

    protected $listeners = [
        'ProductSelected' => 'loadProduct',
        'showAddEditProductForm' => 'displayAddProductForm',
        'showEditProductForm' => 'displayEditProductForm',
        'refreshComponent' => '$refresh',//'handleRefreshComponent',
        'createProduct' => 'createProduct',
        'updateProduct' => 'updateProduct',
        'deleteProduct' => 'deleteProduct',
        'processStockOperationConfirmed' => 'processStockOperation',
        'deleteSubUnit' => 'deleteSubUnit'
    ];

    public function mount($productId = null)
    {
        \Log::info("ProductDetail Component Mounted");
        
        // Check for product_id in query parameters if no productId is passed directly
        if (!$productId && request()->has('product_id')) {
            $productId = request()->get('product_id');
        }
        
        if ($productId) {
            $this->loadProduct($productId);
        }
        $this->productTypes = ProductType::all();
        $this->productGroups = ProductGroup::all();
        $this->productStatuses = ProductStatus::where('id', '!=', 0)->get(); // Exclude Delete status (ID 0)
        $this->vats = Vat::all();
        $this->withholdings = Withholding::all();
        $this->unitNames = Product::whereNotNull('unit_name')
            ->where('unit_name', '!=', '')
            ->distinct()
            ->pluck('unit_name')
            ->sort()
            ->values()
            ->toArray();
            
        // Load warehouses for stock-in modal
        $this->warehouses = Warehouse::with('branch')
            ->whereHas('status', function($query) {
                $query->where('name', 'Active');
            })
            ->get();
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
        // Force refresh warehouse data on every render
        if ($this->product) {
            $this->loadWarehouseProductData();
        }
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
            'minimum_quantity', 'maximum_quantity', 'product_cover_img', 'candidateProductNo'
        ]);
        
        // Generate candidate product number
        $this->candidateProductNo = Product::generateProductNumber();
        
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
        
        // Dispatch event to initialize typeahead for edit form
        $this->dispatch('productSelected', product: $this->product);
        $this->name = $this->product->name;
        $this->sku_number = $this->product->sku_number;
        $this->serial_number = $this->product->serial_number;
        $this->product_type_id = $this->product->product_type_id;
        $this->product_group_id = $this->product->product_group_id;
        $this->product_group_name = $this->product->group ? $this->product->group->name : '';
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

    /**
     * Cancel edit product form
     */
    public function cancelEditProduct()
    {
        \Log::info("Livewire Event Received: cancelEditProduct");
        $this->showAddEditProductForm = false;
        $this->resetErrorBag();
        
        // Reset form fields to original product values
        if ($this->product) {
            $this->name = $this->product->name;
            $this->sku_number = $this->product->sku_number;
            $this->serial_number = $this->product->serial_number;
            $this->product_type_id = $this->product->product_type_id;
            $this->product_group_id = $this->product->product_group_id;
            $this->product_group_name = $this->product->group ? $this->product->group->name : '';
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
        }
        
        \Log::info("Edit product form cancelled, returning to product detail view");
    }

    /**
     * Cancel add product form
     */
    public function cancelAddProduct()
    {
        \Log::info("Livewire Event Received: cancelAddProduct");
        $this->showAddEditProductForm = false;
        $this->resetErrorBag();
        
        // Reset all form fields to empty/default values
        $this->reset([
            'name', 'sku_number', 'serial_number', 'product_type_id', 
            'product_group_id', 'product_group_name', 'product_status_id', 'unit_name', 
            'buy_price', 'buy_vat_id', 'buy_withholding_id', 'buy_description',
            'sale_price', 'sale_vat_id', 'sale_withholding_id', 'sale_description',
            'minimum_quantity', 'maximum_quantity', 'product_cover_img', 'candidateProductNo'
        ]);
        
        \Log::info("Add product form cancelled, returning to product list view");
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

            // Use ProductService to create product
            $data = $request->all();
            $result = $this->getProductService()->createProduct($data);

            if ($result['success']) {
                // Update productId to the newly created product's ID
                $this->productId = $result['product']->id;
                $this->product = $result['product'];
                
                \Log::info("ProductDetail: Updated productId after creation", [
                    'new_product_id' => $this->productId,
                    'new_product_name' => $this->product->name
                ]);
                
                $this->showAddEditProductForm = false;
                $this->dispatch('refreshComponent');
                $this->dispatch('refreshProductList'); // Refresh the product list to show the new product
                // Dispatch event for DataTable reinitialization
                $this->dispatch('refreshDataTable');
                
                // Show success message and wait for user to click OK before redirecting
                $redirectUrl = route('menu.menu_product', ['product_id' => $this->productId], false);
                $this->dispatch('showSuccessMessage', [
                    'message' => $result['message'],
                    'redirectUrl' => $redirectUrl
                ]);
            } else {
                $this->dispatch('showErrorMessage', message: $result['message']);
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
            // Handle product group - find or create if name is provided
            $productGroupId = $this->product_group_id;
            if (!empty($this->product_group_name)) {
                $productGroup = ProductGroup::firstOrCreate(
                    ['name' => $this->product_group_name],
                    ['name' => $this->product_group_name]
                );
                $productGroupId = $productGroup->id;
            }

            // Create a new request instance with all form data
            $request = new \Illuminate\Http\Request();
            $request->merge([
                'name' => $this->name,
                'sku_number' => $this->sku_number,
                'serial_number' => $this->serial_number,
                'product_type_id' => $this->product_type_id,
                'product_group_id' => $productGroupId,
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

            // Use ProductService to update product
            $data = $request->all();
            $result = $this->getProductService()->updateProduct($this->product, $data);

            if ($result['success']) {
                $this->showAddEditProductForm = false;
                $this->dispatch('refreshComponent');
                $this->dispatch('refreshProductList');
                // Dispatch event for DataTable reinitialization
                $this->dispatch('refreshDataTable'); // Refresh the product list to show updated product
                
                // Show success message and wait for user to click OK before redirecting
                $redirectUrl = route('menu.menu_product', ['product_id' => $this->productId], false);
                $this->dispatch('showSuccessMessage', [
                    'message' => $result['message'],
                    'redirectUrl' => $redirectUrl
                ]);
            } else {
                $this->dispatch('showErrorMessage', message: $result['message']);
            }
        } catch (\Exception $e) {
            \Log::info("Error updating product: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Open stock adjustment modal
     */
    public function openStockModal($warehouseId, $warehouseName)
    {
        \Log::info("🚀 [STOCK MODAL] Opening modal for warehouse: {$warehouseId}, name: {$warehouseName}");
        
        $this->selectedWarehouseId = $warehouseId;
        $this->selectedWarehouseName = $warehouseName;
        $this->showStockModal = true;
        $this->isStockInModal = false; // This is regular stock adjustment
        $this->resetStockModalFields();
        
        // Get current stock for the warehouse
        if ($warehouseId > 0) {
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $warehouseId)
                ->where('product_id', $this->product->id)
                ->first();
            $this->currentStock = $warehouseProduct ? $warehouseProduct->balance : 0;
        } else {
            // For "Total All Warehouses", sum up all stock
            $this->currentStock = $this->totalQuantity;
        }
        
        $this->unitName = $this->product->unit_name ?? 'pcs';
        
        // Pre-fill prices with product's current prices
        $this->unitPrice = $this->product->buy_price ?? 0;
        $this->salePrice = $this->product->sale_price ?? 0;
        
        \Log::info("🚀 [STOCK MODAL] Modal state set - showModal: {$this->showStockModal}, operationType: '{$this->operationType}', currentStock: {$this->currentStock}");
        \Log::info("🚀 [STOCK MODAL] Pre-filled prices - unitPrice: {$this->unitPrice}, salePrice: {$this->salePrice}");
        
        $this->dispatch('showStockModal');
    }

    /**
     * Open stock-in modal for products with no warehouse data
     */
    public function openStockInModal()
    {
        \Log::info("🚀 [STOCK IN MODAL] Opening stock-in modal for new product");
        
        $this->selectedWarehouseId = null;
        $this->selectedWarehouseName = '';
        $this->showStockModal = true;
        $this->isStockInModal = true; // This is stock-in modal for new products
        $this->resetStockModalFields();
        
        // Set operation type to stock_in automatically
        $this->operationType = 'stock_in';
        
        // Set current stock to 0 since product has no warehouse data
        $this->currentStock = 0;
        
        $this->unitName = $this->product->unit_name ?? 'pcs';
        
        // Pre-fill prices with product's current prices
        $this->unitPrice = $this->product->buy_price ?? 0;
        $this->salePrice = $this->product->sale_price ?? 0;
        
        \Log::info("🚀 [STOCK IN MODAL] Modal state set - showModal: {$this->showStockModal}, operationType: '{$this->operationType}', currentStock: {$this->currentStock}");
        \Log::info("🚀 [STOCK IN MODAL] Pre-filled prices - unitPrice: {$this->unitPrice}, salePrice: {$this->salePrice}");
        
        $this->dispatch('showStockModal');
    }

    // Sub-unit CRUD methods
    public function openAddSubUnitModal()
    {
        \Log::info("🚀 [SUB UNIT MODAL] Opening add sub-unit modal");

        $this->subUnitId = null;
        $this->showSubUnitModal = true;
        $this->resetSubUnitFields();

        \Log::info("🚀 [SUB UNIT MODAL] Modal state set - showModal: {$this->showSubUnitModal}");
        
        $this->dispatch('showSubUnitModal');
    }

    public function openEditSubUnitModal($subUnitId)
    {
        \Log::info("🚀 [SUB UNIT MODAL] Opening edit sub-unit modal for ID: {$subUnitId}");

        $subUnit = \App\Models\ProductSubUnit::find($subUnitId);
        if ($subUnit) {
            $this->subUnitId = $subUnit->id;
            $this->subUnitName = $subUnit->name;
            $this->subUnitBuyPrice = $subUnit->buy_price;
            $this->subUnitSalePrice = $subUnit->sale_price;
            $this->subUnitQuantity = $subUnit->quantity_of_minimum_unit;
            $this->subUnitBarcode = $subUnit->barcode ?? '';
            $this->showSubUnitModal = true;

            \Log::info("🚀 [SUB UNIT MODAL] Loaded sub-unit data - Name: {$this->subUnitName}, Buy: {$this->subUnitBuyPrice}, Sale: {$this->subUnitSalePrice}");
        }

        \Log::info("🚀 [SUB UNIT MODAL] Modal state set - showModal: {$this->showSubUnitModal}");
        
        $this->dispatch('showSubUnitModal');
    }

    public function saveSubUnit()
    {
        \Log::info("🚀 [SUB UNIT MODAL] Saving sub-unit");

        $validationRules = [
            'subUnitName' => 'required|string|max:255',
            'subUnitBuyPrice' => 'required|numeric|min:0',
            'subUnitSalePrice' => 'required|numeric|min:0',
            'subUnitQuantity' => 'required|integer|min:1',
        ];

        $subUnitTableHasBarcode = Schema::hasColumn('product_sub_units', 'barcode');

        if ($subUnitTableHasBarcode) {
            $validationRules['subUnitBarcode'] = 'nullable|string|max:255';
        }

        $this->validate($validationRules);

        try {
            $data = [
                'product_id' => $this->product->id,
                'name' => $this->subUnitName,
                'buy_price' => $this->subUnitBuyPrice,
                'sale_price' => $this->subUnitSalePrice,
                'quantity_of_minimum_unit' => $this->subUnitQuantity,
            ];

            if ($subUnitTableHasBarcode) {
                $data['barcode'] = $this->subUnitBarcode ?: null;
            }

            if ($this->subUnitId) {
                // Update existing sub-unit
                \App\Models\ProductSubUnit::where('id', $this->subUnitId)->update($data);
                \Log::info("🚀 [SUB UNIT MODAL] Updated sub-unit ID: {$this->subUnitId}");
            } else {
                // Create new sub-unit
                \App\Models\ProductSubUnit::create($data);
                \Log::info("🚀 [SUB UNIT MODAL] Created new sub-unit");
            }

            // Refresh product data
            $this->loadProduct($this->product->id);

            // Close modal
            $this->closeSubUnitModal();

            // Show success message using SweetAlert
            $successTitle = $this->subUnitId
                ? __t('product.sub_unit_updated', 'Sub-Unit Updated!')
                : __t('product.sub_unit_created', 'Sub-Unit Created!');

            $successMessage = $this->subUnitId
                ? __t('product.sub_unit_updated_successfully', 'Sub-unit has been updated successfully.')
                : __t('product.sub_unit_created_successfully', 'New sub-unit has been created successfully.');

            $pricingSummary = sprintf(
                '%s %s | %s %s',
                __t('product.sale_price', 'Sale Price'),
                currency($this->subUnitSalePrice),
                __t('product.buy_price', 'Buy Price'),
                currency($this->subUnitBuyPrice)
            );

            $this->dispatch('showSweetAlert', [
                'title' => $successTitle,
                'text' => $successMessage . ' ' . $pricingSummary,
                'icon' => 'success',
                'timer' => 2800,
                'showConfirmButton' => false,
                'allowOutsideClick' => false,
                'allowEscapeKey' => true,
            ]);

        } catch (\Exception $e) {
            \Log::error("🚀 [SUB UNIT MODAL] Error saving sub-unit: " . $e->getMessage());
            $this->dispatch('showSweetAlert', [
                'title' => __t('common.error', 'Error'),
                'text' => __t('product.sub_unit_save_failed', 'Failed to save sub-unit. Please check required fields and try again.'),
                'icon' => 'error',
                'confirmButtonText' => 'Try Again'
            ]);
        }
    }

    public function confirmDeleteSubUnit($subUnitId)
    {
        \Log::info("🚀 [SUB UNIT MODAL] Confirming delete for sub-unit ID: {$subUnitId}");

        // Get sub-unit details for better confirmation message
        $subUnit = \App\Models\ProductSubUnit::find($subUnitId);
        $subUnitName = $subUnit ? $subUnit->name : 'Unknown';

        \Log::info("🚀 [SUB UNIT MODAL] Dispatching showSweetAlertConfirm event");

        $this->dispatch('showSweetAlertConfirm', [
            'title' => 'Delete Sub-Unit',
            'text' => "Are you sure you want to delete the sub-unit '{$subUnitName}'? This action cannot be undone!",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Yes, Delete It!',
            'cancelButtonText' => 'Cancel',
            'confirmButtonColor' => '#dc3545',
            'cancelButtonColor' => '#6c757d',
            'allowOutsideClick' => false,
            'allowEscapeKey' => true,
            'subUnitId' => $subUnitId
        ]);
    }

    public function deleteSubUnit($subUnitId)
    {
        \Log::info("🚀 [SUB UNIT MODAL] Deleting sub-unit ID: {$subUnitId}");

        try {
            \App\Models\ProductSubUnit::where('id', $subUnitId)->delete();
            
            // Refresh product data
            $this->loadProduct($this->product->id);

            \Log::info("🚀 [SUB UNIT MODAL] Deleted sub-unit successfully");

            // Show success message using SweetAlert
            \Log::info("🚀 [SUB UNIT MODAL] Dispatching showSweetAlert success event");
            $this->dispatch('showSweetAlert', [
                'title' => 'Sub-Unit Deleted!',
                'text' => 'Sub-unit has been deleted successfully!',
                'icon' => 'success',
                'timer' => 3000,
                'showConfirmButton' => false
            ]);

        } catch (\Exception $e) {
            \Log::error("🚀 [SUB UNIT MODAL] Error deleting sub-unit: " . $e->getMessage());
            $this->dispatch('showSweetAlert', [
                'title' => 'Delete Failed!',
                'html' => '
                    <div style="text-align: center;">
                        <p style="margin-bottom: 15px;">Failed to delete sub-unit. Please try again.</p>
                        <div style="background-color: #f8d7da; padding: 15px; border-radius: 8px; border-left: 4px solid #dc3545;">
                            <strong>Possible Issues:</strong><br>
                            <span style="color: #721c24;">• Sub-unit may be in use by other records</span><br>
                            <span style="color: #721c24;">• Database connection issue</span><br>
                            <span style="color: #721c24;">• Try again or contact support</span>
                        </div>
                    </div>
                ',
                'icon' => 'error',
                'confirmButtonText' => 'Try Again'
            ]);
        }
    }

    public function closeSubUnitModal()
    {
        \Log::info("🚀 [SUB UNIT MODAL] Closing sub-unit modal");

        $this->showSubUnitModal = false;
        $this->resetSubUnitFields();
        
        $this->dispatch('closeSubUnitModal');
    }

    public function resetSubUnitFields()
    {
        $this->subUnitId = null;
        $this->subUnitName = '';
        $this->subUnitBuyPrice = 0;
        $this->subUnitSalePrice = 0;
        $this->subUnitQuantity = 1;
        $this->subUnitBarcode = '';
        $this->resetErrorBag();
    }

    /**
     * Reset stock modal fields
     */
    public function resetStockModalFields()
    {
        // Don't reset operationType if this is a stock-in modal
        if (!$this->isStockInModal) {
            $this->operationType = '';
        }
        $this->quantity = 0;
        // Keep the pre-filled prices from product data
        $this->unitPrice = $this->product->buy_price ?? 0;
        $this->salePrice = $this->product->sale_price ?? 0;
        $this->detail = '';
        $this->resetErrorBag();
    }

    /**
     * Handle operation type change
     */
    public function updatedOperationType()
    {
        // This method will be called automatically when operationType changes
        \Log::info("🔄 [STOCK MODAL] Operation type changed to: " . $this->operationType);
        \Log::info("🔄 [STOCK MODAL] Show modal: " . ($this->showStockModal ? 'true' : 'false'));
        \Log::info("🔄 [STOCK MODAL] Selected warehouse ID: " . $this->selectedWarehouseId);
    }

    /**
     * Handle warehouse selection change in stock-in modal
     */
    public function updatedSelectedWarehouseId()
    {
        if ($this->isStockInModal && $this->selectedWarehouseId) {
            \Log::info("🔄 [STOCK IN MODAL] Warehouse selected: " . $this->selectedWarehouseId);
            
            // Update selected warehouse name for display
            $selectedWarehouse = $this->warehouses->firstWhere('id', $this->selectedWarehouseId);
            if ($selectedWarehouse) {
                $this->selectedWarehouseName = $selectedWarehouse->name;
            }
        }
    }

    /**
     * Process stock operation
     */
    public function processStockOperation($confirm = false)
    {
        \Log::info("🚀 [PROCESS] Starting processStockOperation", [
            'operationType' => $this->operationType,
            'quantity' => $this->quantity,
            'selectedWarehouseId' => $this->selectedWarehouseId,
            'productId' => $this->product ? $this->product->id : 'null',
            'confirm' => $confirm
        ]);

        try {
            $validationRules = [
                'operationType' => 'required|in:stock_in,stock_out,adjustment',
                'quantity' => 'required|numeric|min:0.01',
                'unitPrice' => 'nullable|numeric|min:0',
                'salePrice' => 'nullable|numeric|min:0',
                'detail' => 'nullable|string|max:255'
            ];
            
            // Add warehouse validation for stock-in modal
            if ($this->isStockInModal) {
                $validationRules['selectedWarehouseId'] = 'required|exists:warehouses,id';
            }
            
            $this->validate($validationRules);
            
            \Log::info("🚀 [PROCESS] Validation passed");
        } catch (\Exception $e) {
            \Log::error("🚀 [PROCESS] Validation failed: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Validation failed: ' . $e->getMessage());
            return;
        }

        \Log::info("🚀 [PROCESS] About to check confirm parameter", ['confirm' => $confirm]);

        if (!$confirm) {
            if (!$this->product) {
                \Log::warning("🚀 [CONFIRM] Product not loaded, skipping confirmation");
                return;
            }

            $resolvedCurrentStock = 0;

            if ($this->isStockInModal) {
                // For stock-in modal, current stock is always 0 since product has no warehouse data
                $resolvedCurrentStock = 0;
            } elseif ($this->selectedWarehouseId > 0) {
                $warehouseProduct = WarehouseProduct::where('warehouse_id', $this->selectedWarehouseId)
                    ->where('product_id', $this->product->id)
                    ->first();
                $resolvedCurrentStock = $warehouseProduct ? (float) $warehouseProduct->balance : 0;
            } else {
                $resolvedCurrentStock = (float) $this->totalQuantity;
            }

            \Log::info("🚀 [CONFIRM] Resolved current stock for confirmation", [
                'selectedWarehouseId' => $this->selectedWarehouseId,
                'resolvedCurrentStock' => $resolvedCurrentStock
            ]);

            $newStock = $this->operationType === 'adjustment'
                ? (float) $this->quantity
                : ($this->operationType === 'stock_in'
                    ? $resolvedCurrentStock + (float) $this->quantity
                    : $resolvedCurrentStock - (float) $this->quantity);

            \Log::info("🚀 [CONFIRM] Dispatching confirmStockOperation event", [
                'operationType' => $this->operationType,
                'currentStock' => $resolvedCurrentStock,
                'newStock' => $newStock
            ]);

            // Get warehouse name for confirmation
            $warehouseName = $this->selectedWarehouseName;
            if ($this->isStockInModal && $this->selectedWarehouseId) {
                $selectedWarehouse = $this->warehouses->firstWhere('id', $this->selectedWarehouseId);
                $warehouseName = $selectedWarehouse ? $selectedWarehouse->name : 'N/A';
            }

            $this->dispatch('confirmStockOperation',
                operationType: $this->operationType,
                currentStock: $resolvedCurrentStock,
                newStock: $newStock,
                productName: $this->product->name,
                productSku: $this->product->sku_number,
                productImage: asset('assets/images/default_product.png'),
                warehouseName: $warehouseName,
                operationDate: now()->format('d/m/Y'),
                operationTime: now()->format('H:i:s'),
                documentNumber: 'STK-' . now()->format('YmdHis'),
                unitName: $this->product->unit_name ?? 'pcs'
            );

            return;
        }

        // Additional validation for stock out operations
        if ($this->operationType === 'stock_out' && $this->selectedWarehouseId > 0 && !$this->isStockInModal) {
            $warehouseProduct = WarehouseProduct::where('warehouse_id', $this->selectedWarehouseId)
                ->where('product_id', $this->product->id)
                ->first();
            
            if (!$warehouseProduct || $warehouseProduct->balance < $this->quantity) {
                $this->addError('quantity', 'Insufficient stock. Available: ' . ($warehouseProduct ? $warehouseProduct->balance : 0));
                return;
            }
        }

        try {
            $inventoryService = new InventoryService();
            
            // Handle "Total All Warehouses" case (not applicable for stock-in modal)
            if ($this->selectedWarehouseId == 0 && !$this->isStockInModal) {
                $this->processAllWarehousesOperation($inventoryService);
                return;
            }
            
            $data = [
                'product_id' => $this->product->id,
                'warehouse_id' => $this->selectedWarehouseId,
                'quantity' => $this->quantity,
                'unit_name' => $this->unitName,
                'unit_price' => $this->unitPrice,
                'sale_price' => $this->salePrice,
                'detail' => $this->detail ?: ucfirst(str_replace('_', ' ', $this->operationType)),
                'date_activity' => now()
            ];

            $result = null;

            switch ($this->operationType) {
                case 'stock_in':
                    $result = $inventoryService->stockIn($data);
                    break;
                case 'stock_out':
                    $result = $inventoryService->stockOut($data);
                    break;
                case 'adjustment':
                    $data['new_quantity'] = $this->quantity;
                    unset($data['quantity']);
                    $result = $inventoryService->stockAdjustment($data);
                    break;
            }

            if ($result && $result['success']) {
                $this->handleSuccessfulOperation($result['message']);
            } else {
                $this->dispatch('showErrorMessage', message: 'Stock operation failed');
            }

        } catch (\Exception $e) {
            \Log::error("Stock operation failed: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Stock operation failed: ' . $e->getMessage());
        }
    }

    /**
     * Process operation for all warehouses
     */
    private function processAllWarehousesOperation($inventoryService)
    {
        $warehouses = WarehouseProduct::where('product_id', $this->product->id)
            ->where('balance', '>', 0)
            ->get();

        $successCount = 0;
        $totalWarehouses = $warehouses->count();

        foreach ($warehouses as $warehouseProduct) {
            try {
                $data = [
                    'product_id' => $this->product->id,
                    'warehouse_id' => $warehouseProduct->warehouse_id,
                    'quantity' => $this->quantity,
                    'unit_name' => $this->unitName,
                    'unit_price' => $this->unitPrice,
                    'sale_price' => $this->salePrice,
                    'detail' => $this->detail ?: ucfirst(str_replace('_', ' ', $this->operationType)) . ' (All Warehouses)',
                    'date_activity' => now()
                ];

                $result = null;

                switch ($this->operationType) {
                    case 'stock_in':
                        $result = $inventoryService->stockIn($data);
                        break;
                    case 'stock_out':
                        $result = $inventoryService->stockOut($data);
                        break;
                    case 'adjustment':
                        $data['new_quantity'] = $this->quantity;
                        unset($data['quantity']);
                        $result = $inventoryService->stockAdjustment($data);
                        break;
                }

                if ($result && $result['success']) {
                    $successCount++;
                }
            } catch (\Exception $e) {
                \Log::error("Stock operation failed for warehouse {$warehouseProduct->warehouse_id}: " . $e->getMessage());
            }
        }

        if ($successCount > 0) {
            $message = "Stock operation completed successfully for {$successCount} out of {$totalWarehouses} warehouses";
            $this->handleSuccessfulOperation($message);
        } else {
            $this->dispatch('showErrorMessage', message: 'Stock operation failed for all warehouses');
        }
    }

    /**
     * Handle successful operation
     */
    private function handleSuccessfulOperation($message)
    {
        // Close modal
        $this->showStockModal = false;
        $this->resetStockModalFields();
        
        // Refresh warehouse data
        $this->loadWarehouseProductData();
        
        // Hide modal using JavaScript first
        $this->dispatch('hideStockModal');
        
        // Refresh stock card component
        $this->dispatch('refreshStockCard');

        // Show success message after a short delay to ensure modal is closed
        $this->dispatch('showSuccessMessage', message: $message);
    }

    /**
     * Close stock modal
     */
    public function closeStockModal()
    {
        $this->showStockModal = false;
        $this->isStockInModal = false;
        $this->resetStockModalFields();
        $this->dispatch('hideStockModal');
    }

    /**
     * Test method to set operation type
     */
    public function setOperationType($type)
    {
        $this->operationType = $type;
        \Log::info("🧪 [TEST] Operation type set to: {$this->operationType}");
    }

    /**
     * Simple test method
     */
    public function testMethod()
    {
        \Log::info("🧪 [TEST] Test method called successfully!");
        $this->dispatch('showSuccessMessage', message: 'Test method called!');
    }

    /**
     * Even simpler test method - no events
     */
    public function simpleTest()
    {
        \Log::info("🧪 [SIMPLE] Simple test method called - no events!");
        // Just update a property to show it worked
        $this->detail = "Simple test worked at " . now()->format('H:i:s');
    }

    /**
     * Get ProductService instance
     */
    public function getProductService()
    {
        return app(ProductService::class);
    }

    /**
     * Delete product (change status to inactive)
     */
    public function deleteProduct()
    {
        try {
            if (!$this->product) {
                $this->dispatch('showErrorMessage', message: 'No product selected to delete.');
                return;
            }

            // Use ProductService to soft delete the product
            $result = $this->getProductService()->softDeleteProduct($this->product);

            if ($result['success']) {
                // Reset product detail view
                $this->resetProductDetailView();
                
                // Dispatch refresh event to update the product list
                $this->dispatch('refreshProductList');
                
                // Dispatch event for DataTable reinitialization
                $this->dispatch('refreshDataTable');
                
                // Show success message and redirect to product list
                $redirectUrl = route('menu.menu_product', [], false);
                $this->dispatch('showSuccessMessage', [
                    'message' => $result['message'],
                    'redirectUrl' => $redirectUrl
                ]);
            } else {
                $this->dispatch('showErrorMessage', message: $result['message']);
            }
        } catch (\Exception $e) {
            \Log::error("Error deleting product: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Handle refresh component signal with logging
     */
    public function handleRefreshComponent()
    {
        \Log::info("🔄 ProductDetail: Refresh signal received", [
            'product_id' => $this->productId,
            'product_name' => $this->product ? $this->product->name : 'none',
            'show_add_edit_form' => $this->showAddEditProductForm,
            'timestamp' => now()->toDateTimeString()
        ]);

        // Reload the current product if we have a productId
        if ($this->productId) {
            \Log::info("🔄 ProductDetail: Reloading product data", [
                'product_id' => $this->productId
            ]);
            $this->loadProduct($this->productId);
        }

        // Refresh warehouse product data if we have a product
        if ($this->product) {
            \Log::info("🔄 ProductDetail: Refreshing warehouse product data", [
                'product_id' => $this->product->id
            ]);
            $this->loadWarehouseProductData();
        }

        \Log::info("🔄 ProductDetail: Refresh completed successfully");
    }

    /**
     * Reset product detail view after deletion
     */
    private function resetProductDetailView()
    {
        \Log::info("🔄 ProductDetail: Resetting product detail view after deletion", [
            'product_id' => $this->productId,
            'product_name' => $this->product ? $this->product->name : 'none'
        ]);

        // Reset all product-related properties
        $this->productId = null;
        $this->product = null;
        $this->warehouseProducts = collect();
        $this->totalQuantity = 0;
        $this->averageSalePrice = 0;
        $this->averageBuyPrice = 0;
        $this->totalValue = 0;
        
        // Reset form-related properties
        $this->showAddEditProductForm = false;
        
        // Reset stock modal properties
        $this->selectedWarehouseId = null;
        $this->selectedWarehouseName = null;
        $this->operationType = null;
        $this->quantity = null;
        $this->unitPrice = null;
        $this->salePrice = null;
        $this->detail = null;
        $this->currentStock = null;

        \Log::info("🔄 ProductDetail: Product detail view reset completed");
    }

    
} 
