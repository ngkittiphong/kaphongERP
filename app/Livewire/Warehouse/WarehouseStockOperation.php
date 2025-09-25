<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Services\InventoryService;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Branch;

class WarehouseStockOperation extends Component
{
    public $warehouseId;
    public $productId;
    public $quantity;
    public $unitPrice;
    public $salePrice;
    public $detail;
    public $operationType = 'stock_in'; // stock_in, stock_out, adjustment, transfer
    public $newQuantity; // For adjustment
    public $fromWarehouseId; // For transfer
    public $toWarehouseId; // For transfer
    public $transferSlipId; // For transfer

    // Available warehouses for transfer
    public $availableWarehouses = [];
    public $availableProducts = [];

    protected $inventoryService;

    public function boot(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function mount()
    {
        $this->loadAvailableWarehouses();
        $this->loadAvailableProducts();
    }

    public function updatedWarehouseId()
    {
        $this->loadAvailableProducts();
        $this->reset(['productId', 'quantity', 'unitPrice', 'salePrice', 'detail', 'newQuantity']);
    }

    public function updatedOperationType()
    {
        $this->reset(['quantity', 'unitPrice', 'salePrice', 'detail', 'newQuantity', 'fromWarehouseId', 'toWarehouseId']);
    }

    public function stockIn()
    {
        $this->validate([
            'warehouseId' => 'required|exists:warehouses,id',
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unitPrice' => 'nullable|numeric|min:0',
            'salePrice' => 'nullable|numeric|min:0',
        ]);

        try {
            $result = $this->inventoryService->stockIn([
                'warehouse_id' => $this->warehouseId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'unit_price' => $this->unitPrice ?? 0,
                'sale_price' => $this->salePrice ?? 0,
                'detail' => $this->detail ?? 'Stock In via Livewire',
                'date_activity' => now(),
            ]);

            session()->flash('success', $result['message']);
            $this->reset(['quantity', 'unitPrice', 'salePrice', 'detail']);

            // Dispatch event to refresh warehouse detail
            $this->dispatch('warehouseUpdated', ['warehouseId' => $this->warehouseId]);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function stockOut()
    {
        $this->validate([
            'warehouseId' => 'required|exists:warehouses,id',
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $result = $this->inventoryService->stockOut([
                'warehouse_id' => $this->warehouseId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'detail' => $this->detail ?? 'Stock Out via Livewire',
                'date_activity' => now(),
            ]);

            session()->flash('success', $result['message']);
            $this->reset(['quantity', 'detail']);

            // Dispatch event to refresh warehouse detail
            $this->dispatch('warehouseUpdated', ['warehouseId' => $this->warehouseId]);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function stockAdjustment()
    {
        $this->validate([
            'warehouseId' => 'required|exists:warehouses,id',
            'productId' => 'required|exists:products,id',
            'newQuantity' => 'required|integer|min:0',
        ]);

        try {
            $result = $this->inventoryService->stockAdjustment([
                'warehouse_id' => $this->warehouseId,
                'product_id' => $this->productId,
                'new_quantity' => $this->newQuantity,
                'detail' => $this->detail ?? 'Stock Adjustment via Livewire',
                'date_activity' => now(),
            ]);

            session()->flash('success', $result['message']);
            $this->reset(['newQuantity', 'detail']);

            // Dispatch event to refresh warehouse detail
            $this->dispatch('warehouseUpdated', ['warehouseId' => $this->warehouseId]);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function transferStock()
    {
        $this->validate([
            'fromWarehouseId' => 'required|exists:warehouses,id',
            'toWarehouseId' => 'required|exists:warehouses,id|different:fromWarehouseId',
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unitPrice' => 'nullable|numeric|min:0',
            'salePrice' => 'nullable|numeric|min:0',
        ]);

        try {
            $result = $this->inventoryService->transferStock([
                'from_warehouse_id' => $this->fromWarehouseId,
                'to_warehouse_id' => $this->toWarehouseId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'unit_price' => $this->unitPrice ?? 0,
                'sale_price' => $this->salePrice ?? 0,
                'transfer_slip_id' => $this->transferSlipId,
                'detail' => $this->detail ?? 'Stock Transfer via Livewire',
                'date_activity' => now(),
            ]);

            session()->flash('success', $result['message']);
            $this->reset(['quantity', 'unitPrice', 'salePrice', 'detail', 'transferSlipId']);

            // Dispatch event to refresh both warehouse details
            $this->dispatch('warehouseUpdated', ['warehouseId' => $this->fromWarehouseId]);
            $this->dispatch('warehouseUpdated', ['warehouseId' => $this->toWarehouseId]);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getCurrentStockBalance()
    {
        if ($this->warehouseId && $this->productId) {
            return $this->inventoryService->getStockBalance($this->warehouseId, $this->productId);
        }
        return 0;
    }

    private function loadAvailableWarehouses()
    {
        $this->availableWarehouses = Warehouse::with('branch')
            ->whereHas('status', function($query) {
                $query->where('name', 'Active');
            })
            ->orderBy('name')
            ->get();
    }

    private function loadAvailableProducts()
    {
        if ($this->warehouseId) {
            // Get products that exist in this warehouse
            $this->availableProducts = Product::whereHas('warehouseProducts', function($query) {
                $query->where('warehouse_id', $this->warehouseId);
            })->orderBy('name')->get();
        } else {
            $this->availableProducts = Product::orderBy('name')->get();
        }
    }

    public function render()
    {
        $currentBalance = $this->getCurrentStockBalance();
        
        return view('livewire.warehouse.warehouse-stock-operation', [
            'currentBalance' => $currentBalance
        ]);
    }
}
