<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Warehouse;
use App\Http\Controllers\BranchController;
use Illuminate\Http\Request;

class BranchDetail extends Component
{
    public $branch;
    public $showAddBranchForm = false;
    public $showEditBranchForm = false;
    
    // Branch form fields
    public $company_id, $branch_code, $name_th, $name_en, $address_th, $address_en;
    public $bill_address_th, $bill_address_en, $post_code, $phone_country_code, $phone_number;
    public $fax, $website, $email, $is_active, $is_head_office, $latitude, $longitude;
    public $contact_name, $contact_email, $contact_mobile;
    
    public $companies = [];
    public $warehouses = [];

    protected $listeners = [
        'BranchSelected' => 'loadBranch',
        'showAddBranchForm' => 'displayAddBranchForm',
        'showEditBranchForm' => 'displayEditBranchForm',
        'refreshComponent' => '$refresh',
        'createBranch' => 'createBranch',
        'deleteBranch' => 'deleteBranch',
        'cancelForm' => 'cancelForm'
    ];

    protected $rules = [
        'company_id' => 'required|exists:companies,id',
        'branch_code' => 'required|string|max:50',
        'name_th' => 'required|string|max:255',
        'name_en' => 'nullable|string|max:255',
        'address_th' => 'nullable|string',
        'address_en' => 'nullable|string',
        'bill_address_th' => 'nullable|string',
        'bill_address_en' => 'nullable|string',
        'post_code' => 'nullable|string|max:10',
        'phone_country_code' => 'nullable|string|max:10',
        'phone_number' => 'nullable|string|max:20',
        'fax' => 'nullable|string|max:20',
        'website' => 'nullable|url|max:255',
        'email' => 'nullable|email|max:100',
        'is_active' => 'boolean',
        'is_head_office' => 'boolean',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'contact_name' => 'nullable|string|max:100',
        'contact_email' => 'nullable|email|max:100',
        'contact_mobile' => 'nullable|string|max:20',
    ];

    public function mount()
    {
        \Log::info("BranchDetail Component Mounted");
        $this->companies = Company::all();
    }

    public function loadBranch($branchId)
    {
        \Log::info("loadBranch: {$branchId}");
        $this->showEditBranchForm = false;
        $this->branch = Branch::with(['company', 'warehouses'])->find($branchId) ?? null;
        
        if ($this->branch) {
            // Load warehouses for this branch
            $this->warehouses = $this->branch->warehouses;
            
            // Populate form fields
            $this->company_id = $this->branch->company_id;
            $this->branch_code = $this->branch->branch_code;
            $this->name_th = $this->branch->name_th;
            $this->name_en = $this->branch->name_en;
            $this->address_th = $this->branch->address_th;
            $this->address_en = $this->branch->address_en;
            $this->bill_address_th = $this->branch->bill_address_th;
            $this->bill_address_en = $this->branch->bill_address_en;
            $this->post_code = $this->branch->post_code;
            $this->phone_country_code = $this->branch->phone_country_code;
            $this->phone_number = $this->branch->phone_number;
            $this->fax = $this->branch->fax;
            $this->website = $this->branch->website;
            $this->email = $this->branch->email;
            $this->is_active = $this->branch->is_active;
            $this->is_head_office = $this->branch->is_head_office;
            $this->latitude = $this->branch->latitude;
            $this->longitude = $this->branch->longitude;
            $this->contact_name = $this->branch->contact_name;
            $this->contact_email = $this->branch->contact_email;
            $this->contact_mobile = $this->branch->contact_mobile;
        }
        
        $this->showAddBranchForm = false;
    }

    public function displayAddBranchForm()
    {
        \Log::info("Livewire Event Received: showAddBranchForm");
        $this->showAddBranchForm = true;
        $this->reset([
            'company_id', 'branch_code', 'name_th', 'name_en', 'address_th', 'address_en',
            'bill_address_th', 'bill_address_en', 'post_code', 'phone_country_code', 'phone_number',
            'fax', 'website', 'email', 'is_active', 'is_head_office', 'latitude', 'longitude',
            'contact_name', 'contact_email', 'contact_mobile'
        ]);
        $this->resetErrorBag();
        $this->branch = null;
        $this->warehouses = [];
        $this->is_active = true;
        $this->is_head_office = false;
        $this->dispatch('addBranch');
    }

