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
    public $company_id, $branch_code, $branch_no, $name_th, $name_en, $address_th, $address_en;
    public $bill_address_th, $bill_address_en, $post_code, $phone_country_code, $phone_number;
    public $fax, $website, $email, $is_active, $is_head_office, $latitude, $longitude;
    public $contact_name, $contact_email, $contact_mobile;
    public $candidateBranchNo;
    
    public $companies = [];
    public $companyNameTh;
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

    protected function rules()
    {
        $rules = [
            'company_id' => 'required|exists:companies,id',
            'branch_code' => 'required|string|max:50',
            'branch_no' => 'nullable|string|max:20',
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
            $this->branch_no = $this->branch->branch_no;
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
            'company_id', 'branch_code', 'branch_no', 'name_th', 'name_en', 'address_th', 'address_en',
            'bill_address_th', 'bill_address_en', 'post_code', 'phone_country_code', 'phone_number',
            'fax', 'website', 'email', 'is_active', 'is_head_office', 'latitude', 'longitude',
            'contact_name', 'contact_email', 'contact_mobile', 'candidateBranchNo', 'companyNameTh'
        ]);
        $this->resetErrorBag();
        $this->branch = null;
        $this->warehouses = [];
        $this->is_active = true;
        $this->is_head_office = false;
        
        // Force company to ID=1 and set display name
        $this->company_id = 1;
        $company = Company::find(1);
        $this->companyNameTh = $company?->company_name_th;

        // Generate initial candidate branch number for forced company_id=1
        $this->candidateBranchNo = Branch::generateBranchNo($this->company_id);
        \Log::debug('Generated initial candidate branch number', [
            'candidate_branch_no' => $this->candidateBranchNo
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
            $this->branch_no = $this->branch->branch_no;
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
            'company_id', 'branch_code', 'branch_no', 'name_th', 'name_en', 'address_th', 'address_en',
            'bill_address_th', 'bill_address_en', 'post_code', 'phone_country_code', 'phone_number',
            'fax', 'website', 'email', 'is_active', 'is_head_office', 'latitude', 'longitude',
            'contact_name', 'contact_email', 'contact_mobile', 'candidateBranchNo'
        ]);
    }

    /**
     * Called when company_id is updated - regenerate candidate branch number
     */
    public function updatedCompanyId()
    {
        if ($this->company_id) {
            $this->candidateBranchNo = Branch::generateBranchNo($this->company_id);
            \Log::debug('Generated candidate branch number for selected company', [
                'company_id' => $this->company_id,
                'candidate_branch_no' => $this->candidateBranchNo
            ]);
        } else {
            // If no company selected, generate with default company_id = 1
            $this->candidateBranchNo = Branch::generateBranchNo(1);
            \Log::debug('Generated candidate branch number with default company', [
                'candidate_branch_no' => $this->candidateBranchNo
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
            if (strpos($errorMessage, 'branches_company_id_branch_code_unique') !== false) {
                $this->addError('branch_code', 'This branch code already exists for this company.');
            } elseif (strpos($errorMessage, 'branches_branch_no_unique') !== false) {
                $this->addError('branch_no', 'This branch number already exists.');
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
                'branch_no' => $this->branch_no, // Will be auto-generated if empty
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
                'branch_no' => $this->branch_no,
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
                $this->addError('general', 'Failed to delete branch. Please try again.');
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
