<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Http\Controllers\WarehouseController;
use Illuminate\Http\Request;

class WarehouseDetail extends Component
{
    public $warehouse;
    public $showAddWarehouseForm = false;
    public $showEditWarehouseForm = false;
    
    // Warehouse form fields
    public $branch_id, $warehouse_code, $name_th, $name_en, $address_th, $address_en;
    public $phone_number, $email, $is_active, $is_main_warehouse, $description;
    public $contact_name, $contact_email, $contact_mobile;
    
    public $branches = [];

    protected $listeners = [
        'warehouseSelected' => 'loadWarehouse',
        'showAddWarehouseForm' => 'displayAddWarehouseForm',
        'showEditWarehouseForm' => 'displayEditWarehouseForm',
        'refreshComponent' => '$refresh',
        'createWarehouse' => 'createWarehouse',
        'deleteWarehouse' => 'deleteWarehouse',
        'reactivateWarehouse' => 'reactivateWarehouse',
        'cancelForm' => 'cancelForm'
    ];

    protected $rules = [
        'branch_id' => 'required|exists:branches,id',
        'warehouse_code' => 'required|string|max:50',
        'name_th' => 'required|string|max:255',
        'name_en' => 'nullable|string|max:255',
        'address_th' => 'nullable|string',
        'address_en' => 'nullable|string',
        'phone_number' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:100',
        'is_active' => 'boolean',
        'is_main_warehouse' => 'boolean',
        'description' => 'nullable|string',
        'contact_name' => 'nullable|string|max:100',
        'contact_email' => 'nullable|email|max:100',
        'contact_mobile' => 'nullable|string|max:20',
    ];

    protected $messages = [
        'branch_id.required' => 'Please select a branch.',
        'branch_id.exists' => 'The selected branch does not exist.',
        'warehouse_code.required' => 'Warehouse code is required.',
        'warehouse_code.max' => 'Warehouse code must not exceed 50 characters.',
        'name_th.required' => 'Thai name is required.',
        'name_th.max' => 'Thai name must not exceed 255 characters.',
        'name_en.max' => 'English name must not exceed 255 characters.',
        'phone_number.max' => 'Phone number must not exceed 20 characters.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'Email must not exceed 100 characters.',
        'contact_name.max' => 'Contact name must not exceed 100 characters.',
        'contact_email.email' => 'Please enter a valid contact email address.',
        'contact_email.max' => 'Contact email must not exceed 100 characters.',
        'contact_mobile.max' => 'Contact mobile must not exceed 20 characters.',
    ];

    public function mount()
    {
        \Log::info("WarehouseDetail mounted");
        $this->branches = Branch::all();
        \Log::info("Branches loaded: " . $this->branches->count());
        
        // Check if there's a previously selected warehouse
        $selectedWarehouseId = session('selected_warehouse_id');
        if ($selectedWarehouseId) {
            \Log::info("Restoring warehouse from session: {$selectedWarehouseId}");
            $this->warehouse = Warehouse::with(['branch'])->find($selectedWarehouseId);
            if ($this->warehouse) {
                \Log::info("Warehouse restored: " . $this->warehouse->name_th);
                // Populate form fields
                $this->branch_id = $this->warehouse->branch_id;
                $this->warehouse_code = $this->warehouse->warehouse_code;
                $this->name_th = $this->warehouse->name_th;
                $this->name_en = $this->warehouse->name_en;
                $this->address_th = $this->warehouse->address_th;
                $this->address_en = $this->warehouse->address_en;
                $this->phone_number = $this->warehouse->phone_number;
                $this->email = $this->warehouse->email;
                $this->is_active = $this->warehouse->is_active;
                $this->is_main_warehouse = $this->warehouse->is_main_warehouse;
                $this->description = $this->warehouse->description;
                $this->contact_name = $this->warehouse->contact_name;
                $this->contact_email = $this->warehouse->contact_email;
                $this->contact_mobile = $this->warehouse->contact_mobile;
            }
        }
    }

    public function loadWarehouse($data)
    {
        \Log::info("loadWarehouse called with: " . json_encode($data));
        
        // Handle different data formats
        if (is_numeric($data)) {
            // If it's just an ID number
            $warehouseId = $data;
        } elseif (isset($data['warehouse'])) {
            // If it's the new event format with warehouse object
            $warehouse = $data['warehouse'];
            $warehouseId = is_array($warehouse) ? $warehouse['id'] : $warehouse->id;
        } else {
            // If it's the old format or direct warehouse object
            $warehouse = $data;
            $warehouseId = is_array($warehouse) ? $warehouse['id'] : $warehouse->id;
        }
        
        \Log::info("loadWarehouse: {$warehouseId}");
        $this->showEditWarehouseForm = false;
        $this->warehouse = Warehouse::with(['branch'])->find($warehouseId) ?? null;
        \Log::info("Warehouse loaded: " . ($this->warehouse ? $this->warehouse->name_th : 'null'));
        
        // Store in session to persist across component remounts
        if ($this->warehouse) {
            session(['selected_warehouse_id' => $warehouseId]);
        }
        
        if ($this->warehouse) {
            // Populate form fields
            $this->branch_id = $this->warehouse->branch_id;
            $this->warehouse_code = $this->warehouse->warehouse_code;
            $this->name_th = $this->warehouse->name_th;
            $this->name_en = $this->warehouse->name_en;
            $this->address_th = $this->warehouse->address_th;
            $this->address_en = $this->warehouse->address_en;
            $this->phone_number = $this->warehouse->phone_number;
            $this->email = $this->warehouse->email;
            $this->is_active = $this->warehouse->is_active;
            $this->is_main_warehouse = $this->warehouse->is_main_warehouse;
            $this->description = $this->warehouse->description;
            $this->contact_name = $this->warehouse->contact_name;
            $this->contact_email = $this->warehouse->contact_email;
            $this->contact_mobile = $this->warehouse->contact_mobile;
        }
        
        $this->showAddWarehouseForm = false;
    }

