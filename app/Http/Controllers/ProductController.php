<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::with(['type', 'group', 'status'])
            ->get();
        return $products; //view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $types = ProductType::all();
        $groups = ProductGroup::all();
        $statuses = ProductStatus::all();
        $vats = Vat::all();
        $withholdings = Withholding::all();

        return view('product.create', compact('types', 'groups', 'statuses', 'vats', 'withholdings'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku_number' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255',
                'product_type_id' => 'required|exists:product_types,id',
                'product_group_id' => 'required|exists:product_groups,id',
                'product_status_id' => 'required|exists:product_statuses,id',
                'unit_name' => 'required|string|max:255',
                'buy_price' => 'nullable|numeric|min:0',
                'buy_vat_id' => 'nullable|exists:vats,id',
                'buy_withholding_id' => 'nullable|exists:withholdings,id',
                'buy_description' => 'nullable|string',
                'sale_price' => 'required|numeric|min:0',
                'sale_vat_id' => 'nullable|exists:vats,id',
                'sale_withholding_id' => 'nullable|exists:withholdings,id',
                'sale_description' => 'nullable|string',
                'minimum_quantity' => 'nullable|integer|min:0',
                'maximum_quantity' => 'nullable|integer|min:0',
            ]);

            $validated['date_create'] = now();

            $product = Product::create($validated);

            // Handle sub-units if provided
            if ($request->has('sub_units')) {
                foreach ($request->sub_units as $subUnit) {
                    $product->subUnits()->create([
                        'serial_number' => $subUnit['serial_number'] ?? null,
                        'name' => $subUnit['name'] ?? null,
                        'buy_price' => $subUnit['buy_price'] ?? 0,
                        'sale_price' => $subUnit['sale_price'] ?? 0,
                        'quantity_of_minimum_unit' => $subUnit['quantity_of_minimum_unit'] ?? null,
                    ]);
                }
            }

            DB::commit();

            // Always return JSON response for Livewire
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully.',
                'product' => $product->load(['type', 'group', 'status', 'subUnits'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['type', 'group', 'status', 'subUnits', 'inventories']);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $types = ProductType::all();
        $groups = ProductGroup::all();
        $statuses = ProductStatus::all();
        $vats = Vat::all();
        $withholdings = Withholding::all();
        $product->load('subUnits');

        return view('product.edit', compact('product', 'types', 'groups', 'statuses', 'vats', 'withholdings'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'product_type_id' => 'required|exists:product_types,id',
                'product_group_id' => 'required|exists:product_groups,id',
                'product_status_id' => 'required|exists:product_statuses,id',
                'sku_number' => 'nullable|string|max:50',
                'serial_number' => 'nullable|string|max:150',
                'name' => 'required|string|max:150',
                'product_cover_img' => 'nullable|string',
                'unit_name' => 'required|string|max:80',
                'buy_price' => 'required|numeric|min:0',
                'buy_vat_id' => 'nullable|exists:vats,id',
                'buy_withholding_id' => 'nullable|exists:withholdings,id',
                'buy_description' => 'nullable|string',
                'sale_price' => 'required|numeric|min:0',
                'sale_vat_id' => 'nullable|exists:vats,id',
                'sale_withholding_id' => 'nullable|exists:withholdings,id',
                'sale_description' => 'nullable|string',
                'minimum_quantity' => 'nullable|integer|min:0',
                'maximum_quantity' => 'nullable|integer|min:0',
            ]);

            $product->update($validated);

            // Handle sub-units
            if ($request->has('sub_units')) {
                // Delete existing sub-units not in the request
                $existingIds = collect($request->sub_units)->pluck('id')->filter();
                $product->subUnits()->whereNotIn('id', $existingIds)->delete();

                // Update or create sub-units
                foreach ($request->sub_units as $subUnit) {
                    if (isset($subUnit['id'])) {
                        $product->subUnits()->where('id', $subUnit['id'])->update([
                            'serial_number' => $subUnit['serial_number'] ?? null,
                            'name' => $subUnit['name'] ?? null,
                            'buy_price' => $subUnit['buy_price'] ?? 0,
                            'sale_price' => $subUnit['sale_price'] ?? 0,
                            'quantity_of_minimum_unit' => $subUnit['quantity_of_minimum_unit'] ?? null,
                        ]);
                    } else {
                        $product->subUnits()->create([
                            'serial_number' => $subUnit['serial_number'] ?? null,
                            'name' => $subUnit['name'] ?? null,
                            'buy_price' => $subUnit['buy_price'] ?? 0,
                            'sale_price' => $subUnit['sale_price'] ?? 0,
                            'quantity_of_minimum_unit' => $subUnit['quantity_of_minimum_unit'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'product' => $product->load(['type', 'group', 'status', 'subUnits'])
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating product: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Delete the product and its sub-units
            $product->subUnits()->delete();
            $product->delete();

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully.'
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting product: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Get product details for AJAX requests.
     */
    public function getProductDetails(Product $product)
    {
        $product->load(['type', 'group', 'status', 'subUnits', 'inventories']);
        return response()->json($product);
    }
} 