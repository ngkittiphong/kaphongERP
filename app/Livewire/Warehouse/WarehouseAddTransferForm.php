<?php

namespace App\Livewire\Warehouse;

use App\Models\TransferSlip;
use App\Models\TransferSlipDetail;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\TransferSlipStatus;
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
    
    // Form state
    public $showForm = false;
    public $isSubmitting = false;
    
    protected $listeners = [
        'showAddForm' => 'showAddForm',
        'transferSlipCreated' => 'handleTransferSlipCreated',
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
        'transferProducts.*.product_id' => 'required|exists:products,id',
        'transferProducts.*.quantity' => 'required|numeric|min:0.01',
        'transferProducts.*.cost_per_unit' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'warehouseDestinationId.different' => 'Destination warehouse must be different from origin warehouse.',
        'transferProducts.required' => 'At least one product must be added to the transfer.',
        'transferProducts.*.product_id.required' => 'Please select a product.',
        'transferProducts.*.quantity.required' => 'Quantity is required.',
        'transferProducts.*.quantity.min' => 'Quantity must be greater than 0.',
        'transferProducts.*.cost_per_unit.required' => 'Cost per unit is required.',
        'transferProducts.*.cost_per_unit.min' => 'Cost per unit must be 0 or greater.',
    ];

    public function mount()
    {
        Log::info('🔥 WarehouseAddTransferForm: mount() called');
        $this->dateRequest = now()->format('Y-m-d');
        $this->userRequestName = auth()->user()->username ?? '';
        $this->loadDropdownData();
        $this->addEmptyProduct();
        Log::info('🔥 WarehouseAddTransferForm: mount() completed');
    }

    public function loadDropdownData()
    {
        $this->warehouses = Warehouse::with('branch')
            ->whereHas('status', function($query) {
                $query->where('name', 'Active');
            })
            ->get();
            
        $this->products = Product::with(['type', 'group', 'status'])
            ->get();
            
        $this->users = User::all();
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
            ];
        }
    }

    public function removeProduct($index)
    {
        if (count($this->transferProducts) > 1) {
            unset($this->transferProducts[$index]);
            $this->transferProducts = array_values($this->transferProducts);
        }
    }

    public function updatedTransferProducts($value, $index)
    {
        if (str_contains($index, '.product_id')) {
            $this->selectProduct($index);
        } elseif (str_contains($index, '.quantity') || str_contains($index, '.cost_per_unit')) {
            $this->calculateProductTotal($index);
        }
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

    public function testMethod()
    {
        Log::info('🔥 WarehouseAddTransferForm: testMethod() called - component is working!');
        $this->dispatch('showSuccessMessage', 'Test method called successfully!');
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
        $this->companyName = '';
        $this->companyAddress = '';
        $this->taxId = '';
        $this->tel = '';
        $this->dateRequest = now()->format('Y-m-d');
        $this->userRequestName = auth()->user()->username ?? '';
        $this->deliverName = '';
        $this->warehouseOriginId = '';
        $this->warehouseDestinationId = '';
        $this->description = '';
        $this->note = '';
        $this->transferProducts = [];
        $this->addEmptyProduct();
        $this->isSubmitting = false;
    }

    public function submit()
    {
        Log::info('🔥 WarehouseAddTransferForm: submit() method called');
        Log::info('🔥 Form data:', [
            'companyName' => $this->companyName,
            'warehouseOriginId' => $this->warehouseOriginId,
            'warehouseDestinationId' => $this->warehouseDestinationId,
            'transferProducts' => $this->transferProducts
        ]);
        
        try {
            Log::info('🔥 Starting validation...');
            $this->validate();
            Log::info('🔥 Validation passed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('🔥 Validation failed:', ['errors' => $e->errors()]);
            throw $e;
        }
        
        // Filter out empty products
        $this->transferProducts = array_filter($this->transferProducts, function($product) {
            return !empty($product['product_id']);
        });
        
        Log::info('🔥 Filtered products:', ['products' => $this->transferProducts]);
        
        if (empty($this->transferProducts)) {
            Log::error('🔥 No products found after filtering');
            $this->addError('transferProducts', 'At least one product must be added to the transfer.');
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
            
            // Get pending status
            $pendingStatus = TransferSlipStatus::where('name', 'Pending')->first();
            Log::info('🔥 Pending status found:', ['status_id' => $pendingStatus ? $pendingStatus->id : 'NOT FOUND']);
            
            // Create transfer slip
            Log::info('🔥 Creating transfer slip with data:', [
                'user_request_id' => auth()->id(),
                'transfer_slip_number' => $this->transferSlipNumber,
                'company_name' => $this->companyName,
                'warehouse_origin_id' => $this->warehouseOriginId,
                'warehouse_destination_id' => $this->warehouseDestinationId,
                'total_quantity' => $this->getTotalQuantity(),
                'transfer_slip_status_id' => $pendingStatus->id,
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
                'transfer_slip_status_id' => $pendingStatus->id,
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

    public function render()
    {
        return view('livewire.warehouse.warehouse-add-transfer-form');
    }
}