    public function displayAddWarehouseForm()
    {
        $this->showAddWarehouseForm = true;
        $this->showEditWarehouseForm = false;
        $this->resetErrorBag();
        $this->reset([
            'branch_id', 'warehouse_code', 'name_th', 'name_en', 'address_th', 'address_en',
            'phone_number', 'email', 'is_active', 'is_main_warehouse', 'description',
            'contact_name', 'contact_email', 'contact_mobile'
        ]);
    }

    public function displayEditWarehouseForm()
    {
        if ($this->warehouse) {
            $this->showEditWarehouseForm = true;
            $this->showAddWarehouseForm = false;
            $this->resetErrorBag();
            
            // Populate form fields with current warehouse data
            $this->branch_id = $this->warehouse->branch_id;
            $this->warehouse_code = $this->warehouse->warehouse_code;
            $this->name_th = $this->warehouse->name_th;
            $this->name_en = $this->warehouse->name_en;
            $this->address_th = $this->warehouse->address_th;
            $this->address_en = $this->warehouse->address_en;
            $this->phone_number = $this->warehouse->phone_number;
            $this->email = $this->warehouse->email;
            $this->is_active = $this->warehouse->is_active;
            $this->is_main_warehouse = $this->warehouse->is_main_warehouse;
            $this->description = $this->warehouse->description;
            $this->contact_name = $this->warehouse->contact_name;
            $this->contact_email = $this->warehouse->contact_email;
            $this->contact_mobile = $this->warehouse->contact_mobile;
        }
    }

    public function cancelForm()
    {
        $this->showAddWarehouseForm = false;
        $this->showEditWarehouseForm = false;
        $this->resetErrorBag();
        $this->reset([
            'branch_id', 'warehouse_code', 'name_th', 'name_en', 'address_th', 'address_en',
            'phone_number', 'email', 'is_active', 'is_main_warehouse', 'description',
            'contact_name', 'contact_email', 'contact_mobile'
        ]);
    }

    public function saveWarehouse()
    {
        $this->validate();

        try {
            // Create the warehouse directly
            $warehouse = Warehouse::create([
                'branch_id' => $this->branch_id,
                'warehouse_code' => $this->warehouse_code,
                'name_th' => $this->name_th,
                'name_en' => $this->name_en,
                'address_th' => $this->address_th,
                'address_en' => $this->address_en,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'is_active' => $this->is_active ?? false,
                'is_main_warehouse' => $this->is_main_warehouse ?? false,
                'description' => $this->description,
                'contact_name' => $this->contact_name,
                'contact_email' => $this->contact_email,
                'contact_mobile' => $this->contact_mobile,
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
            $this->dispatch('refreshComponent');
            
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
                'warehouse_code' => $this->warehouse_code,
                'name_th' => $this->name_th,
                'name_en' => $this->name_en,
                'address_th' => $this->address_th,
                'address_en' => $this->address_en,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'is_active' => $this->is_active ?? false,
                'is_main_warehouse' => $this->is_main_warehouse ?? false,
                'description' => $this->description,
                'contact_name' => $this->contact_name,
                'contact_email' => $this->contact_email,
                'contact_mobile' => $this->contact_mobile,
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
            $this->dispatch('refreshComponent');
            
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
            $controller = new WarehouseController();
            $response = $controller->destroy($warehouse);
            
            if ($response->getData()->success) {
                session()->flash('message', 'Warehouse deactivated successfully!');
                $this->warehouse = null;
                
                // Clear the selected warehouse from session
                session()->forget('selected_warehouse_id');
                
                // Dispatch events for success dialog and list update
                $this->dispatch('warehouseDeleted', [
                    'message' => 'Warehouse deactivated successfully!'
                ]);
                $this->dispatch('warehouseListUpdated');
                $this->dispatch('refreshComponent');
                
                \Log::info("📡 Dispatching warehouseDeleted and warehouseListUpdated events");
            } else {
                $this->addError('general', 'Failed to deactivate warehouse. Please try again.');
            }
        }
    }

    public function reactivateWarehouse($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        if ($warehouse) {
            $warehouse->update(['is_active' => true]);
            
            session()->flash('message', 'Warehouse reactivated successfully!');
            
            // Reload the warehouse data to reflect changes
            $this->loadWarehouse($warehouseId);
            
            // Dispatch events for success dialog and list update
            $this->dispatch('warehouseReactivated', [
                'message' => 'Warehouse reactivated successfully!'
            ]);
            $this->dispatch('warehouseListUpdated');
            $this->dispatch('refreshComponent');
            
            \Log::info("📡 Dispatching warehouseReactivated and warehouseListUpdated events");
        }
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-detail');
    }
}
