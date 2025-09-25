# InventoryService Usage Guide

## Overview
The `InventoryService` provides comprehensive stock management functionality that maintains both the `inventories` (transaction history) and `warehouses_products` (current balances) tables in sync.

## Features
- ✅ **Stock In** - Add inventory with weighted average pricing
- ✅ **Stock Out** - Remove inventory with balance validation
- ✅ **Stock Adjustment** - Set specific quantities
- ✅ **Stock Transfer** - Move between warehouses
- ✅ **Data Integrity** - Automatic validation and reconciliation
- ✅ **Weighted Average Pricing** - Automatic cost calculation

## Quick Start

### 1. Basic Usage in Controllers
```php
use App\Services\InventoryService;

class YourController extends Controller
{
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function addStock(Request $request)
    {
        $result = $this->inventoryService->stockIn([
            'warehouse_id' => $request->warehouse_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'sale_price' => $request->sale_price,
            'detail' => 'Purchase Order #PO-2024-001'
        ]);

        return response()->json($result);
    }
}
```

### 2. Usage in Livewire Components
```php
use App\Services\InventoryService;

class YourLivewireComponent extends Component
{
    public function boot(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function processStockIn()
    {
        try {
            $result = $this->inventoryService->stockIn([
                'warehouse_id' => $this->warehouseId,
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'unit_price' => $this->unitPrice,
                'detail' => 'Stock In via Livewire'
            ]);

            session()->flash('success', $result['message']);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
```

## Available Operations

### Stock In
```php
$result = $inventoryService->stockIn([
    'warehouse_id' => 1,
    'product_id' => 123,
    'quantity' => 100,
    'unit_price' => 25.50,    // Optional
    'sale_price' => 35.00,    // Optional
    'detail' => 'Purchase Order #PO-001',
    'date_activity' => now(), // Optional, defaults to now()
    'sale_order_id' => null,  // Optional
    'transfer_slip_id' => null, // Optional
    'contact_id' => null,     // Optional
]);
```

### Stock Out
```php
$result = $inventoryService->stockOut([
    'warehouse_id' => 1,
    'product_id' => 123,
    'quantity' => 10,
    'detail' => 'Sales Order #SO-001',
    'date_activity' => now(), // Optional
    'sale_order_id' => null,  // Optional
    'transfer_slip_id' => null, // Optional
    'contact_id' => null,     // Optional
]);
```

### Stock Adjustment
```php
$result = $inventoryService->stockAdjustment([
    'warehouse_id' => 1,
    'product_id' => 123,
    'new_quantity' => 95,     // Set to specific quantity
    'detail' => 'Physical count adjustment',
    'date_activity' => now(), // Optional
]);
```

### Stock Transfer
```php
$result = $inventoryService->transferStock([
    'from_warehouse_id' => 1,
    'to_warehouse_id' => 2,
    'product_id' => 123,
    'quantity' => 50,
    'unit_price' => 25.50,    // Optional, for destination warehouse
    'sale_price' => 35.00,    // Optional, for destination warehouse
    'transfer_slip_id' => 'TS-001', // Optional
    'detail' => 'Transfer between warehouses',
    'date_activity' => now(), // Optional
]);
```

## Utility Methods

### Get Current Stock Balance
```php
$balance = $inventoryService->getStockBalance($warehouseId, $productId);
```

### Get Stock History
```php
$history = $inventoryService->getStockHistory($warehouseId, $productId, $limit = 50);
```

### Validate Stock Integrity
```php
$validation = $inventoryService->validateStockIntegrity($warehouseId, $productId);
// Returns: ['is_valid' => bool, 'current_balance' => int, 'calculated_balance' => int, 'difference' => int]
```

### Reconcile Stock
```php
$result = $inventoryService->reconcileStock($warehouseId, $productId);
// Automatically fixes discrepancies between warehouses_products and inventories tables
```

## API Endpoints

The service is also available via HTTP API endpoints:

- `POST /inventory/stock-in` - Stock In operation
- `POST /inventory/stock-out` - Stock Out operation
- `POST /inventory/stock-adjustment` - Stock Adjustment operation
- `POST /inventory/transfer-stock` - Transfer Stock operation
- `GET /inventory/stock-balance` - Get current balance
- `GET /inventory/stock-history` - Get movement history
- `GET /inventory/validate-integrity` - Validate data integrity
- `POST /inventory/reconcile-stock` - Reconcile stock

## Livewire Integration

The system includes a ready-to-use Livewire component for stock operations:

```blade
@livewire('warehouse.warehouse-stock-operation', ['warehouseId' => $warehouse->id])
```

This component provides a complete UI for all stock operations including:
- Stock In/Out forms
- Stock Adjustment interface
- Transfer between warehouses
- Real-time balance display
- Validation and error handling

## Error Handling

All operations are wrapped in database transactions and include comprehensive error handling:

```php
try {
    $result = $inventoryService->stockOut($data);
    // Success
} catch (\Exception $e) {
    // Handle error: $e->getMessage()
}
```

Common error scenarios:
- Insufficient stock for stock out
- Invalid warehouse/product IDs
- Negative quantities
- Database constraint violations

## Data Integrity

The service automatically:
- ✅ Maintains both `inventories` and `warehouses_products` tables
- ✅ Calculates weighted average prices
- ✅ Validates stock availability
- ✅ Uses database transactions for consistency
- ✅ Provides reconciliation methods

## Testing

Run the test suite to verify functionality:

```bash
php artisan test tests/Feature/InventoryServiceTest.php
```

## Integration with Existing System

The service integrates seamlessly with your existing warehouse system:
- ✅ Updated warehouse detail view with stock operations tab
- ✅ Real-time average remaining price calculation
- ✅ Event-driven updates between components
- ✅ Maintains existing data structure and relationships
