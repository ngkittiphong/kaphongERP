<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\TransferSlip;
use App\Models\TransferSlipDetail;
use App\Models\Inventory;
use App\Models\MoveType;
use Carbon\Carbon;

class ProductStockCard extends Component
{
    public $product;
    public $productId;
    public $startDate;
    public $endDate;
    public $selectedBranchId;
    public $selectedWarehouseIds = [];
    
    // Data properties
    public $branches = [];
    public $warehouses = [];
    public $stockMovements = [];
    public $summaryData = [];
    
    // Summary cards data
    public $remainingStock = 0;
    public $incomingStock = 0;
    public $outgoingStock = 0;
    public $unitName = 'pieces';

    protected $listeners = [
        'refreshStockCard' => 'loadStockCardData'
    ];

    public function mount($productId = null)
    {
        $this->productId = $productId;
        $this->loadBranches();
        $this->loadWarehouses();
        
        // Set default date range to last 30 days
        $this->endDate = now()->format('Y-m-d');
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        
        if ($productId) {
            $this->loadProduct($productId);
        } else {
            // Initialize with empty data
            $this->stockMovements = [];
            $this->remainingStock = 0;
            $this->incomingStock = 0;
            $this->outgoingStock = 0;
        }
    }

    public function loadProduct($productId = null)
    {
        if (!$productId) {
            return;
        }
        
        $this->productId = $productId;
        $this->product = Product::with(['type', 'group', 'status'])->find($productId);
        
        if ($this->product) {
            $this->loadStockCardData();
        } else {
            // Reset data if product not found
            $this->stockMovements = [];
            $this->remainingStock = 0;
            $this->incomingStock = 0;
            $this->outgoingStock = 0;
        }
    }

    public function loadBranches()
    {
        $this->branches = Branch::where('is_active', true)
            ->orderBy('name_en')
            ->get();
    }

    public function loadWarehouses()
    {
        $this->warehouses = Warehouse::with('branch')->orderBy('name')->get();
    }

    public function updatedSelectedBranchId()
    {
        // Handle prefixed selection values
        if (str_starts_with($this->selectedBranchId, 'branch_')) {
            // Branch selection - select all warehouses for this branch
            $branchId = str_replace('branch_', '', $this->selectedBranchId);
            $branchWarehouses = $this->warehouses->where('branch_id', $branchId);
            $this->selectedWarehouseIds = $branchWarehouses->pluck('id')->toArray();
        } elseif (str_starts_with($this->selectedBranchId, 'warehouse_')) {
            // Warehouse selection - select only this warehouse
            $warehouseId = str_replace('warehouse_', '', $this->selectedBranchId);
            $this->selectedWarehouseIds = [$warehouseId];
        } else {
            // No selection or "All" selected
            $this->selectedWarehouseIds = [];
        }
        
        $this->loadStockCardData();
    }

    public function updatedStartDate()
    {
        $this->loadStockCardData();
    }

    public function updatedEndDate()
    {
        $this->loadStockCardData();
    }

