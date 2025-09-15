<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\WarehouseStatus;
use App\Http\Controllers\WarehouseController;
use Illuminate\Http\Request;

class WarehouseDetail extends Component
{
    public $warehouse;
    public $showAddWarehouseForm = false;
    public $showEditWarehouseForm = false;
    
    // Warehouse form fields (matching ER diagram)
    public $branch_id, $user_create_id, $main_warehouse, $name, $date_create, $warehouse_status_id, $avr_remain_price;
    
    public $branches = [];
    public $warehouse_statuses = [];

    protected $listeners = [
        'warehouseSelected' => 'loadWarehouse',
        'showAddWarehouseForm' => 'displayAddWarehouseForm',
        'showEditWarehouseForm' => 'displayEditWarehouseForm',
        'refreshComponent' => 'handleRefreshComponent',
        'createWarehouse' => 'createWarehouse',
        'deleteWarehouse' => 'deleteWarehouse',
        'reactivateWarehouse' => 'reactivateWarehouse',
        'cancelForm' => 'cancelForm'
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
            'cancelForm' => 'cancelForm'
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
        
        // Check if there's a previously selected warehouse
        $selectedWarehouseId = session('selected_warehouse_id');
        if ($selectedWarehouseId) {
            \Log::info("🔥 Restoring warehouse from session: {$selectedWarehouseId}");
            $this->warehouse = Warehouse::with(['branch', 'status', 'userCreate'])->find($selectedWarehouseId);
            if ($this->warehouse) {
                \Log::info("🔥 Warehouse restored:", ['name' => $this->warehouse->name]);
                // Populate form fields
                $this->branch_id = $this->warehouse->branch_id;
                $this->user_create_id = $this->warehouse->user_create_id;
                $this->main_warehouse = $this->warehouse->main_warehouse;
                $this->name = $this->warehouse->name;
                $this->date_create = $this->warehouse->date_create->format('Y-m-d\TH:i');
                $this->warehouse_status_id = $this->warehouse->warehouse_status_id;
                $this->avr_remain_price = $this->warehouse->avr_remain_price;
            }
        }
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
        
        $this->showEditWarehouseForm = false;
        $this->showAddWarehouseForm = false;
        
        // Load warehouse with relationships (matching branch pattern)
        $this->warehouse = Warehouse::with(['branch', 'status', 'userCreate', 'inventories'])->find($warehouseId) ?? null;
        \Log::info("🔥 Warehouse loaded:", ['name' => $this->warehouse ? $this->warehouse->name : 'null']);
        
        // Store in session to persist across component remounts
        if ($this->warehouse) {
            session(['selected_warehouse_id' => $this->warehouse->id]);
            \Log::info("🔥 Stored warehouse ID in session: {$this->warehouse->id}");
            
            // Populate form fields
            $this->branch_id = $this->warehouse->branch_id;
            $this->user_create_id = $this->warehouse->user_create_id;
            $this->main_warehouse = $this->warehouse->main_warehouse;
            $this->name = $this->warehouse->name;
            $this->date_create = $this->warehouse->date_create->format('Y-m-d\TH:i');
            $this->warehouse_status_id = $this->warehouse->warehouse_status_id;
            $this->avr_remain_price = $this->warehouse->avr_remain_price;
            \Log::info("🔥 Form fields populated successfully");
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
        
        // Set default values
        $this->user_create_id = auth()->id() ?? 1;
        $this->date_create = now()->format('Y-m-d\TH:i');
        $this->main_warehouse = false;
        $this->avr_remain_price = 0.00;
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
        if (!$this->warehouse) {
            return;
        }

        $this->validate();

        try {
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

            // Success - show message and refresh
            session()->flash('message', 'Warehouse updated successfully!');
            \Log::info("✅ Warehouse updated successfully!");
            $this->showEditWarehouseForm = false;
            
            // Reload the warehouse data to reflect changes
            $this->loadWarehouse($this->warehouse->id);
            
            // Dispatch events for success dialog and list update
            $this->dispatch('warehouseUpdated', [
                'message' => 'Warehouse updated successfully!',
                'warehouse' => $this->warehouse
            ]);
            $this->dispatch('warehouseListUpdated');
            
            \Log::info("📡 Dispatching warehouseUpdated and warehouseListUpdated events");
            
        } catch (\Exception $e) {
            // Handle errors
            $this->addError('general', 'Failed to update warehouse: ' . $e->getMessage());
            \Log::error("❌ Warehouse update failed: " . $e->getMessage());
        }
    }

    public function deleteWarehouse($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            // Set status to Inactive (assuming status_id 2 is Inactive)
            $inactiveStatus = WarehouseStatus::where('name', 'Inactive')->first();
            if ($inactiveStatus) {
                $warehouse->update(['warehouse_status_id' => $inactiveStatus->id]);
                
                session()->flash('message', 'Warehouse deactivated successfully!');
                
                // Reload the warehouse data to reflect changes
                $this->loadWarehouse($warehouseId);
                
                // Dispatch events for success dialog and list update
                $this->dispatch('warehouseDeleted', [
                    'message' => 'Warehouse deactivated successfully!'
                ]);
                $this->dispatch('warehouseListUpdated');
                
                \Log::info("📡 Dispatching warehouseDeleted and warehouseListUpdated events");
            } else {
                $this->addError('general', 'Failed to deactivate warehouse. Inactive status not found.');
            }
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

    public function render()
    {
        return view('livewire.warehouse.warehouse-detail');
    }
}
