<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Services\InventoryService;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class WarehouseStockOutForm extends Component
{
    public $warehouseId;
    public $productId;
    public $quantity;
    public $unitPrice;
    public $salePrice;
    public $detail;
    public $dateActivity;

    public $warehouses = [];
    public $products = [];
    public $selectedWarehouse = null;
    public $selectedProduct = null;
    public $currentStockBalance = 0;

    protected $rules = [
        'warehouseId' => 'required|exists:warehouses,id',
        'productId' => 'required|exists:products,id',
        'quantity' => 'required|numeric|min:1',
        'unitPrice' => 'nullable|numeric|min:0',
        'salePrice' => 'nullable|numeric|min:0',
        'detail' => 'nullable|string|max:255',
        'dateActivity' => 'nullable|date',
    ];

    protected $messages = [
        'warehouseId.required' => 'Please select a warehouse.',
        'productId.required' => 'Please select a product.',
        'quantity.required' => 'Quantity is required.',
        'quantity.min' => 'Quantity must be at least 1.',
        'unitPrice.min' => 'Unit price cannot be negative.',
        'salePrice.min' => 'Sale price cannot be negative.',
    ];

    public function mount()
    {
        $this->dateActivity = now()->format('Y-m-d');
        $this->loadDropdownData();
    }

    public function render()
    {
        // Force update stock balance on every render to ensure it's current
        $this->updateStockBalance();
        
        return view('livewire.warehouse.warehouse-stock-out-form');
    }

    public function updatedWarehouseId($value)
    {
        if ($value) {
            $this->selectedWarehouse = Warehouse::find($value);
            $this->updateStockBalance();
        } else {
            $this->selectedWarehouse = null;
            $this->currentStockBalance = 0;
        }
    }

    public function updatedProductId($value)
    {
        if ($value) {
            $this->selectedProduct = Product::find($value);
            $this->updateStockBalance();
        } else {
            $this->selectedProduct = null;
            $this->currentStockBalance = 0;
        }
    }

    public function stockOut()
    {
        $this->validate();

        // Additional validation for stock availability
        if ($this->quantity > $this->currentStockBalance) {
            session()->flash('error', "Insufficient stock. Available: {$this->currentStockBalance}, Requested: {$this->quantity}");
            return;
        }

        try {
            $inventoryService = app(InventoryService::class);
            
            $result = $inventoryService->stockOut([
                'warehouse_id' => $this->warehouseId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'unit_price' => $this->unitPrice ?? 0,
                'sale_price' => $this->salePrice ?? 0,
                'detail' => $this->detail ?? 'Stock Out via Form',
                'date_activity' => $this->dateActivity ? \Carbon\Carbon::parse($this->dateActivity) : now(),
            ]);

            session()->flash('success', $result['message']);
            $this->resetForm();
            $this->updateStockBalance();

            // Dispatch events to refresh other components
            $this->dispatch('stockOperationCompleted', ['type' => 'stock-out', 'warehouseId' => $this->warehouseId]);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            Log::error('Stock Out Form Error: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['productId', 'quantity', 'unitPrice', 'salePrice', 'detail']);
        $this->selectedProduct = null;
        $this->currentStockBalance = 0;
        $this->dateActivity = now()->format('Y-m-d');
    }

    private function loadDropdownData()
    {
        $this->warehouses = Warehouse::with('branch')
            ->whereHas('status', function($query) {
                $query->where('name', 'Active');
            })
            ->orderBy('name')
            ->get();

        // Remove status filter to show all products for stock operations
        $this->products = Product::orderBy('name')->get();
    }

    private function updateStockBalance()
    {
        if ($this->warehouseId && $this->productId) {
            try {
                $inventoryService = app(InventoryService::class);
                $this->currentStockBalance = $inventoryService->getStockBalance($this->warehouseId, $this->productId);
            } catch (\Exception $e) {
                Log::error('Stock balance update error: ' . $e->getMessage());
                $this->currentStockBalance = 0;
            }
        } else {
            $this->currentStockBalance = 0;
        }
    }
}