    public function loadStockCardData()
    {
        if (!$this->product) {
            return;
        }

        $this->stockMovements = [];
        $this->remainingStock = 0;
        $this->incomingStock = 0;
        $this->outgoingStock = 0;

        // Get stock movements from transfer slips
        $this->loadTransferSlipMovements();
        
        // Get stock movements from inventory table
        $this->loadInventoryMovements();
        
        // Sort movements by date
        usort($this->stockMovements, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Calculate summary data
        $this->calculateSummaryData();
    }

    private function loadTransferSlipMovements()
    {
        $query = TransferSlip::with(['transferSlipDetails', 'warehouseOrigin', 'warehouseDestination'])
            ->whereHas('transferSlipDetails', function($q) {
                $q->where('product_id', $this->product->id);
            });

        // Apply date filter
        if ($this->startDate) {
            $query->where('date_request', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->where('date_request', '<=', $this->endDate . ' 23:59:59');
        }

        // Apply warehouse filter
        if (!empty($this->selectedWarehouseIds)) {
            $query->where(function($q) {
                $q->whereIn('warehouse_origin_id', $this->selectedWarehouseIds)
                  ->orWhereIn('warehouse_destination_id', $this->selectedWarehouseIds);
            });
        }

        $transferSlips = $query->get();

        foreach ($transferSlips as $transferSlip) {
            foreach ($transferSlip->transferSlipDetails as $detail) {
                if ($detail->product_id == $this->product->id) {
                    // Outgoing from origin warehouse
                    $this->stockMovements[] = [
                        'type' => 'out',
                        'date' => $transferSlip->date_request->format('Y-m-d'),
                        'document_no' => $transferSlip->transfer_slip_number,
                        'detail' => 'Transfer Out',
                        'warehouse' => $transferSlip->warehouse_origin_name,
                        'quantity_in' => 0,
                        'quantity_out' => $detail->quantity,
                        'unit' => $detail->unit_name,
                        'color' => '#F1CFCF'
                    ];

                    // Incoming to destination warehouse
                    $this->stockMovements[] = [
                        'type' => 'in',
                        'date' => $transferSlip->date_receive ? $transferSlip->date_receive->format('Y-m-d') : $transferSlip->date_request->format('Y-m-d'),
                        'document_no' => $transferSlip->transfer_slip_number,
                        'detail' => 'Transfer In',
                        'warehouse' => $transferSlip->warehouse_destination_name,
                        'quantity_in' => $detail->quantity,
                        'quantity_out' => 0,
                        'unit' => $detail->unit_name,
                        'color' => '#D0F1CF'
                    ];
                }
            }
        }
    }

    private function loadInventoryMovements()
    {
        $query = Inventory::with(['moveType', 'warehouse'])
            ->where('product_id', $this->product->id);

        // Apply date filter
        if ($this->startDate) {
            $query->where('date_activity', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->where('date_activity', '<=', $this->endDate . ' 23:59:59');
        }

        // Apply warehouse filter
        if (!empty($this->selectedWarehouseIds)) {
            $query->whereIn('warehouse_id', $this->selectedWarehouseIds);
        }

        $inventories = $query->get();

        foreach ($inventories as $inventory) {
            $isIncoming = $inventory->quantity_move > 0;
            $quantity = abs($inventory->quantity_move);
            
            $this->stockMovements[] = [
                'type' => $isIncoming ? 'in' : 'out',
                'date' => $inventory->date_activity->format('Y-m-d'),
                'document_no' => $inventory->transfer_slip_id ? 'TS' . $inventory->transfer_slip_id : 'INV' . $inventory->id,
                'detail' => $inventory->detail ?: ($inventory->moveType ? $inventory->moveType->name : 'Inventory Movement'),
                'warehouse' => $inventory->warehouse ? $inventory->warehouse->name : 'Unknown',
                'quantity_in' => $isIncoming ? $quantity : 0,
                'quantity_out' => $isIncoming ? 0 : $quantity,
                'unit' => $inventory->unit_name ?: $this->product->unit_name,
                'color' => $isIncoming ? '#D0F1CF' : '#F1CFCF'
            ];
        }
    }

    private function calculateSummaryData()
    {
        $this->incomingStock = 0;
        $this->outgoingStock = 0;

        foreach ($this->stockMovements as $movement) {
            $this->incomingStock += $movement['quantity_in'];
            $this->outgoingStock += $movement['quantity_out'];
        }

        // Calculate remaining stock (this would typically come from current warehouse balance)
        $this->remainingStock = $this->incomingStock - $this->outgoingStock;
        
        // Set unit name from first movement or product default
        if (!empty($this->stockMovements)) {
            $this->unitName = $this->stockMovements[0]['unit'];
        } else {
            $this->unitName = $this->product->unit_name ?? 'pieces';
        }
    }

    public function viewStockCard()
    {
        $this->loadStockCardData();
    }

    public function render()
    {
        return view('livewire.product.product-stock-card');
    }
}
