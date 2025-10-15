<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\WarehouseStatus;
use App\Models\WarehouseProduct;
use App\Models\Inventory;
use App\Models\TransferSlip;
use App\Models\Product;
use App\Http\Controllers\WarehouseController;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseDetail extends Component
{
    public $warehouse;
    public $warehouseId; // Store warehouse ID for proper hydration
    public $showAddWarehouseForm = false;
    public $showEditWarehouseForm = false;
    
    // Warehouse form fields (matching ER diagram)
    public $branch_id, $user_create_id, $main_warehouse, $name, $date_create, $warehouse_status_id, $avr_remain_price;
    
    public $branches = [];
    public $warehouse_statuses = [];
    
    // Main warehouse change tracking
    public $originalMainWarehouse = false;
    public $pendingMainWarehouseChange = false;
    
    // Inventory and movement data
    public $warehouseInventory = [];
    public $warehouseMovements = [];
    
    // Branch selection for viewing warehouse details
    public $selectedBranchId = null;
    
    // Product edit modal properties
    public $showProductEditModal = false;
    public $selectedProduct = null;
    public $selectedProductId = null;
    public $selectedWarehouseId = null;
    public $selectedWarehouseName = '';
    public $operationType = '';
    public $quantity = 0;
    public $unitName = '';
    public $unitPrice = 0;
    public $salePrice = 0;
    public $detail = '';
    public $currentStock = 0;

    // Tab state management
    public $activeTab = 'detail';

    protected $listeners = [
        'warehouseSelected' => 'loadWarehouse',
        'showAddWarehouseForm' => 'displayAddWarehouseForm',
        'showEditWarehouseForm' => 'displayEditWarehouseForm',
        'refreshComponent' => 'handleRefreshComponent',
        'createWarehouse' => 'createWarehouse',
        'deleteWarehouse' => 'deleteWarehouse',
        'reactivateWarehouse' => 'reactivateWarehouse',
        'cancelForm' => 'cancelForm',
        'closeProductEditModal' => 'closeProductEditModal',
        'processProductStockOperation' => 'processProductStockOperation'
    ];

    protected function getListeners()
    {
        return [
            'warehouseSelected' => 'loadWarehouse',
            'showAddWarehouseForm' => 'displayAddWarehouseForm',
            'showEditWarehouseForm' => 'displayEditWarehouseForm',
            'refreshComponent' => 'handleRefreshComponent',
            'createWarehouse' => 'createWarehouse',
            'deleteWarehouse' => 'deleteWarehouse',
            'reactivateWarehouse' => 'reactivateWarehouse',
            'cancelForm' => 'cancelForm',
            'closeProductEditModal' => 'closeProductEditModal',
            'processProductStockOperation' => 'processProductStockOperation'
        ];
    }

    protected $rules = [
        'branch_id' => 'required|exists:branches,id',
        'user_create_id' => 'required|exists:users,id',
        'main_warehouse' => 'boolean',
        'name' => 'required|string|max:150',
        'date_create' => 'required|date',
        'warehouse_status_id' => 'required|exists:warehouse_statuses,id',
        'avr_remain_price' => 'nullable|numeric|min:0',
    ];

    public function getRules()
    {
        \Log::info("🔍 getRules called", [
            'main_warehouse' => $this->main_warehouse,
            'originalMainWarehouse' => $this->originalMainWarehouse,
            'pendingMainWarehouseChange' => $this->pendingMainWarehouseChange
        ]);
        
        $rules = $this->rules;

        // Add custom validation for main warehouse uniqueness per branch
        // Only apply this validation if the user is trying to set it as main and it wasn't originally main,
        // AND they haven't already confirmed the change (pendingMainWarehouseChange is false after confirmation)
        if ($this->main_warehouse && !$this->originalMainWarehouse && !$this->pendingMainWarehouseChange) {
            \Log::info("🚨 Applying main warehouse validation");
            $rules['main_warehouse'] = [
                'boolean',
                function ($attribute, $value, $fail) {
                    $existingMainWarehouse = Warehouse::where('branch_id', $this->branch_id)
                        ->where('main_warehouse', 1)
                        ->where('id', '!=', $this->warehouse->id)
                        ->first();
                    
                    if ($existingMainWarehouse) {
                        $fail(__t('warehouse.main_warehouse_validation_error', 'There is already a main warehouse') . " '{$existingMainWarehouse->name}' " . __t('warehouse.only_one_main_warehouse_per_branch', 'in this branch. Only one main warehouse per branch is allowed.'));
                    }
                }
            ];
        }
        
        // If the user has confirmed the main warehouse change, bypass the validation
        if ($this->main_warehouse && $this->pendingMainWarehouseChange) {
            \Log::info("✅ Bypassing main warehouse validation - user confirmed change");
            // Remove any existing main_warehouse validation rules
            unset($rules['main_warehouse']);
            $rules['main_warehouse'] = ['boolean'];
        }
        
        \Log::info("🔍 Final rules for main_warehouse:", ['rules' => $rules['main_warehouse'] ?? 'not set']);
        return $rules;
    }

    protected $messages = [
        'branch_id.required' => 'Please select a branch.',
        'branch_id.exists' => 'The selected branch does not exist.',
        'user_create_id.required' => 'Please select a user.',
        'user_create_id.exists' => 'The selected user does not exist.',
        'name.required' => 'Warehouse name is required.',
        'name.max' => 'Warehouse name must not exceed 150 characters.',
        'date_create.required' => 'Creation date is required.',
        'date_create.date' => 'Please enter a valid date.',
        'warehouse_status_id.required' => 'Please select a status.',
        'warehouse_status_id.exists' => 'The selected status does not exist.',
        'avr_remain_price.numeric' => 'Average remain price must be a number.',
        'avr_remain_price.min' => 'Average remain price cannot be negative.',
    ];

    public function mount()
    {
        \Log::info("🔥 WarehouseDetail::mount called");
        $this->branches = Branch::all();
        $this->warehouse_statuses = WarehouseStatus::all();
        \Log::info("🔥 Branches loaded:", ['count' => $this->branches->count()]);
        \Log::info("🔥 Warehouse statuses loaded:", ['count' => $this->warehouse_statuses->count()]);
        
        // Set default values
        $this->user_create_id = auth()->id() ?? 1; // Default to current user or first user
        $this->date_create = now()->format('Y-m-d\TH:i');
        $this->main_warehouse = false;
        $this->avr_remain_price = 0.00;
        
        // Check for warehouse_id in query parameters
        if (request()->has('warehouse_id')) {
            $warehouseId = request()->get('warehouse_id');
            \Log::info("🔥 Warehouse ID from URL parameter: {$warehouseId}");
            $this->loadWarehouse($warehouseId);
        } else {
            // Do NOT preselect any warehouse on load; show empty detail until user selects
            $this->warehouse = null;
            $this->selectedBranchId = null;
        }
        
        // Note: Event listeners are handled via JavaScript dispatch in Livewire 3.x
    }

    public function loadWarehouse($data = null)
    {
        \Log::info("🔥 WarehouseDetail::loadWarehouse called with:", ['data' => $data]);
        \Log::info("🔥 WarehouseDetail::loadWarehouse - Current warehouse:", ['id' => $this->warehouse ? $this->warehouse->id : 'null']);
        
        // Handle different data formats
        if (is_array($data) && isset($data['warehouseId'])) {
            // Event data format: { warehouseId: 123 }
            $warehouseId = $data['warehouseId'];
        } elseif (is_numeric($data)) {
            // Direct ID format
            $warehouseId = $data;
        } else {
            \Log::error("🔥 Invalid data format for loadWarehouse: " . json_encode($data));
            return;
        }
        
        \Log::info("🔥 Extracted warehouseId: {$warehouseId}");
        
        // Store the warehouse ID for proper hydration (matching Product Detail pattern)
        $this->warehouseId = $warehouseId;
        
        $this->showEditWarehouseForm = false;
        $this->showAddWarehouseForm = false;
        
        // Load warehouse with relationships (matching branch pattern)
        $this->warehouse = Warehouse::with(['branch', 'status', 'userCreate', 'inventories', 'warehouseProducts.product'])->where('warehouse_status_id', '!=', 0)->find($warehouseId) ?? null;
        \Log::info("🔥 Warehouse loaded:", ['name' => $this->warehouse ? $this->warehouse->name : 'null']);
        
        // Store original main warehouse status for change tracking
        if ($this->warehouse) {
            $this->originalMainWarehouse = (bool) $this->warehouse->main_warehouse;
            \Log::info("🔥 Original main warehouse status:", ['main_warehouse' => $this->originalMainWarehouse]);
        }
        
        // Load inventory and movement data
        if ($this->warehouse) {
            $this->loadWarehouseInventory();
            $this->loadWarehouseMovements();
        }
        
        // Do NOT persist selected warehouse in session and do NOT preselect branch
        // Reset branch dropdown so user must choose explicitly
        $this->selectedBranchId = null;
        
        // Dispatch event to notify frontend that warehouse data has been loaded
        if ($this->warehouse) {
            $this->dispatch('warehouseLoaded', [
                'warehouseId' => $this->warehouse->id,
                'warehouseName' => $this->warehouse->name
            ]);
            \Log::info("📡 Dispatching warehouseLoaded event for warehouse: {$this->warehouse->name}");
        }
    }

    public function displayAddWarehouseForm()
    {
        $this->showAddWarehouseForm = true;
        $this->showEditWarehouseForm = false;
        $this->resetErrorBag();
        $this->reset([
            'branch_id', 'user_create_id', 'main_warehouse', 'name', 'date_create', 'warehouse_status_id', 'avr_remain_price'
        ]);
        
        // Set forced values for hidden fields
        $this->user_create_id = auth()->id() ?? 1; // Force current user
        $this->date_create = now()->format('Y-m-d\TH:i'); // Force today's date
        $this->main_warehouse = false; // Force not selected
        $this->avr_remain_price = 0.00; // Force 0
        
        // Force Active status (ID 1)
        $activeStatus = \App\Models\WarehouseStatus::where('name', 'Active')->first();
        if ($activeStatus) {
            $this->warehouse_status_id = $activeStatus->id;
        }
    }

    public function displayEditWarehouseForm()
    {
        if ($this->warehouse) {
            $this->showEditWarehouseForm = true;
            $this->showAddWarehouseForm = false;
            $this->resetErrorBag();
            
            // Populate form fields with current warehouse data
            $this->branch_id = $this->warehouse->branch_id;
            $this->user_create_id = $this->warehouse->user_create_id;
            $this->main_warehouse = $this->warehouse->main_warehouse;
            $this->name = $this->warehouse->name;
            $this->date_create = $this->warehouse->date_create->format('Y-m-d\TH:i');
            $this->warehouse_status_id = $this->warehouse->warehouse_status_id;
            $this->avr_remain_price = $this->warehouse->avr_remain_price;
        }
    }

    public function cancelForm()
    {
        $this->showAddWarehouseForm = false;
        $this->showEditWarehouseForm = false;
        $this->resetErrorBag();
        $this->reset([
            'branch_id', 'user_create_id', 'main_warehouse', 'name', 'date_create', 'warehouse_status_id', 'avr_remain_price'
        ]);
    }

    public function handleMainWarehouseChange()
    {
        // If this warehouse is already the main warehouse, prevent unchecking
        if ($this->warehouse && $this->warehouse->main_warehouse) {
            \Log::info("🚫 Attempted to uncheck main warehouse checkbox - prevented");
            // Reset the checkbox to checked state
            $this->main_warehouse = true;
            return;
        }

        // If trying to check main warehouse and it wasn't originally main
        if ($this->main_warehouse && !$this->originalMainWarehouse) {
            \Log::info("🚨 User trying to set main warehouse - checking for existing main warehouse");
            
            // Check if there's already a main warehouse in this branch
            $existingMainWarehouse = Warehouse::where('branch_id', $this->branch_id)
                ->where('main_warehouse', 1)
                ->where('id', '!=', $this->warehouse->id)
                ->first();
            
            if ($existingMainWarehouse) {
                \Log::info("🚨 Found existing main warehouse:", ['name' => $existingMainWarehouse->name]);
                
                // Set global JavaScript variables for the alert
                $this->js("window.currentWarehouseName = '{$this->warehouse->name}';");
                $this->js("window.existingMainWarehouseName = '{$existingMainWarehouse->name}';");
                
                // Dispatch event to show confirmation dialog
                $this->dispatch('confirmMainWarehouse');
                \Log::info("📡 Dispatched confirmMainWarehouse event");
                $this->pendingMainWarehouseChange = true;
                
                // Temporarily uncheck the checkbox until user confirms
                $this->main_warehouse = false;
            } else {
                \Log::info("✅ No existing main warehouse found - allowing change");
            }
        }
    }

    public function confirmMainWarehouseChange()
    {
        \Log::info("✅ confirmMainWarehouseChange called!");
        \Log::info("✅ Before setting - pendingMainWarehouseChange: " . ($this->pendingMainWarehouseChange ? 'true' : 'false'));
        $this->main_warehouse = true;
        $this->pendingMainWarehouseChange = true; // Explicitly set it to true
        \Log::info("✅ After setting - pendingMainWarehouseChange: " . ($this->pendingMainWarehouseChange ? 'true' : 'false'));
        \Log::info("✅ Main warehouse set to true: " . ($this->main_warehouse ? 'true' : 'false'));
        \Log::info("✅ pendingMainWarehouseChange remains true for validation bypass");
    }

    public function handleRefreshComponent()
    {
        \Log::info("🔥 WarehouseDetail::handleRefreshComponent called - NOT refreshing to prevent double calls");
        // Don't call $this->$refresh() to prevent component remounting
        // The component will update naturally when properties change
    }

    public function saveWarehouse()
    {
        $this->validate();

        try {
            // Create the warehouse directly
            $warehouse = Warehouse::create([
                'branch_id' => $this->branch_id,
                'user_create_id' => $this->user_create_id,
                'main_warehouse' => $this->main_warehouse ?? false,
                'name' => $this->name,
                'date_create' => $this->date_create,
                'warehouse_status_id' => $this->warehouse_status_id,
                'avr_remain_price' => $this->avr_remain_price ?? 0.00,
            ]);

            // Success - show message and refresh
            session()->flash('message', 'Warehouse created successfully!');
            \Log::info("✅ Warehouse created successfully!");
            $this->showAddWarehouseForm = false;
            
            // Dispatch events for success dialog and list update
            $this->dispatch('warehouseCreated', [
                'message' => 'Warehouse created successfully!',
                'warehouse' => $warehouse
            ]);
            $this->dispatch('warehouseListUpdated');
            
            \Log::info("📡 Dispatching warehouseCreated and warehouseListUpdated events");
            
        } catch (\Exception $e) {
            // Handle errors
            $this->addError('general', 'Failed to create warehouse: ' . $e->getMessage());
            \Log::error("❌ Warehouse creation failed: " . $e->getMessage());
        }
    }

    public function updateWarehouse()
    {
        \Log::info("🚀 updateWarehouse method called!");
        
        if (!$this->warehouse) {
            \Log::info("❌ No warehouse selected");
            return;
        }

        \Log::info("🔍 About to validate with rules");
        $this->validate($this->getRules());
        
        try {
            DB::transaction(function () {
                // If this warehouse is being set as the main warehouse,
                // disable the previously main warehouse in the same branch
                if ($this->main_warehouse && !$this->originalMainWarehouse) {
                    Warehouse::where('branch_id', $this->branch_id)
                        ->where('main_warehouse', 1)
                        ->where('id', '!=', $this->warehouse->id)
                        ->update(['main_warehouse' => 0]);
                    \Log::info("✅ Disabled previous main warehouse in branch {$this->branch_id}");
                }

                // Update the warehouse directly
                $this->warehouse->update([
                    'branch_id' => $this->branch_id,
                    'user_create_id' => $this->user_create_id,
                    'main_warehouse' => $this->main_warehouse ?? false,
                    'name' => $this->name,
                    'date_create' => $this->date_create,
                    'warehouse_status_id' => $this->warehouse_status_id,
                    'avr_remain_price' => $this->avr_remain_price ?? 0.00,
                ]);
                \Log::info("✅ Current warehouse updated: {$this->warehouse->name}");
            });

            // Success - show message and refresh
            session()->flash('message', __t('warehouse.warehouse_updated_successfully', 'Warehouse updated successfully!'));
            \Log::info("✅ Warehouse updated successfully!");
            $this->showEditWarehouseForm = false;
            
            // Reset the pending main warehouse change flag after successful update
            $this->pendingMainWarehouseChange = false;
            \Log::info("✅ pendingMainWarehouseChange reset to false after successful update");
        
            // Reload the warehouse data to reflect changes
            $this->loadWarehouse($this->warehouse->id);
            
            // Dispatch events for success dialog and list update
            $this->dispatch('warehouseUpdated', [
                'message' => __t('warehouse.warehouse_updated_successfully', 'Warehouse updated successfully!'),
                'warehouse' => $this->warehouse
            ]);
            $this->dispatch('warehouseListUpdated');
            
            \Log::info("📡 Dispatching warehouseUpdated and warehouseListUpdated events");
            
        } catch (\Exception $e) {
            // Handle errors
            $this->addError('general', __t('warehouse.failed_to_update_warehouse', 'Failed to update warehouse') . ': ' . $e->getMessage());
            \Log::error("❌ Warehouse update failed: " . $e->getMessage());
        }
    }

    public function deleteWarehouse($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            // Set status to Delete (status 0)
            $warehouse->update(['warehouse_status_id' => 0]);
            
            session()->flash('message', 'Warehouse deleted successfully!');
            
            // Reload the warehouse data to reflect changes
            $this->loadWarehouse($warehouseId);
            
            // Dispatch events for success dialog and list update
            $this->dispatch('warehouseDeleted', [
                'message' => 'Warehouse deleted successfully!'
            ]);
            $this->dispatch('warehouseListUpdated');
            
            \Log::info("📡 Dispatching warehouseDeleted and warehouseListUpdated events");
        }
    }

    public function reactivateWarehouse($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            // Set status to Active (assuming status_id 1 is Active)
            $activeStatus = WarehouseStatus::where('name', 'Active')->first();
            if ($activeStatus) {
                $warehouse->update(['warehouse_status_id' => $activeStatus->id]);
            }
            
            session()->flash('message', 'Warehouse reactivated successfully!');
            
            // Reload the warehouse data to reflect changes
            $this->loadWarehouse($warehouseId);
            
            // Dispatch events for success dialog and list update
            $this->dispatch('warehouseReactivated', [
                'message' => 'Warehouse reactivated successfully!'
            ]);
            $this->dispatch('warehouseListUpdated');
            
            \Log::info("📡 Dispatching warehouseReactivated and warehouseListUpdated events");
        }
    }

    public function loadWarehouseInventory()
    {
        if (!$this->warehouse) {
            $this->warehouseInventory = [];
            return;
        }

        $this->warehouseInventory = WarehouseProduct::with(['product'])
            ->where('warehouse_id', $this->warehouse->id)
            ->orderBy('balance', 'desc')
            ->get();
    }

    public function getCalculatedAverageRemainingPrice()
    {
        if (!$this->warehouseInventory || $this->warehouseInventory->isEmpty()) {
            return 0;
        }

        $totalValue = 0;
        $totalQuantity = 0;

        foreach ($this->warehouseInventory as $inventory) {
            if ($inventory->balance > 0) {
                // Use average buy price * balance to get total value for this item
                $itemValue = $inventory->avr_buy_price * $inventory->balance;
                $totalValue += $itemValue;
                $totalQuantity += $inventory->balance;
            }
        }

        // Return weighted average price (total value / total quantity)
        return $totalQuantity > 0 ? $totalValue / $totalQuantity : 0;
    }

    /**
     * Handle stock operation events from the stock operation component
     */
    public function handleStockOperation($operation, $data)
    {
        try {
            $inventoryService = app(InventoryService::class);
            
            switch ($operation) {
                case 'stock_in':
                    $result = $inventoryService->stockIn($data);
                    break;
                case 'stock_out':
                    $result = $inventoryService->stockOut($data);
                    break;
                case 'adjustment':
                    $result = $inventoryService->stockAdjustment($data);
                    break;
                case 'transfer':
                    $result = $inventoryService->transferStock($data);
                    break;
                default:
                    throw new \Exception("Unknown operation: {$operation}");
            }

            // Refresh warehouse data
            $this->loadWarehouse($this->warehouse->id);
            
            // Dispatch success event
            $this->dispatch('stockOperationCompleted', [
                'operation' => $operation,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            $this->dispatch('stockOperationFailed', [
                'operation' => $operation,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function loadWarehouseMovements()
    {
        if (!$this->warehouse) {
            $this->warehouseMovements = [];
            return;
        }

        $movements = [];

        // Load transfer slip movements
        $transferSlips = TransferSlip::with(['transferSlipDetails.product', 'warehouseOrigin', 'warehouseDestination'])
            ->where(function($q) {
                $q->where('warehouse_origin_id', $this->warehouse->id)
                  ->orWhere('warehouse_destination_id', $this->warehouse->id);
            })
            ->orderBy('date_request', 'desc')
            ->get();

        foreach ($transferSlips as $transferSlip) {
            foreach ($transferSlip->transferSlipDetails as $detail) {
                $isIncoming = $transferSlip->warehouse_destination_id == $this->warehouse->id;
                $quantity = $isIncoming ? $detail->quantity : -$detail->quantity;
                
                $movements[] = [
                    'id' => 'ts_' . $transferSlip->id . '_' . $detail->id,
                    'date' => $transferSlip->date_request,
                    'type' => 'Transfer',
                    'type_class' => $isIncoming ? 'success' : 'warning',
                    'product_name' => $detail->product_name,
                    'product_code' => $detail->product ? $detail->product->sku_number : 'N/A',
                    'quantity' => $quantity,
                    'unit' => $detail->unit_name,
                    'reference' => $transferSlip->transfer_slip_number,
                    'warehouse_from' => $transferSlip->warehouseOrigin ? $transferSlip->warehouseOrigin->name : 'N/A',
                    'warehouse_to' => $transferSlip->warehouseDestination ? $transferSlip->warehouseDestination->name : 'N/A',
                    'description' => $transferSlip->description,
                    'status' => $transferSlip->status ? $transferSlip->status->name : 'Unknown'
                ];
            }
        }

        // Load inventory movements
        $inventories = Inventory::with(['product', 'moveType'])
            ->where('warehouse_id', $this->warehouse->id)
            ->orderBy('date_activity', 'desc')
            ->get();

        foreach ($inventories as $inventory) {
            $isIncoming = $inventory->quantity_move > 0;
            $quantity = $inventory->quantity_move;
            
            $movements[] = [
                'id' => 'inv_' . $inventory->id,
                'date' => $inventory->date_activity,
                'type' => $inventory->moveType ? $inventory->moveType->name : 'Inventory',
                'type_class' => $isIncoming ? 'success' : 'danger',
                'product_name' => $inventory->product ? $inventory->product->name : 'N/A',
                'product_code' => $inventory->product ? $inventory->product->sku_number : 'N/A',
                'quantity' => $quantity,
                'unit' => $inventory->unit_name ?: ($inventory->product ? $inventory->product->unit_name : 'N/A'),
                'reference' => $inventory->transfer_slip_id ? 'TS' . $inventory->transfer_slip_id : 'INV' . $inventory->id,
                'warehouse_from' => $this->warehouse->name,
                'warehouse_to' => $this->warehouse->name,
                'description' => $inventory->detail,
                'status' => 'Completed'
            ];
        }

        // Sort by date descending
        usort($movements, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $this->warehouseMovements = $movements;
    }

    public function updatedSelectedBranchId()
    {
        // When user selects a different branch, reload warehouse data for that branch
        if ($this->selectedBranchId && $this->warehouse) {
            // Find warehouses for the selected branch
            $warehouses = Warehouse::where('branch_id', $this->selectedBranchId)->where('warehouse_status_id', '!=', 0)->get();
            
            if ($warehouses->count() > 0) {
                // Load the first warehouse for the selected branch
                $this->loadWarehouse(['warehouseId' => $warehouses->first()->id]);
            } else {
                // No warehouses found for selected branch, clear the data
                $this->warehouse = null;
                $this->warehouseInventory = [];
                $this->warehouseMovements = [];
            }
        }
    }

    /**
     * Open stock adjustment modal (same as Product Detail)
     */
    public function openStockModal($productId, $warehouseId, $warehouseName)
    {
        \Log::info("🚀 [STOCK MODAL] openStockModal called - product: {$productId}, warehouse: {$warehouseId}, name: {$warehouseName}");
        
        // Preserve the current tab state
        $this->activeTab = 'inventory';
        
        $this->selectedProductId = $productId;
        $this->selectedWarehouseId = $warehouseId;
        $this->selectedWarehouseName = $warehouseName;

        $this->selectedProduct = Product::with('type')->find($productId);

        if (!$this->selectedProduct) {
            \Log::error("🚀 [STOCK MODAL] Product not found with ID: {$productId}");
            $this->dispatch('showErrorMessage', message: 'Product not found');
            return;
        }

        $this->currentStock = $this->resolveCurrentStock();
        $this->showProductEditModal = true;
        $this->resetProductEditModalFields();

        \Log::info("🚀 [STOCK MODAL] Dispatching showStockModal event");
        $this->dispatch('showStockModal');
    }

    /**
     * Open transfer form with preselected product and source warehouse
     */
    public function openTransferForm($productId, $warehouseId, $warehouseName)
    {
        \Log::info("🚀 [TRANSFER FORM] openTransferForm called - product: {$productId}, warehouse: {$warehouseId}, name: {$warehouseName}");
        
        // Preserve the current tab state
        $this->activeTab = 'inventory';
        
        $this->selectedProductId = $productId;
        $this->selectedWarehouseId = $warehouseId;
        $this->selectedWarehouseName = $warehouseName;

        $this->selectedProduct = Product::with('type')->find($productId);

        if (!$this->selectedProduct) {
            \Log::error("🚀 [TRANSFER FORM] Product not found with ID: {$productId}");
            $this->dispatch('showErrorMessage', message: 'Product not found');
            return;
        }

        // Store preselection data in session for the transfer page
        session([
            'transfer_preselection' => [
                'productId' => $productId,
                'productName' => $this->selectedProduct->name,
                'productSku' => $this->selectedProduct->sku_number,
                'warehouseId' => $warehouseId,
                'warehouseName' => $warehouseName
            ]
        ]);

        \Log::info("🚀 [TRANSFER FORM] Stored preselection data in session, redirecting to transfer page");
        
        // Redirect to transfer page
        return redirect()->route('menu.menu_warehouse_transfer');
    }

    /**
     * Switch to a specific tab
     */
    public function switchTab($tabName)
    {
        $this->activeTab = $tabName;
        $this->dispatch('tabSwitched', tab: $tabName);
    }

    /**
     * Close product edit modal
     */
    public function closeProductEditModal()
    {
        $this->showProductEditModal = false;
        $this->dispatch('hideStockModal');
        $this->resetProductEditModalFields();
    }

    /**
     * Close stock modal (alias for compatibility)
     */
    public function closeStockModal()
    {
        $this->closeProductEditModal();
    }


    /**
     * Reset product edit modal fields
     */
    public function resetProductEditModalFields()
    {
        $product = $this->selectedProduct;
        $this->operationType = '';
        $this->quantity = 0;
        $this->unitName = $product->unit_name ?? 'pcs';
        $this->unitPrice = $product->buy_price ?? 0;
        $this->salePrice = $product->sale_price ?? 0;
        $this->detail = '';
        $this->resetErrorBag();
    }

    protected function resolveCurrentStock(): float
    {
        if (!$this->selectedProductId) {
            return 0;
        }

        if ($this->selectedWarehouseId > 0) {
            return (float) (WarehouseProduct::where('warehouse_id', $this->selectedWarehouseId)
                ->where('product_id', $this->selectedProductId)
                ->value('balance') ?? 0);
        }

        return (float) WarehouseProduct::where('product_id', $this->selectedProductId)->sum('balance');
    }

    /**
     * Process product stock operation
     */
    public function processProductStockOperation($confirm = false)
    {
        if (!$this->selectedProduct) {
            $this->dispatch('showErrorMessage', message: 'Product not loaded');
            return;
        }

        try {
            $this->validate([
                'operationType' => 'required|in:stock_in,stock_out,adjustment',
                'quantity' => 'required|numeric|min:0.01',
                'unitPrice' => 'nullable|numeric|min:0',
                'salePrice' => 'nullable|numeric|min:0',
                'detail' => 'nullable|string|max:255'
            ]);
        } catch (\Exception $e) {
            \Log::error("🚀 [PRODUCT EDIT MODAL] Validation failed: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Validation failed: ' . $e->getMessage());
            return;
        }

        $currentStock = $this->resolveCurrentStock();
        $unitName = $this->unitName ?: ($this->selectedProduct->unit_name ?? 'pcs');

        if (!$confirm) {
            $newStock = $currentStock;

            switch ($this->operationType) {
                case 'adjustment':
                    $newStock = (float) $this->quantity;
                    break;
                case 'stock_in':
                    $newStock = $currentStock + (float) $this->quantity;
                    break;
                case 'stock_out':
                    $newStock = $currentStock - (float) $this->quantity;
                    break;
            }

            \Log::info("🚀 [PRODUCT EDIT MODAL] Dispatching confirmProductStockOperation event", [
                'operationType' => $this->operationType,
                'currentStock' => $currentStock,
                'newStock' => $newStock,
            ]);

            $this->dispatch(
                'confirmStockOperation',
                operationType: $this->operationType,
                currentStock: $currentStock,
                newStock: $newStock,
                productName: $this->selectedProduct->name,
                productSku: $this->selectedProduct->sku_number,
                productImage: asset('assets/images/default_product.png'),
                warehouseName: $this->selectedWarehouseName,
                operationDate: now()->format('d/m/Y'),
                operationTime: now()->format('H:i:s'),
                documentNumber: 'STK-' . now()->format('YmdHis'),
                unitName: $unitName,
            );

            return;
        }

        if ($this->operationType === 'stock_out' && $this->quantity > $currentStock) {
            $this->addError('quantity', 'Insufficient stock. Available: ' . $currentStock);
            return;
        }

        try {
            $inventoryService = new InventoryService();

            $data = [
                'product_id' => $this->selectedProductId,
                'warehouse_id' => $this->selectedWarehouseId,
                'unit_name' => $unitName,
                'unit_price' => $this->unitPrice,
                'sale_price' => $this->salePrice,
                'detail' => $this->detail ?: ucfirst(str_replace('_', ' ', $this->operationType)),
                'date_activity' => now(),
            ];

            if ($this->operationType === 'adjustment') {
                $data['new_quantity'] = $this->quantity;
            } else {
                $data['quantity'] = $this->quantity;
            }

            $result = null;

            switch ($this->operationType) {
                case 'stock_in':
                    $result = $inventoryService->stockIn($data);
                    break;
                case 'stock_out':
                    $result = $inventoryService->stockOut($data);
                    break;
                case 'adjustment':
                    $result = $inventoryService->stockAdjustment($data);
                    break;
            }

            if ($result && ($result['success'] ?? false)) {
                $this->closeProductEditModal();
                $this->loadWarehouseInventory();
                $this->loadWarehouseMovements();
                $this->dispatch('showSuccessMessage', message: $result['message'] ?? 'Stock updated successfully');
            } else {
                $this->dispatch('showErrorMessage', message: 'Stock operation failed');
            }
        } catch (\Exception $e) {
            \Log::error("🚀 [PRODUCT EDIT MODAL] Stock operation failed: " . $e->getMessage());
            $this->dispatch('showErrorMessage', message: 'Stock operation failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        \Log::info("🎨 WarehouseDetail::render called", [
            'warehouseId' => $this->warehouseId,
            'warehouse_exists' => $this->warehouse ? 'yes' : 'no',
            'warehouse_name' => $this->warehouse ? $this->warehouse->name : 'null'
        ]);

        // Reload warehouse if warehouseId is set (for proper Livewire hydration)
        if ($this->warehouseId) {
            $this->warehouse = Warehouse::with(['branch', 'status', 'userCreate', 'inventories', 'warehouseProducts.product'])
                ->where('warehouse_status_id', '!=', 0)
                ->find($this->warehouseId);
            
            \Log::info("🎨 Warehouse reloaded in render:", ['name' => $this->warehouse ? $this->warehouse->name : 'null']);
            
            // Reload inventory and movement data if warehouse is loaded
            if ($this->warehouse) {
                $this->loadWarehouseInventory();
                $this->loadWarehouseMovements();
            }
        }
        
        return view('livewire.warehouse.warehouse-detail');
    }
}
