<?php

namespace App\Livewire\Warehouse;

use App\Models\CheckStockReport;
use App\Models\CheckStockDetail;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class WarehouseAddCheckStockForm extends Component
{
    use WithFileUploads;

    public $warehouse_id;
    public $user_create_id;
    public $datetime_create;
    public $description;
    public $warehouses;
    public $users;
    public $products;
    public $selectedProducts = [];
    public $newProductId;
    public $newProductQuantity;

    protected $rules = [
        'warehouse_id' => 'required|exists:warehouses,id',
        'user_create_id' => 'required|exists:users,id',
        'datetime_create' => 'required|date',
        'description' => 'nullable|string|max:500',
        'selectedProducts' => 'required|array|min:1',
        'selectedProducts.*.product_id' => 'required|exists:products,id',
        'selectedProducts.*.quantity' => 'required|integer|min:0',
    ];

    protected $messages = [
        'warehouse_id.required' => 'Please select a warehouse.',
        'warehouse_id.exists' => 'Selected warehouse does not exist.',
        'user_create_id.required' => 'Please select a user.',
        'user_create_id.exists' => 'Selected user does not exist.',
        'datetime_create.required' => 'Please select a date.',
        'datetime_create.date' => 'Please enter a valid date.',
        'description.max' => 'Description cannot exceed 500 characters.',
        'selectedProducts.required' => 'Please add at least one product.',
        'selectedProducts.min' => 'Please add at least one product.',
        'selectedProducts.*.product_id.required' => 'Product is required.',
        'selectedProducts.*.product_id.exists' => 'Selected product does not exist.',
        'selectedProducts.*.quantity.required' => 'Quantity is required.',
        'selectedProducts.*.quantity.integer' => 'Quantity must be a number.',
        'selectedProducts.*.quantity.min' => 'Quantity must be at least 0.',
    ];

    public function mount()
    {
        $this->warehouses = Warehouse::where('warehouse_status_id', '!=', 0)->get(); // Exclude deleted warehouses
        $this->users = User::where('user_status_id', 1)->get(); // Active users
        $this->products = Product::where('product_status_id', 1)->get(); // Active products
        $this->datetime_create = now()->format('Y-m-d\TH:i');
        $this->user_create_id = auth()->id();
        $this->selectedProducts = [];
    }

    public function addProduct()
    {
        $this->validate([
            'newProductId' => 'required|exists:products,id',
            'newProductQuantity' => 'required|integer|min:0',
        ], [
            'newProductId.required' => 'Please select a product.',
            'newProductId.exists' => 'Selected product does not exist.',
            'newProductQuantity.required' => 'Please enter quantity.',
            'newProductQuantity.integer' => 'Quantity must be a number.',
            'newProductQuantity.min' => 'Quantity must be at least 0.',
        ]);

        // Check if product already exists in selected products
        $existingIndex = collect($this->selectedProducts)->search(function ($item) {
            return $item['product_id'] == $this->newProductId;
        });

        if ($existingIndex !== false) {
            // Update existing product quantity
            $this->selectedProducts[$existingIndex]['quantity'] += $this->newProductQuantity;
        } else {
            // Add new product
            $product = Product::find($this->newProductId);
            $this->selectedProducts[] = [
                'product_id' => $this->newProductId,
                'product_name' => $product->name,
                'sku_number' => $product->sku_number,
                'unit_name' => $product->unit_name,
                'quantity' => $this->newProductQuantity,
            ];
        }

        // Reset new product fields
        $this->newProductId = '';
        $this->newProductQuantity = '';
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts); // Re-index array
    }

    public function updateProductQuantity($index, $quantity)
    {
        if (isset($this->selectedProducts[$index])) {
            $this->selectedProducts[$index]['quantity'] = max(0, intval($quantity));
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Create check stock report
            $checkStockReport = CheckStockReport::create([
                'warehouse_id' => $this->warehouse_id,
                'user_create_id' => $this->user_create_id,
                'datetime_create' => $this->datetime_create,
                'closed' => false,
            ]);

            // Create check stock details for each product
            foreach ($this->selectedProducts as $product) {
                CheckStockDetail::create([
                    'check_stock_report_id' => $checkStockReport->id,
                    'product_id' => $product['product_id'],
                    'user_check_id' => $this->user_create_id,
                    'product_scan_num' => $product['quantity'],
                    'datetime_scan' => $this->datetime_create,
                ]);
            }

            // Dispatch events
            $this->dispatch('checkStockReportListUpdated');
            $this->dispatch('checkStockReportSelected', $checkStockReport);
            $this->dispatch('hideAddForm');
            $this->dispatch('showSuccessMessage', 'Check stock report created successfully with ' . count($this->selectedProducts) . ' products!');

            // Reset form
            $this->resetForm();

        } catch (\Exception $e) {
            $this->dispatch('showErrorMessage', 'Error creating check stock report: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $this->resetForm();
        $this->dispatch('hideAddForm');
    }

    private function resetForm()
    {
        $this->reset(['warehouse_id', 'description', 'selectedProducts', 'newProductId', 'newProductQuantity']);
        $this->datetime_create = now()->format('Y-m-d\TH:i');
        $this->user_create_id = auth()->id();
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-add-check-stock-form');
    }
}
