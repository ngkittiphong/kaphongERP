<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController
{
    public function index()
    {
        return Warehouse::with(['branch', 'status'])
            ->orderBy('warehouse_status_id', 'asc')
            ->orderBy('name')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'warehouse_code' => 'required|string|max:50|unique:warehouses,warehouse_code',
            'name_th' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'address_th' => 'nullable|string',
            'address_en' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean',
            'is_main_warehouse' => 'boolean',
            'description' => 'nullable|string',
            'contact_name' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:100',
            'contact_mobile' => 'nullable|string|max:20',
        ]);

        $warehouse = Warehouse::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Warehouse created successfully!',
            'warehouse' => $warehouse->load('branch')
        ], 201);
    }

    public function show(Warehouse $warehouse)
    {
        return response()->json([
            'success' => true,
            'warehouse' => $warehouse->load('branch')
        ]);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'warehouse_code' => 'required|string|max:50|unique:warehouses,warehouse_code,' . $warehouse->id,
            'name_th' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'address_th' => 'nullable|string',
            'address_en' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_active' => 'boolean',
            'is_main_warehouse' => 'boolean',
            'description' => 'nullable|string',
            'contact_name' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:100',
            'contact_mobile' => 'nullable|string|max:20',
        ]);

        $warehouse->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Warehouse updated successfully!',
            'warehouse' => $warehouse->load('branch')
        ]);
    }

    public function destroy(Warehouse $warehouse)
    {
        // Soft delete by setting status to Inactive instead of actually deleting
        $inactiveStatus = \App\Models\WarehouseStatus::where('name', 'Inactive')->first();
        if ($inactiveStatus) {
            $warehouse->update(['warehouse_status_id' => $inactiveStatus->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Warehouse deactivated successfully!'
        ]);
    }
}
