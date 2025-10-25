<?php

namespace App\Livewire\Branch;

use Livewire\Component;
use App\Livewire\Branch\BranchList;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Warehouse;
use App\Http\Controllers\BranchController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchDetail extends Component
{
    public $branch;
    public $showAddBranchForm = false;
    public $showEditBranchForm = false;
    
    // Branch form fields
    public $company_id, $branch_code, $name_th, $name_en, $address_th, $address_en;
    public $bill_address_th, $bill_address_en, $post_code, $phone_country_code, $phone_number;
    public $fax, $website, $email, $is_head_office, $branch_status_id, $latitude, $longitude;
    public $contact_name, $contact_email, $contact_mobile;
    public $candidateBranchCode;
    
    public $companies = [];
    public $companyNameTh;
    public $warehouses = [];
    
    // Warehouse form properties
    public $showAddWarehouseForm = false;
    public $warehouseName = '';

    protected $listeners = [
        'BranchSelected' => 'loadBranch',
        'showAddBranchForm' => 'displayAddBranchForm',
        'showEditBranchForm' => 'displayEditBranchForm',
        'showAddWarehouseForm' => 'displayAddWarehouseForm',
        'refreshComponent' => '$refresh',
        'createBranch' => 'createBranch',
        'deleteBranch' => 'deleteBranch',
        'cancelForm' => 'cancelForm',
        'cancelWarehouseForm' => 'cancelWarehouseForm'
    ];

    protected function rules()
    {
        $rules = [
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
            'is_head_office' => 'boolean',
            'branch_status_id' => 'required|integer|in:1,2',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'contact_name' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:100',
            'contact_mobile' => 'nullable|string|max:20',
            'warehouseName' => 'required|string|max:255',
        ];

        // Add unique constraints for create operation
        if ($this->showAddBranchForm) {
            $rules['branch_code'] .= '|unique:branches,branch_code';
            $rules['email'] .= '|unique:branches,email';
            $rules['contact_email'] .= '|unique:branches,contact_email';
        }
        // Add unique constraints for update operation (excluding current record)
        elseif ($this->showEditBranchForm && $this->branch) {
            $rules['branch_code'] .= '|unique:branches,branch_code,' . $this->branch->id;
            $rules['email'] .= '|unique:branches,email,' . $this->branch->id;
            $rules['contact_email'] .= '|unique:branches,contact_email,' . $this->branch->id;
        }

        return $rules;
    }

    public function mount()
    {
        \Log::info("BranchDetail Component Mounted");
        $this->companies = Company::all();
        // Force company context to ID=1 for new branch creation UI defaults
        $defaultCompany = Company::find(1);
        if ($defaultCompany) {
            $this->company_id = 1;
            $this->companyNameTh = $defaultCompany->company_name_th;
        }
    }

    public function loadBranch($branchId)
    {
        \Log::info("loadBranch: {$branchId}");
        $this->showEditBranchForm = false;
        $this->branch = Branch::with(['company', 'warehouses.status'])->where('branch_status_id', '!=', 0)->find($branchId) ?? null;
        
        if ($this->branch) {
            // Load warehouses for this branch
            $this->warehouses = $this->branch->warehouses;
            
            // Populate form fields
            $this->company_id = $this->branch->company_id;
            // Reflect selected company's Thai name when viewing/editing
            $company = Company::find($this->company_id);
            $this->companyNameTh = $company?->company_name_th;
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
            $this->is_head_office = $this->branch->is_head_office;
            $this->branch_status_id = $this->branch->branch_status_id;
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
            'fax', 'website', 'email', 'is_head_office', 'branch_status_id', 'latitude', 'longitude',
            'contact_name', 'contact_email', 'contact_mobile', 'candidateBranchCode', 'companyNameTh'
        ]);
        $this->resetErrorBag();
        $this->branch = null;
        $this->warehouses = [];
        $this->branch_status_id = 1;
        $this->is_head_office = false;
        
        // Force company to ID=1 and set display name
        $this->company_id = 1;
        $company = Company::find(1);
        $this->companyNameTh = $company?->company_name_th;

        // Generate initial candidate branch code for forced company_id=1 and prefill input (editable)
        $this->candidateBranchCode = Branch::generateBranchCode($this->company_id);
        if (empty($this->branch_code)) {
            $this->branch_code = $this->candidateBranchCode;
        }
        \Log::debug('Generated initial candidate branch code', [
            'candidate_branch_code' => $this->candidateBranchCode
        ]);
        
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
            $this->is_head_office = $this->branch->is_head_office;
            $this->branch_status_id = $this->branch->branch_status_id;
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
            'fax', 'website', 'email', 'is_head_office', 'branch_status_id', 'latitude', 'longitude',
            'contact_name', 'contact_email', 'contact_mobile', 'candidateBranchCode'
        ]);
    }

    public function displayAddWarehouseForm()
    {
        \Log::info("Livewire Event Received: showAddWarehouseForm");
        $this->showAddWarehouseForm = true;
        $this->warehouseName = '';
        $this->resetErrorBag();
    }

    public function cancelWarehouseForm()
    {
        $this->showAddWarehouseForm = false;
        $this->warehouseName = '';
        $this->resetErrorBag();
    }

    public function createWarehouse()
    {
        if (!$this->branch) {
            $this->addError('general', 'No branch selected. Please select a branch first.');
            return;
        }

        $this->validate(['warehouseName' => 'required|string|max:255']);

        try {
            $warehouse = Warehouse::create([
                'branch_id' => $this->branch->id,
                'name' => $this->warehouseName,
                'warehouse_status_id' => 1, // Active
                'main_warehouse' => false,
                'user_create_id' => auth()->id(),
                'date_create' => now(),
                'avr_remain_price' => 0.00,
            ]);

            // Refresh warehouses collection
            $this->warehouses = $this->branch->fresh()->warehouses;

            // Success - show message and close form
            session()->flash('message', __t('warehouse.warehouse_created_successfully', 'Warehouse created successfully!'));
            \Log::info("✅ Warehouse created successfully!");
            
            $this->showAddWarehouseForm = false;
            $this->warehouseName = '';
            
            // Dispatch event to refresh datatable
            $this->dispatch('warehouseCreated');
            
        } catch (\Exception $e) {
            $this->addError('general', 'Failed to create warehouse: ' . $e->getMessage());
            \Log::error("❌ Warehouse creation failed: " . $e->getMessage());
        }
    }

    /**
     * Called when company_id is updated - regenerate candidate branch code
     */
    public function updatedCompanyId()
    {
        if ($this->company_id) {
            $this->candidateBranchCode = Branch::generateBranchCode($this->company_id);
            if ($this->showAddBranchForm && empty($this->branch_code)) {
                $this->branch_code = $this->candidateBranchCode;
            }
            \Log::debug('Generated candidate branch code for selected company', [
                'company_id' => $this->company_id,
                'candidate_branch_code' => $this->candidateBranchCode
            ]);
        } else {
            // If no company selected, generate with default company_id = 1
            $this->candidateBranchCode = Branch::generateBranchCode(1);
            if ($this->showAddBranchForm && empty($this->branch_code)) {
                $this->branch_code = $this->candidateBranchCode;
            }
            \Log::debug('Generated candidate branch code with default company', [
                'candidate_branch_code' => $this->candidateBranchCode
            ]);
        }
    }

    /**
     * Handle database constraint violations and map them to specific field errors
     */
    private function handleDatabaseError(QueryException $e)
    {
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();

        // Handle duplicate entry errors
        if ($errorCode === '23000') { // Integrity constraint violation
            if (strpos($errorMessage, 'branches_company_id_branch_code_unique') !== false
                || strpos($errorMessage, 'branches_branch_code_unique') !== false) {
                $this->addError('branch_code', 'This branch code already exists.');
            } elseif (strpos($errorMessage, 'branches_email_unique') !== false) {
                $this->addError('email', 'This email address is already in use.');
            } elseif (strpos($errorMessage, 'branches_contact_email_unique') !== false) {
                $this->addError('contact_email', 'This contact email address is already in use.');
            } else {
                // Generic duplicate error
                $this->addError('general', 'A duplicate entry was detected. Please check your input and try again.');
            }
        } elseif ($errorCode === '22001') { // Data too long
            if (strpos($errorMessage, 'branch_code') !== false) {
                $this->addError('branch_code', 'Branch code is too long.');
            } elseif (strpos($errorMessage, 'name_th') !== false) {
                $this->addError('name_th', 'Branch name (Thai) is too long.');
            } elseif (strpos($errorMessage, 'name_en') !== false) {
                $this->addError('name_en', 'Branch name (English) is too long.');
            } elseif (strpos($errorMessage, 'email') !== false) {
                $this->addError('email', 'Email address is too long.');
            } elseif (strpos($errorMessage, 'contact_email') !== false) {
                $this->addError('contact_email', 'Contact email address is too long.');
            } else {
                $this->addError('general', 'One or more fields contain data that is too long.');
            }
        } elseif ($errorCode === '22003') { // Numeric value out of range
            if (strpos($errorMessage, 'latitude') !== false) {
                $this->addError('latitude', 'Latitude value is out of valid range.');
            } elseif (strpos($errorMessage, 'longitude') !== false) {
                $this->addError('longitude', 'Longitude value is out of valid range.');
            } else {
                $this->addError('general', 'One or more numeric values are out of valid range.');
            }
        } else {
            // Generic database error
            $this->addError('general', 'Database error occurred. Please try again.');
        }
    }

    public function saveBranch()
    {
        \Log::info('Branch validation rules:', $this->rules());
        \Log::info('Branch form data:', [
            'company_id' => $this->company_id,
            'branch_code' => $this->branch_code,
            'showAddBranchForm' => $this->showAddBranchForm
        ]);
        
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
                'is_head_office' => $this->is_head_office ?? false,
                'branch_status_id' => $this->branch_status_id ?? 1, // Default to Active status
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
            $this->dispatch('branchListRefreshRequested')->to(BranchList::class);
        } catch (QueryException $e) {
            $fullSql = $this->formatSqlFromException($e);
            $errorMessage = sprintf(
                'Failed to create branch. Executed SQL: %s | Database message: %s',
                $fullSql,
                $e->getMessage()
            );

            $this->addError('general', $errorMessage);
            \Log::error('❌ Branch creation failed (QueryException): ' . $e->getMessage(), [
                'sql' => $fullSql,
            ]);
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
                'branch_status_id' => $this->branch_status_id ?? ($this->branch->branch_status_id ?? 1),
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
            $this->dispatch('branchListRefreshRequested')->to(BranchList::class);
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            $this->handleDatabaseError($e);
            $this->addError('general', 'Failed to update branch: ' . $e->getMessage());
            \Log::error("❌ Branch update failed (Database Error): " . $e->getMessage());
        } catch (\Exception $e) {
            // Handle other errors
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
                $this->dispatch('branchListRefreshRequested')->to(BranchList::class);
            } else {
                // Display the actual error message from the controller
                $errorMessage = $response->getData()->message ?? 'Failed to delete branch. Please try again.';
                $this->addError('general', $errorMessage);
            }
        }
    }

    private function formatSqlFromException(QueryException $exception): string
    {
        $bindings = array_map(function ($binding) {
            if ($binding === null) {
                return 'NULL';
            }

            if ($binding instanceof \DateTimeInterface) {
                return "'" . $binding->format('Y-m-d H:i:s') . "'";
            }

            if (is_bool($binding)) {
                return $binding ? '1' : '0';
            }

            if (is_numeric($binding)) {
                return (string) $binding;
            }

            return "'" . addslashes((string) $binding) . "'";
        }, $exception->getBindings());

        try {
            return Str::replaceArray('?', $bindings, $exception->getSql());
        } catch (\Throwable $throwable) {
            return $exception->getSql() . ' | bindings: ' . implode(', ', $bindings);
        }
    }

    public function render()
    {
        return view('livewire.branch.branch-detail');
    }
}
