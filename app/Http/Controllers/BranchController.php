<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BranchController
{
    /**
     * Display a listing of branches.
     */
    public function index()
    {
        $branches = Branch::all();
        return $branches; //view('branch.index', compact('branches'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('branch.create');
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_th' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'address_th' => 'required|string',
            'address_en' => 'required|string',
            'phone' => 'required|string|max:20',
            'mobile' => 'required|string|max:20',
            'fax' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    /**
     * Get the head office branch and other branches.
     */
    public function getHeadOffice()
    {
        $headOffice = Branch::where('is_head_office', true)
            ->where('is_active', true)
            ->first();
        $otherBranches = Branch::where('is_head_office', false)
            ->where('is_active', true)
            ->get();
        return view('setting.setting_company_profile', compact('headOffice', 'otherBranches'));
    }

    /**
     * Display the specified branch.
     */
    public function show(Branch $branch)
    {
        return view('branch.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(Branch $branch)
    {
        return view('branch.edit', compact('branch'));
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        try {
            $validated = $request->validate([
                'branch_code' => 'required|string|max:50',
                'name_th' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'address_th' => 'nullable|string',
                'address_en' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'mobile' => 'nullable|string|max:20',
                'fax' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
            ]);

            $branch->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Branch updated successfully.',
                    'branch' => $branch
                ]);
            }

            return redirect()->route('setting.company_profile')
                ->with('success', 'Branch updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating branch: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Error updating branch: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(Branch $branch)
    {
        try {
            // Don't allow deletion of head office
            if ($branch->is_head_office) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete head office branch'
                ], 400);
            }

            // Soft delete by updating is_active and setting deleted_at
            $branch->update([
                'is_active' => false,
                'deleted_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branch deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting branch: ' . $e->getMessage()
            ], 500);
        }
    }
} 