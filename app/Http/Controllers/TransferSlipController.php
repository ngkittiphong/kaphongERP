<?php

namespace App\Http\Controllers;

use App\Models\TransferSlip;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransferSlipController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TransferSlip::with([
            'userRequest',
            'userReceive', 
            'warehouseOrigin',
            'warehouseDestination',
            'status',
            'transferSlipDetails'
        ])->orderBy('date_request', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_request_id' => 'required|exists:users,id',
            'warehouse_origin_id' => 'required|exists:warehouses,id',
            'warehouse_destination_id' => 'required|exists:warehouses,id',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'tel' => 'nullable|string',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $validated['transfer_slip_number'] = TransferSlip::generateTransferSlipNumber();
        $validated['date_request'] = now();
        $validated['user_request_name'] = auth()->user()->username;
        $validated['transfer_slip_status_id'] = 1; // Default status

        $transferSlip = TransferSlip::create($validated);

        return response()->json([
            'success' => true,
            'data' => $transferSlip->load(['userRequest', 'warehouseOrigin', 'warehouseDestination', 'status'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransferSlip $transferSlip)
    {
        return $transferSlip->load([
            'userRequest',
            'userReceive',
            'warehouseOrigin', 
            'warehouseDestination',
            'status',
            'transferSlipDetails.product'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransferSlip $transferSlip)
    {
        $validated = $request->validate([
            'user_receive_id' => 'nullable|exists:users,id',
            'warehouse_origin_id' => 'required|exists:warehouses,id',
            'warehouse_destination_id' => 'required|exists:warehouses,id',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'tel' => 'nullable|string',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'transfer_slip_status_id' => 'required|exists:transfer_slip_statuses,id',
        ]);

        $transferSlip->update($validated);

        return response()->json([
            'success' => true,
            'data' => $transferSlip->load(['userRequest', 'warehouseOrigin', 'warehouseDestination', 'status'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransferSlip $transferSlip)
    {
        $transferSlip->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transfer slip deleted successfully'
        ]);
    }

    /**
     * Update transfer slip status
     */
    public function updateStatus(Request $request, TransferSlip $transferSlip)
    {
        $validated = $request->validate([
            'transfer_slip_status_id' => 'required|exists:transfer_slip_statuses,id',
            'user_receive_id' => 'nullable|exists:users,id',
        ]);

        if (isset($validated['user_receive_id'])) {
            $validated['user_receive_name'] = \App\Models\User::find($validated['user_receive_id'])->username;
            $validated['date_receive'] = now();
        }

        $transferSlip->update($validated);

        return response()->json([
            'success' => true,
            'data' => $transferSlip->load(['userRequest', 'userReceive', 'warehouseOrigin', 'warehouseDestination', 'status'])
        ]);
    }
}
