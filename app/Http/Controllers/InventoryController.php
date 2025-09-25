<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Stock In endpoint
     */
    public function stockIn(Request $request): JsonResponse
    {
        try {
            $result = $this->inventoryService->stockIn($request->all());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Stock Out endpoint
     */
    public function stockOut(Request $request): JsonResponse
    {
        try {
            $result = $this->inventoryService->stockOut($request->all());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Stock Adjustment endpoint
     */
    public function stockAdjustment(Request $request): JsonResponse
    {
        try {
            $result = $this->inventoryService->stockAdjustment($request->all());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Transfer Stock endpoint
     */
    public function transferStock(Request $request): JsonResponse
    {
        try {
            $result = $this->inventoryService->transferStock($request->all());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get stock balance
     */
    public function getStockBalance(Request $request): JsonResponse
    {
        $warehouseId = $request->input('warehouse_id');
        $productId = $request->input('product_id');

        $balance = $this->inventoryService->getStockBalance($warehouseId, $productId);

        return response()->json([
            'warehouse_id' => $warehouseId,
            'product_id' => $productId,
            'balance' => $balance
        ]);
    }

    /**
     * Get stock history
     */
    public function getStockHistory(Request $request): JsonResponse
    {
        $warehouseId = $request->input('warehouse_id');
        $productId = $request->input('product_id');
        $limit = $request->input('limit', 50);

        $history = $this->inventoryService->getStockHistory($warehouseId, $productId, $limit);

        return response()->json($history);
    }

    /**
     * Validate stock integrity
     */
    public function validateStockIntegrity(Request $request): JsonResponse
    {
        $warehouseId = $request->input('warehouse_id');
        $productId = $request->input('product_id');

        $result = $this->inventoryService->validateStockIntegrity($warehouseId, $productId);

        return response()->json($result);
    }

    /**
     * Reconcile stock
     */
    public function reconcileStock(Request $request): JsonResponse
    {
        $warehouseId = $request->input('warehouse_id');
        $productId = $request->input('product_id');

        $result = $this->inventoryService->reconcileStock($warehouseId, $productId);

        return response()->json($result);
    }
}
