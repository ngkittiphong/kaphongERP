<?php

namespace App\Livewire\Warehouse;

use App\Models\TransferSlip;
use App\Models\TransferSlipDetail;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\TransferSlipStatus;
use App\Models\Company;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseAddTransferForm extends Component
{
    use WithFileUploads;

    // Form data
    public $transferSlipNumber;
    public $companyName = '';
    public $companyAddress = '';
    public $taxId = '';
    public $tel = '';
    public $dateRequest;
    public $userRequestName = '';
    public $deliverName = '';
    public $warehouseOriginId = '';
    public $warehouseDestinationId = '';
    public $description = '';
    public $note = '';
    
    // Product transfer details
    public $transferProducts = [];
    public $maxProducts = 20;
    
    // Available data for dropdowns
    public $warehouses = [];
    public $products = [];
    public $users = [];
    
    // Typeahead dataset for JavaScript
    public $productTypeaheadDataset = [];
    
    // Company profile data
    public $companyProfile = null;
    public $mainBranch = null;
    
    // Product search functionality (legacy server-side search - kept for fallback)
    public $productSearchResults = [];
    public $showProductSearch = false;
    public $searchingProduct = false;
    
    // Form state
    public $showForm = false;
    public $isSubmitting = false;
    public $originWarehouseSelected = false;
    
    protected $listeners = [
        'showAddForm' => 'showAddForm',
        'showAddFormWithPreselection' => 'showAddFormWithPreselection',
        'transferSlipCreated' => 'handleTransferSlipCreated',
        'selectProductFromSearch' => 'selectProductFromSearch',
        'clearProductSearch' => 'clearProductSearch',
        'confirmSubmit' => 'confirmSubmit',
    ];
    
    protected $rules = [
        'companyName' => 'required|string|max:255',
        'companyAddress' => 'nullable|string|max:500',
        'taxId' => 'nullable|string|max:50',
        'tel' => 'nullable|string|max:20',
        'dateRequest' => 'required|date',
        'userRequestName' => 'required|string|max:255',
        'deliverName' => 'nullable|string|max:255',
        'warehouseOriginId' => 'required|exists:warehouses,id',
        'warehouseDestinationId' => 'required|exists:warehouses,id|different:warehouseOriginId',
        'description' => 'nullable|string|max:1000',
        'note' => 'nullable|string|max:1000',
        'transferProducts' => 'required|array|min:1',
        'transferProducts.*.product_id' => 'nullable|exists:products,id',
        'transferProducts.*.quantity' => 'nullable|numeric|min:1',
        'transferProducts.*.cost_per_unit' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'warehouseDestinationId.different' => 'Destination warehouse must be different from origin warehouse.',
        'transferProducts.required' => 'At least one product must be added to the transfer.',
        'transferProducts.*.product_id.exists' => 'Please select a valid product.',
        'transferProducts.*.quantity.min' => 'Quantity must be at least 1.',
        'transferProducts.*.cost_per_unit.min' => 'Cost per unit must be 0 or greater.',
    ];

    public function mount()
    {
        $this->dateRequest = now()->format('Y-m-d');
        $this->loadCompanyProfile();
        $this->loadDropdownData();
        $this->addEmptyProduct();
        
        // Check for preselection data from session
        $preselectionData = session('transfer_preselection');
        if ($preselectionData) {
            Log::info('🔥 WarehouseAddTransferForm: Found preselection data in session', $preselectionData);
            $this->applyPreselection($preselectionData);
            // Clear the session data after use
            session()->forget('transfer_preselection');
        }
        
        // Dispatch event with product data for typeahead initialization
        $this->dispatch('transferFormReady', [
            'productData' => $this->productTypeaheadDataset
        ]);
    }

    public function updatedWarehouseOriginId($value)
    {
        $this->resetErrorBag(['warehouseOriginId']);

        if (empty($value)) {
            $this->originWarehouseSelected = false;
        }
    }

    public function proceedToProductSelection()
    {
        Log::info('🔥 proceedToProductSelection called', ['warehouseOriginId' => $this->warehouseOriginId]);

        $this->resetErrorBag(['warehouseOriginId']);

        if (empty($this->warehouseOriginId)) {
            Log::warning('🔥 proceedToProductSelection blocked: origin warehouse missing');
            $this->addError('warehouseOriginId', 'Please select an origin warehouse before proceeding.');
            return;
        }

        $this->originWarehouseSelected = true;
        Log::info('🔥 Origin warehouse confirmed, product selection unlocked');
    }

    public function loadCompanyProfile()
    {
        // Load company profile (assuming there's only one company)
        $this->companyProfile = Company::first();
        
        if ($this->companyProfile) {
            // Load main branch (head office)
            $this->mainBranch = Branch::where('company_id', $this->companyProfile->id)
                ->where('is_head_office', true)
                ->first();
            
            // Prefill company data
            $this->companyName = $this->companyProfile->company_name_en ?? $this->companyProfile->company_name_th ?? '';
            $this->taxId = $this->companyProfile->tax_no ?? '';
            
            // Prefill company address from main branch
            if ($this->mainBranch) {
                $this->companyAddress = $this->mainBranch->address_en ?? $this->mainBranch->address_th ?? '';
                $this->tel = $this->mainBranch->phone_number ?? '';
            }
        }
        
        // Prefill user request name
        $this->userRequestName = auth()->user()->username ?? '';
    }

    public function loadDropdownData()
    {
        $this->warehouses = Warehouse::with('branch')
            ->where('warehouse_status_id', '!=', 0)
            ->get();
            
        $this->products = Product::with(['type', 'group', 'status'])
            ->get();
            
        $this->users = User::all();
        
        // Build typeahead dataset
        if ($this->products->count() > 0) {
            $this->productTypeaheadDataset = $this->products->map(function ($product) {
                $name = $product->name ?? '';
                $sku = $product->sku_number ?? '';

                return [
                    'id'      => $product->id,
                    'name'    => $name,
                    'sku'     => $sku,
                    'unit'    => $product->unit_name ?? '',
                    'price'   => $product->buy_price ?? 0,
                    'search'  => strtolower(trim($name . ' ' . $sku)),
                    'display' => trim($name . ' (' . $sku . ')'),
                ];
            })->values()->toArray();
        } else {
            $this->productTypeaheadDataset = [];
        }
    }

    public function addEmptyProduct()
    {
        if (count($this->transferProducts) < $this->maxProducts) {
            $this->transferProducts[] = [
                'product_id' => '',
                'product_name' => '',
                'product_description' => '',
                'quantity' => 1,
                'unit_name' => '',
                'cost_per_unit' => 0,
                'cost_total' => 0,
                'available_quantity' => 0,
            ];
            
            $this->dispatch('transferProductAdded');
        }
    }

    public function removeProduct($index)
    {
        if (count($this->transferProducts) > 1) {
            unset($this->transferProducts[$index]);
            $this->transferProducts = array_values($this->transferProducts);
            
            // Dispatch event to re-initialize typeahead after product removal
            $this->dispatch('transferProductRemoved');
        }
    }

    /**
     * Get available quantity for a product in a specific warehouse
     */
    public function getAvailableQuantity($warehouseId, $productId)
    {
        if (empty($warehouseId) || empty($productId)) {
            return 0;
        }

        $warehouseProduct = \App\Models\WarehouseProduct::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();

        return $warehouseProduct ? $warehouseProduct->balance : 0;
    }


    public function updatedTransferProducts($value, $index)
    {
        if (str_contains($index, '.product_id')) {
            $this->selectProduct($index);
        } elseif (str_contains($index, '.quantity') || str_contains($index, '.cost_per_unit')) {
            $this->calculateProductTotal($index);
            
            // Validate quantity against available stock
            if (str_contains($index, '.quantity')) {
                $this->validateQuantity($index);
            }
        }
    }

    /**
     * Validate quantity against available stock
     */
    public function validateQuantity($index)
    {
        $productIndex = explode('.', $index)[0];
        $product = $this->transferProducts[$productIndex] ?? null;
        
        if (!$product || empty($product['product_id'])) {
            return;
        }
        
        $availableQuantity = $this->getAvailableQuantity($this->warehouseOriginId, $product['product_id']);
        $requestedQuantity = $product['quantity'] ?? 0;
        
        // Clear previous quantity errors
        $this->resetErrorBag(["transferProducts.{$productIndex}.quantity"]);
        
        if ($requestedQuantity > $availableQuantity) {
            $this->addError("transferProducts.{$productIndex}.quantity", 
                "Requested quantity ({$requestedQuantity}) exceeds available stock ({$availableQuantity})");
        }
    }

    public function searchProducts($searchTerm, $productIndex)
    {
        // Kept for backward compatibility; not used when client-side typeahead is active.
        if (strlen($searchTerm) < 2) {
            $this->productSearchResults = [];
            $this->showProductSearch = false;
            return;
        }

        $this->searchingProduct = true;
        $this->productSearchResults = Product::where(function($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('sku_number', 'like', '%' . $searchTerm . '%');
            })
            ->with(['type', 'group', 'status'])
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku_number,
                    'unit' => $product->unit_name,
                    'buy_price' => $product->buy_price ?? 0,
                    'type' => $product->type->name ?? 'N/A',
                    'display_text' => $product->name . ' (' . $product->sku_number . ')'
                ];
            })
            ->toArray();

        $this->showProductSearch = true;
        $this->searchingProduct = false;
    }


    public function selectProductFromSearch($id, $index)
    {
        $product = Product::find($id);
        if ($product && isset($this->transferProducts[$index])) {
            $this->transferProducts[$index]['product_id'] = $product->id;
            $this->transferProducts[$index]['product_name'] = $product->name;
            $this->transferProducts[$index]['product_description'] = $product->buy_description ?? '';
            $this->transferProducts[$index]['product_search'] = $product->name . ' (' . $product->sku_number . ')';
            $this->transferProducts[$index]['unit_name'] = $product->unit_name;
            $this->transferProducts[$index]['cost_per_unit'] = $product->buy_price ?? 0;
            
            // Get available quantity from origin warehouse
            $availableQuantity = $this->getAvailableQuantity($this->warehouseOriginId, $product->id);
            $this->transferProducts[$index]['available_quantity'] = $availableQuantity;
            
            $this->calculateProductTotal($index . '.quantity');
        }
        
        // Clear search results
        $this->productSearchResults = [];
        $this->showProductSearch = false;
    }

    public function clearProductSearch()
    {
        $this->productSearchResults = [];
        $this->showProductSearch = false;
    }

    public function confirmSubmit($confirmed)
    {
        Log::info('🔥 WarehouseAddTransferForm: confirmSubmit() called with:', ['confirmed' => $confirmed]);
        $this->submit($confirmed);
    }


    public function selectProduct($index)
    {
        $productIndex = explode('.', $index)[0];
        $productId = $this->transferProducts[$productIndex]['product_id'];
        
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->transferProducts[$productIndex]['product_name'] = $product->name;
                $this->transferProducts[$productIndex]['product_description'] = $product->buy_description ?? '';
                $this->transferProducts[$productIndex]['unit_name'] = $product->unit_name;
                $this->transferProducts[$productIndex]['cost_per_unit'] = $product->buy_price ?? 0;
                
                // Get available quantity from origin warehouse
                $availableQuantity = $this->getAvailableQuantity($this->warehouseOriginId, $product->id);
                $this->transferProducts[$productIndex]['available_quantity'] = $availableQuantity;
                
                $this->calculateProductTotal($productIndex . '.quantity');
            }
        }
    }

    public function calculateProductTotal($index)
    {
        $productIndex = explode('.', $index)[0];
        $quantity = $this->transferProducts[$productIndex]['quantity'] ?? 0;
        $costPerUnit = $this->transferProducts[$productIndex]['cost_per_unit'] ?? 0;
        $this->transferProducts[$productIndex]['cost_total'] = $quantity * $costPerUnit;
    }

    public function getTotalQuantity()
    {
        return array_sum(array_column($this->transferProducts, 'quantity'));
    }

    public function getTotalCost()
    {
        return array_sum(array_column($this->transferProducts, 'cost_total'));
    }

    public function showAddForm()
    {
        Log::info('🔥 WarehouseAddTransferForm: showAddForm() called');
        $this->showForm = true;
        $this->resetForm();
    }

    public function showAddFormWithPreselection($data)
    {
        Log::info('🔥 WarehouseAddTransferForm: showAddFormWithPreselection() called', $data);
        $this->showForm = true;
        $this->resetForm();
        $this->applyPreselection($data);
    }

    private function applyPreselection($data)
    {
        // Preselect the warehouse as origin warehouse
        if (isset($data['warehouseId'])) {
            $this->warehouseOriginId = $data['warehouseId'];
            Log::info('🔥 Preselected origin warehouse:', ['warehouseId' => $data['warehouseId']]);
            $this->originWarehouseSelected = true;
        }
        
        // Preselect the product in the first transfer product row
        if (isset($data['productId']) && count($this->transferProducts) > 0) {
            $product = Product::find($data['productId']);
            if ($product) {
                $this->transferProducts[0]['product_id'] = $product->id;
                $this->transferProducts[0]['product_name'] = $product->name;
                $this->transferProducts[0]['product_description'] = $product->buy_description ?? '';
                $this->transferProducts[0]['product_search'] = $product->name . ' (' . $product->sku_number . ')';
                $this->transferProducts[0]['unit_name'] = $product->unit_name;
                $this->transferProducts[0]['cost_per_unit'] = $product->buy_price ?? 0;
                $this->transferProducts[0]['quantity'] = 1; // Default quantity
                
                // Get available quantity from origin warehouse
                $availableQuantity = $this->getAvailableQuantity($this->warehouseOriginId, $product->id);
                $this->transferProducts[0]['available_quantity'] = $availableQuantity;
                
                $this->calculateProductTotal('0.quantity');
                
                Log::info('🔥 Preselected product:', [
                    'productId' => $product->id,
                    'productName' => $product->name,
                    'sku' => $product->sku_number,
                    'availableQuantity' => $availableQuantity
                ]);
            }
        }
    }


    public function hideForm()
    {
        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('hideAddForm');
    }

    public function handleTransferSlipCreated($transferSlipId)
    {
        // This method can be used for additional handling after transfer creation
        // For now, we'll just log it
        Log::info('Transfer slip created with ID:', ['id' => $transferSlipId]);
    }

    public function resetForm()
    {
        $this->dateRequest = now()->format('Y-m-d');
        $this->loadCompanyProfile(); // Reload company profile data
        $this->deliverName = '';
        $this->warehouseOriginId = '';
        $this->warehouseDestinationId = '';
        $this->description = '';
        $this->note = '';
        $this->transferProducts = [];
        $this->addEmptyProduct();
        $this->isSubmitting = false;
        $this->originWarehouseSelected = false;
    }

    public function showTransferConfirmation()
    {
        // Get warehouse names for confirmation
        $originWarehouse = Warehouse::find($this->warehouseOriginId);
        $destinationWarehouse = Warehouse::find($this->warehouseDestinationId);
        
        // Generate transfer slip number for preview
        $transferSlipNumber = TransferSlip::generateTransferSlipNumber();
        
        // Calculate totals
        $totalQuantity = $this->getTotalQuantity();
        $totalCost = $this->getTotalCost();
        $productCount = count($this->transferProducts);
        
        Log::info('🔥 Dispatching confirmTransferCreation event with transfer slip number:', ['number' => $transferSlipNumber]);
        
        $this->dispatch('confirmTransferCreation', [
            'transferSlipNumber' => $transferSlipNumber,
            'originWarehouse' => $originWarehouse ? $originWarehouse->name : 'Unknown',
            'destinationWarehouse' => $destinationWarehouse ? $destinationWarehouse->name : 'Unknown',
            'totalQuantity' => $totalQuantity,
            'totalCost' => $totalCost,
            'productCount' => $productCount,
            'transferProducts' => array_map(function($product) {
                return [
                    'name' => $product['product_name'] ?? 'Unknown Product',
                    'quantity' => $product['quantity'] ?? 0,
                    'unit' => $product['unit_name'] ?? 'pcs',
                    'cost' => $product['cost_total'] ?? 0
                ];
            }, $this->transferProducts)
        ]);
    }

    public function submit($confirmed = false)
    {
        Log::info('🔥 WarehouseAddTransferForm: submit() method called', ['confirmed' => $confirmed]);
        Log::info('🔥 Form data:', [
            'companyName' => $this->companyName,
            'warehouseOriginId' => $this->warehouseOriginId,
            'warehouseDestinationId' => $this->warehouseDestinationId,
            'transferProducts' => $this->transferProducts
        ]);
        if (!$this->originWarehouseSelected) {
            Log::info('Origin warehouse not confirmed yet, skipping submission');
            return;
        }
        
        try {
            Log::info('🔥 Starting validation...');
            $this->validate();
            Log::info('🔥 Validation passed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('🔥 Validation failed:', ['errors' => $e->errors()]);
            throw $e;
        }
        
        // Filter out empty products (products without product_id)
        $this->transferProducts = array_filter($this->transferProducts, function($product) {
            return !empty($product['product_id']) && !empty($product['product_name']);
        });
        
        // Re-index the array after filtering
        $this->transferProducts = array_values($this->transferProducts);
        
        Log::info('🔥 Filtered products:', ['products' => $this->transferProducts]);
        
        if (empty($this->transferProducts)) {
            Log::error('🔥 No products found after filtering');
            $this->addError('transferProducts', 'At least one product must be added to the transfer.');
            return;
        }
        
        // Validate available quantities only for products that have been selected
        $insufficientStockProducts = [];
        foreach ($this->transferProducts as $index => $product) {
            $availableQuantity = $this->getAvailableQuantity($this->warehouseOriginId, $product['product_id']);
            $requestedQuantity = $product['quantity'] ?? 0;
            
            if ($availableQuantity <= 0) {
                $insufficientStockProducts[] = $product['product_name'] ?? 'Unknown Product';
                $this->addError("transferProducts.{$index}.product_id", 
                    "Insufficient stock for {$product['product_name']}. Available: {$availableQuantity}");
            } elseif ($requestedQuantity > $availableQuantity) {
                $insufficientStockProducts[] = $product['product_name'] ?? 'Unknown Product';
                $this->addError("transferProducts.{$index}.quantity", 
                    "Requested quantity ({$requestedQuantity}) exceeds available stock ({$availableQuantity}) for {$product['product_name']}");
            }
        }
        
        if (!empty($insufficientStockProducts)) {
            Log::error('🔥 Insufficient stock detected:', ['products' => $insufficientStockProducts]);
            $this->addError('general', 'Cannot proceed with transfer. Some products have insufficient stock in the origin warehouse.');
            return;
        }
        
        // If not confirmed, show confirmation dialog
        if (!$confirmed) {
            $this->showTransferConfirmation();
            return;
        }
        
        $this->isSubmitting = true;
        Log::info('🔥 Starting database transaction...');
        
        try {
            DB::beginTransaction();
            
            // Generate transfer slip number
            $this->transferSlipNumber = TransferSlip::generateTransferSlipNumber();
            Log::info('🔥 Generated transfer slip number:', ['number' => $this->transferSlipNumber]);
            
            // Get warehouse names
            $originWarehouse = Warehouse::find($this->warehouseOriginId);
            $destinationWarehouse = Warehouse::find($this->warehouseDestinationId);
            Log::info('🔥 Warehouses found:', [
                'origin' => $originWarehouse ? $originWarehouse->name : 'NOT FOUND',
                'destination' => $destinationWarehouse ? $destinationWarehouse->name : 'NOT FOUND'
            ]);
            
            // Get In Transit status (bypassing Approved)
            $inTransitStatus = TransferSlipStatus::where('name', 'In Transit')->first();
            Log::info('🔥 In Transit status found:', ['status_id' => $inTransitStatus ? $inTransitStatus->id : 'NOT FOUND']);
            
            // Create transfer slip
            Log::info('🔥 Creating transfer slip with data:', [
                'user_request_id' => auth()->id(),
                'transfer_slip_number' => $this->transferSlipNumber,
                'company_name' => $this->companyName,
                'warehouse_origin_id' => $this->warehouseOriginId,
                'warehouse_destination_id' => $this->warehouseDestinationId,
                'total_quantity' => $this->getTotalQuantity(),
                'transfer_slip_status_id' => $inTransitStatus->id,
            ]);
            
            $transferSlip = TransferSlip::create([
                'user_request_id' => auth()->id(),
                'user_receive_id' => auth()->id(), // Set to current user as default
                'transfer_slip_number' => $this->transferSlipNumber,
                'company_name' => $this->companyName,
                'company_address' => $this->companyAddress,
                'tax_id' => $this->taxId,
                'tel' => $this->tel,
                'date_request' => $this->dateRequest,
                'user_request_name' => $this->userRequestName,
                'deliver_name' => $this->deliverName,
                'date_receive' => null,
                'user_receive_name' => auth()->user()->username ?? 'Unknown',
                'warehouse_origin_id' => $this->warehouseOriginId,
                'warehouse_origin_name' => $originWarehouse->name,
                'warehouse_destination_id' => $this->warehouseDestinationId,
                'warehouse_destination_name' => $destinationWarehouse->name,
                'total_quantity' => $this->getTotalQuantity(),
                'transfer_slip_status_id' => $inTransitStatus->id,
                'description' => $this->description,
                'note' => $this->note,
            ]);
            
            Log::info('🔥 Transfer slip created successfully with ID:', ['id' => $transferSlip->id]);
            
            // Create transfer slip details
            Log::info('🔥 Creating transfer slip details for products:', ['products' => $this->transferProducts]);
            foreach ($this->transferProducts as $index => $product) {
                Log::info("🔥 Creating detail for product {$index}:", ['product' => $product]);
                TransferSlipDetail::create([
                    'transfer_slip_id' => $transferSlip->id,
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'product_description' => $product['product_description'],
                    'quantity' => $product['quantity'],
                    'unit_name' => $product['unit_name'],
                    'cost_per_unit' => $product['cost_per_unit'],
                    'cost_total' => $product['cost_total'],
                ]);
                Log::info("🔥 Detail created for product {$index}");
            }
            
            // Since we're creating the transfer with "In Transit" status, 
            // we need to reduce stock in the sender warehouse immediately
            Log::info('🔥 Reducing sender warehouse stock for In Transit status...');
            $this->reduceSenderWarehouseStock($transferSlip);
            
            DB::commit();
            Log::info('🔥 Database transaction committed successfully');
            
            // Dispatch events
            Log::info('🔥 Dispatching events...');
            $this->dispatch('transferSlipCreated', $transferSlip->id);
            $this->dispatch('transferSlipListUpdated');
            $this->dispatch('showSuccessMessage', 'Transfer slip created successfully!');
            Log::info('🔥 Events dispatched successfully');
            
            // Reset form and hide
            Log::info('🔥 Resetting form and hiding...');
            $this->resetForm();
            $this->showForm = false;
            $this->dispatch('hideAddForm');
            Log::info('🔥 Form reset and hidden successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('🔥 Error creating transfer slip:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('general', 'An error occurred while creating the transfer slip. Please try again.');
        } finally {
            $this->isSubmitting = false;
            Log::info('🔥 Submit method completed, isSubmitting set to false');
        }
    }

    /**
     * Reduce stock in sender warehouse when creating transfer with In Transit status
     */
    private function reduceSenderWarehouseStock($transferSlip)
    {
        Log::info("🔥 reduceSenderWarehouseStock called for new transfer", [
            'transfer_slip_id' => $transferSlip->id
        ]);
        
        if (!$transferSlip->transferSlipDetails) {
            Log::warning("🔥 No transfer slip details found");
            return;
        }

        $senderWarehouseId = $transferSlip->warehouse_origin_id;
        
        foreach ($transferSlip->transferSlipDetails as $detail) {
            try {
                // Use InventoryService for stock out operation
                $inventoryService = app(\App\Services\InventoryService::class);
                
                $result = $inventoryService->stockOut([
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->cost_per_unit ?? 0,
                    'sale_price' => $detail->cost_per_unit ?? 0,
                    'detail' => "Transfer to Warehouse #{$transferSlip->warehouse_destination_id} - Transfer Slip #{$transferSlip->transfer_slip_number}",
                    'transfer_slip_id' => $transferSlip->id,
                    'date_activity' => now(),
                ]);
                
                Log::info("Reduced sender warehouse stock using InventoryService", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'quantity_reduced' => $detail->quantity,
                    'new_balance' => $result['new_balance'],
                    'inventory_id' => $result['inventory_id']
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to reduce sender warehouse stock", [
                    'warehouse_id' => $senderWarehouseId,
                    'product_id' => $detail->product_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-add-transfer-form');
    }
}