    public function displayEditBranchForm()
    {
        \Log::info("Livewire Event Received: showEditBranchForm");
        $this->showEditBranchForm = true;
        
        // Populate form fields with current branch data
        if ($this->branch) {
            $this->company_id = $this->branch->company_id;
            $this->branch_code = $this->branch->branch_code;
            $this->name_th = $this->branch->name_th;
            $this->name_en = $this->branch->name_en;
            $this->address_th = $this->branch->address_th;
            $this->address_en = $this->branch->address_en;
            $this->bill_address_th = $this->branch->bill_address_th;
            $this->bill_address_en = $this->branch->bill_address_en;
            $this->post_code = $this->branch->post_code;
            $this->phone_country_code = $this->branch->phone_country_code;
            $this->phone_number = $this->branch->phone_number;
            $this->fax = $this->branch->fax;
            $this->website = $this->branch->website;
            $this->email = $this->branch->email;
            $this->is_active = $this->branch->is_active;
            $this->is_head_office = $this->branch->is_head_office;
            $this->latitude = $this->branch->latitude;
            $this->longitude = $this->branch->longitude;
            $this->contact_name = $this->branch->contact_name;
            $this->contact_email = $this->branch->contact_email;
            $this->contact_mobile = $this->branch->contact_mobile;
        }
        
        //$this->dispatch('branchUpdated');
    }

    public function cancelForm()
    {
        $this->showAddBranchForm = false;
        $this->showEditBranchForm = false;
        $this->resetErrorBag();
        $this->reset([
            'company_id', 'branch_code', 'name_th', 'name_en', 'address_th', 'address_en',
            'bill_address_th', 'bill_address_en', 'post_code', 'phone_country_code', 'phone_number',
            'fax', 'website', 'email', 'is_active', 'is_head_office', 'latitude', 'longitude',
            'contact_name', 'contact_email', 'contact_mobile'
        ]);
    }

    public function saveBranch()
    {
        $this->validate();

        try {
            // Create the branch directly
            $branch = Branch::create([
                'company_id' => $this->company_id,
                'branch_code' => $this->branch_code,
                'name_th' => $this->name_th,
                'name_en' => $this->name_en,
                'address_th' => $this->address_th,
                'address_en' => $this->address_en,
                'bill_address_th' => $this->bill_address_th,
                'bill_address_en' => $this->bill_address_en,
                'post_code' => $this->post_code,
                'phone_country_code' => $this->phone_country_code,
                'phone_number' => $this->phone_number,
                'fax' => $this->fax,
                'website' => $this->website,
                'email' => $this->email,
                'is_active' => $this->is_active ?? false,
                'is_head_office' => $this->is_head_office ?? false,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'contact_name' => $this->contact_name,
                'contact_email' => $this->contact_email,
                'contact_mobile' => $this->contact_mobile,
            ]);

            // Success - show message and refresh
            session()->flash('message', 'Branch created successfully!');
            \Log::info("✅ Branch created successfully!");
            $this->showAddBranchForm = false;
            $this->dispatch('branchCreated');
            $this->dispatch('refreshComponent');
            
            
        } catch (\Exception $e) {
            // Handle errors
            $this->addError('general', 'Failed to create branch: ' . $e->getMessage());
            \Log::error("❌ Branch creation failed: " . $e->getMessage());
        }
    }

    public function updateBranch()
    {
        if (!$this->branch) {
            return;
        }

        $this->validate();

        try {
            // Update the branch directly
            $this->branch->update([
                'company_id' => $this->company_id,
                'branch_code' => $this->branch_code,
                'name_th' => $this->name_th,
                'name_en' => $this->name_en,
                'address_th' => $this->address_th,
                'address_en' => $this->address_en,
                'bill_address_th' => $this->bill_address_th,
                'bill_address_en' => $this->bill_address_en,
                'post_code' => $this->post_code,
                'phone_country_code' => $this->phone_country_code,
                'phone_number' => $this->phone_number,
                'fax' => $this->fax,
                'website' => $this->website,
                'email' => $this->email,
                'is_active' => $this->is_active ?? false,
                'is_head_office' => $this->is_head_office ?? false,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'contact_name' => $this->contact_name,
                'contact_email' => $this->contact_email,
                'contact_mobile' => $this->contact_mobile,
            ]);

            // Success - show message and refresh
            session()->flash('message', 'Branch updated successfully!');
            \Log::info("✅ Branch updated successfully!");
            $this->showEditBranchForm = false;
            
            // Reload the branch data to reflect changes
            $this->loadBranch($this->branch->id);
            
            $this->dispatch('branchUpdated');
            \Log::info("📡 Dispatching refreshComponent event");
            
            // Refresh the branch list component
            $this->dispatch('refreshComponent');
            
        } catch (\Exception $e) {
            // Handle errors
            $this->addError('general', 'Failed to update branch: ' . $e->getMessage());
            \Log::error("❌ Branch update failed: " . $e->getMessage());
        }
    }

    public function deleteBranch($branchId)
    {
        $branch = Branch::find($branchId);
        if ($branch) {
            $controller = new BranchController();
            $response = $controller->destroy($branch);
            
            if ($response->getData()->success) {
                session()->flash('message', 'Branch deleted successfully!');
                $this->branch = null;
                $this->warehouses = [];
                $this->dispatch('branchListUpdated');
            } else {
                $this->addError('general', 'Failed to delete branch. Please try again.');
            }
        }
    }

    public function render()
    {
        return view('livewire.branch.branch-detail');
    }
}
