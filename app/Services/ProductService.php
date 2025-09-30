<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductGroup;
use App\Models\ProductStatus;
use App\Models\Vat;
use App\Models\Withholding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Get all products with relationships
     */
    public function getAllProducts()
    {
        Log::info('ProductService@getAllProducts: Starting to retrieve all products');
        $products = Product::with(['type', 'group', 'status'])->get();
        Log::info('ProductService@getAllProducts: Retrieved products successfully', [
            'count' => $products->count(),
            'product_ids' => $products->pluck('id')->toArray()
        ]);
        return $products;
    }

    /**
     * Get product creation form data
     */
    public function getCreateFormData()
    {
        Log::info('ProductService@getCreateFormData: Starting to retrieve form data');
        $formData = [
            'types' => ProductType::all(),
            'groups' => ProductGroup::all(),
            'statuses' => ProductStatus::all(),
            'vats' => Vat::all(),
            'withholdings' => Withholding::all(),
        ];
        Log::info('ProductService@getCreateFormData: Form data retrieved successfully', [
            'types_count' => $formData['types']->count(),
            'groups_count' => $formData['groups']->count(),
            'statuses_count' => $formData['statuses']->count(),
            'vats_count' => $formData['vats']->count(),
            'withholdings_count' => $formData['withholdings']->count()
        ]);
        return $formData;
    }

    /**
     * Get product edit form data
     */
    public function getEditFormData(Product $product)
    {
        Log::info('ProductService@getEditFormData: Starting to retrieve edit form data', [
            'product_id' => $product->id,
            'product_name' => $product->name
        ]);
        $formData = [
            'product' => $product->load('subUnits'),
            'types' => ProductType::all(),
            'groups' => ProductGroup::all(),
            'statuses' => ProductStatus::all(),
            'vats' => Vat::all(),
            'withholdings' => Withholding::all(),
        ];
        Log::info('ProductService@getEditFormData: Edit form data retrieved successfully', [
            'product_id' => $product->id,
            'types_count' => $formData['types']->count(),
            'groups_count' => $formData['groups']->count(),
            'statuses_count' => $formData['statuses']->count(),
            'vats_count' => $formData['vats']->count(),
            'withholdings_count' => $formData['withholdings']->count(),
            'sub_units_count' => $formData['product']->subUnits->count()
        ]);
        return $formData;
    }

    /**
     * Create a new product
     */
    public function createProduct(array $data)
    {
        try {
            Log::info('ProductService@createProduct: Starting product creation', [
                'data_keys' => array_keys($data),
                'product_group_name' => $data['product_group_name'] ?? 'not_provided',
                'product_name' => $data['name'] ?? 'not_provided'
            ]);

            DB::beginTransaction();
            Log::info('ProductService@createProduct: Database transaction started');

            // Validate required fields
            Log::info('ProductService@createProduct: Starting validation');
            $this->validateProductData($data, 'create');
            Log::info('ProductService@createProduct: Validation passed');

            // Handle product group
            Log::info('ProductService@createProduct: Handling product group');
            $productGroup = $this->handleProductGroup($data);
            $data['product_group_id'] = $productGroup->id;
            unset($data['product_group_name']);
            Log::info('ProductService@createProduct: Product group handled', [
                'product_group_id' => $productGroup->id,
                'product_group_name' => $productGroup->name
            ]);

            // Set creation date
            $data['date_create'] = now();

            // Create the product
            Log::info('ProductService@createProduct: Creating product in database');
            $product = Product::create($data);
            Log::info('ProductService@createProduct: Product created in database', [
                'product_id' => $product->id,
                'product_name' => $product->name
            ]);

            // Handle sub-units if provided
            if (isset($data['sub_units']) && is_array($data['sub_units'])) {
                Log::info('ProductService@createProduct: Creating sub-units', [
                    'sub_units_count' => count($data['sub_units'])
                ]);
                $this->createSubUnits($product, $data['sub_units']);
                Log::info('ProductService@createProduct: Sub-units created successfully');
            }

            DB::commit();
            Log::info('ProductService@createProduct: Database transaction committed');

            $productWithRelations = $product->load(['type', 'group', 'status', 'subUnits']);
            Log::info('ProductService@createProduct: Product created successfully', [
                'product_id' => $productWithRelations->id,
                'product_name' => $productWithRelations->name,
                'product_group' => $productWithRelations->group->name,
                'product_type' => $productWithRelations->type->name,
                'product_status' => $productWithRelations->status->name,
                'sub_units_count' => $productWithRelations->subUnits->count()
            ]);

            return [
                'success' => true,
                'message' => 'Product created successfully.',
                'product' => $productWithRelations
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ProductService@createProduct: Error creating product', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'data_keys' => array_keys($data)
            ]);
            
            return [
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update an existing product
     */
    public function updateProduct(Product $product, array $data)
    {
        try {
            Log::info('ProductService@updateProduct: Starting product update', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'data_keys' => array_keys($data),
                'product_group_id' => $data['product_group_id'] ?? 'not_provided'
            ]);

            DB::beginTransaction();
            Log::info('ProductService@updateProduct: Database transaction started');

            // Validate required fields
            Log::info('ProductService@updateProduct: Starting validation');
            $this->validateProductData($data, 'update');
            Log::info('ProductService@updateProduct: Validation passed');

            // Update the product
            Log::info('ProductService@updateProduct: Updating product in database');
            $product->update($data);
            Log::info('ProductService@updateProduct: Product updated in database', [
                'product_id' => $product->id,
                'updated_fields' => array_keys($data)
            ]);

            // Handle sub-units if provided
            if (isset($data['sub_units']) && is_array($data['sub_units'])) {
                Log::info('ProductService@updateProduct: Updating sub-units', [
                    'sub_units_count' => count($data['sub_units'])
                ]);
                $this->updateSubUnits($product, $data['sub_units']);
                Log::info('ProductService@updateProduct: Sub-units updated successfully');
            }

            DB::commit();
            Log::info('ProductService@updateProduct: Database transaction committed');

            $productWithRelations = $product->load(['type', 'group', 'status', 'subUnits']);
            Log::info('ProductService@updateProduct: Product updated successfully', [
                'product_id' => $productWithRelations->id,
                'product_name' => $productWithRelations->name,
                'product_group' => $productWithRelations->group->name,
                'product_type' => $productWithRelations->type->name,
                'product_status' => $productWithRelations->status->name,
                'sub_units_count' => $productWithRelations->subUnits->count()
            ]);

            return [
                'success' => true,
                'message' => 'Product updated successfully.',
                'product' => $productWithRelations
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ProductService@updateProduct: Error updating product', [
                'product_id' => $product->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'data_keys' => array_keys($data)
            ]);
            
            return [
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Soft delete a product (change status to inactive)
     */
    public function softDeleteProduct(Product $product)
    {
        try {
            Log::info('ProductService@softDeleteProduct: Starting product soft delete', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'current_status' => $product->status ? $product->status->name : 'unknown'
            ]);

            DB::beginTransaction();
            Log::info('ProductService@softDeleteProduct: Database transaction started');

            // Find the "Inactive" status
            Log::info('ProductService@softDeleteProduct: Looking for Inactive status');
            $inactiveStatus = ProductStatus::where('name', 'Inactive')->first();
            
            if (!$inactiveStatus) {
                Log::error('ProductService@softDeleteProduct: Inactive status not found');
                throw new \Exception('Inactive status not found');
            }
            Log::info('ProductService@softDeleteProduct: Inactive status found', [
                'inactive_status_id' => $inactiveStatus->id
            ]);

            // Change product status to inactive
            Log::info('ProductService@softDeleteProduct: Updating product status to inactive');
            $product->update(['product_status_id' => $inactiveStatus->id]);
            Log::info('ProductService@softDeleteProduct: Product status updated successfully');

            DB::commit();
            Log::info('ProductService@softDeleteProduct: Database transaction committed');

            Log::info('ProductService@softDeleteProduct: Product soft deleted successfully', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'new_status' => 'Inactive'
            ]);

            return [
                'success' => true,
                'message' => 'Product status changed to inactive successfully.'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ProductService@softDeleteProduct: Error changing product status', [
                'product_id' => $product->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error changing product status: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get product details with all relationships
     */
    public function getProductDetails(Product $product)
    {
        Log::info('ProductService@getProductDetails: Getting product details', [
            'product_id' => $product->id,
            'product_name' => $product->name
        ]);
        $productWithRelations = $product->load(['type', 'group', 'status', 'subUnits', 'inventories']);
        Log::info('ProductService@getProductDetails: Product details retrieved successfully', [
            'product_id' => $productWithRelations->id,
            'has_type' => $productWithRelations->relationLoaded('type'),
            'has_group' => $productWithRelations->relationLoaded('group'),
            'has_status' => $productWithRelations->relationLoaded('status'),
            'has_sub_units' => $productWithRelations->relationLoaded('subUnits'),
            'has_inventories' => $productWithRelations->relationLoaded('inventories'),
            'sub_units_count' => $productWithRelations->subUnits->count(),
            'inventories_count' => $productWithRelations->inventories->count()
        ]);
        return $productWithRelations;
    }

    /**
     * Find product by ID
     */
    public function findProduct(int $id)
    {
        Log::info('ProductService@findProduct: Looking for product', ['product_id' => $id]);
        $product = Product::find($id);
        if ($product) {
            Log::info('ProductService@findProduct: Product found', [
                'product_id' => $product->id,
                'product_name' => $product->name
            ]);
            return $product->load(['type', 'group', 'status', 'subUnits']);
        } else {
            Log::warning('ProductService@findProduct: Product not found', ['product_id' => $id]);
        }
        return null;
    }

    /**
     * Search products by name or SKU
     */
    public function searchProducts(string $query)
    {
        Log::info('ProductService@searchProducts: Starting product search', ['query' => $query]);
        $products = Product::with(['type', 'group', 'status'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('sku_number', 'like', "%{$query}%")
            ->get();
        Log::info('ProductService@searchProducts: Search completed', [
            'query' => $query,
            'results_count' => $products->count(),
            'product_ids' => $products->pluck('id')->toArray()
        ]);
        return $products;
    }

    /**
     * Get products by status
     */
    public function getProductsByStatus(string $statusName)
    {
        Log::info('ProductService@getProductsByStatus: Getting products by status', ['status_name' => $statusName]);
        $status = ProductStatus::where('name', $statusName)->first();
        
        if (!$status) {
            Log::warning('ProductService@getProductsByStatus: Status not found', ['status_name' => $statusName]);
            return collect();
        }

        $products = Product::with(['type', 'group', 'status'])
            ->where('product_status_id', $status->id)
            ->get();
        
        Log::info('ProductService@getProductsByStatus: Products retrieved by status', [
            'status_name' => $statusName,
            'status_id' => $status->id,
            'products_count' => $products->count(),
            'product_ids' => $products->pluck('id')->toArray()
        ]);
        
        return $products;
    }

    /**
     * Get products by group
     */
    public function getProductsByGroup(int $groupId)
    {
        Log::info('ProductService@getProductsByGroup: Getting products by group', ['group_id' => $groupId]);
        $products = Product::with(['type', 'group', 'status'])
            ->where('product_group_id', $groupId)
            ->get();
        Log::info('ProductService@getProductsByGroup: Products retrieved by group', [
            'group_id' => $groupId,
            'products_count' => $products->count(),
            'product_ids' => $products->pluck('id')->toArray()
        ]);
        return $products;
    }

    /**
     * Validate product data
     */
    private function validateProductData(array $data, string $operation = 'create')
    {
        $rules = [
            'name' => 'required|string|max:255',
            'sku_number' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
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
            'product_cover_img' => 'nullable|string',
        ];

        if ($operation === 'create') {
            $rules['product_group_name'] = 'required|string|max:255';
        } else {
            $rules['product_group_id'] = 'required|exists:product_groups,id';
        }

        $validator = validator($data, $rules);
        
        if ($validator->fails()) {
            throw new \Exception('Validation failed: ' . implode(', ', $validator->errors()->all()));
        }
    }

    /**
     * Handle product group creation or retrieval
     */
    private function handleProductGroup(array $data)
    {
        if (isset($data['product_group_name'])) {
            Log::info('ProductService@handleProductGroup: Handling product group', [
                'product_group_name' => $data['product_group_name']
            ]);
            
            $productGroup = ProductGroup::firstOrCreate(
                ['name' => $data['product_group_name']],
                ['name' => $data['product_group_name']]
            );

            Log::info('ProductService@handleProductGroup: Product group handled', [
                'product_group_id' => $productGroup->id,
                'product_group_name' => $productGroup->name,
                'was_created' => $productGroup->wasRecentlyCreated
            ]);
            
            return $productGroup;
        }

        Log::error('ProductService@handleProductGroup: Product group name is required');
        throw new \Exception('Product group name is required');
    }

    /**
     * Create sub-units for a product
     */
    private function createSubUnits(Product $product, array $subUnits)
    {
        Log::info('ProductService@createSubUnits: Creating sub-units', [
            'product_id' => $product->id,
            'sub_units_count' => count($subUnits)
        ]);
        
        foreach ($subUnits as $index => $subUnit) {
            Log::info('ProductService@createSubUnits: Creating sub-unit', [
                'product_id' => $product->id,
                'sub_unit_index' => $index,
                'sub_unit_name' => $subUnit['name'] ?? 'unnamed'
            ]);
            
            $product->subUnits()->create([
                'serial_number' => $subUnit['serial_number'] ?? null,
                'name' => $subUnit['name'] ?? null,
                'buy_price' => $subUnit['buy_price'] ?? 0,
                'sale_price' => $subUnit['sale_price'] ?? 0,
                'quantity_of_minimum_unit' => $subUnit['quantity_of_minimum_unit'] ?? null,
            ]);
        }
        
        Log::info('ProductService@createSubUnits: Sub-units created successfully', [
            'product_id' => $product->id,
            'created_count' => count($subUnits)
        ]);
    }

    /**
     * Update sub-units for a product
     */
    private function updateSubUnits(Product $product, array $subUnits)
    {
        Log::info('ProductService@updateSubUnits: Updating sub-units', [
            'product_id' => $product->id,
            'sub_units_count' => count($subUnits)
        ]);
        
        // Delete existing sub-units not in the request
        $existingIds = collect($subUnits)->pluck('id')->filter();
        $deletedCount = $product->subUnits()->whereNotIn('id', $existingIds)->delete();
        
        Log::info('ProductService@updateSubUnits: Deleted old sub-units', [
            'product_id' => $product->id,
            'deleted_count' => $deletedCount
        ]);

        // Update or create sub-units
        foreach ($subUnits as $index => $subUnit) {
            if (isset($subUnit['id'])) {
                Log::info('ProductService@updateSubUnits: Updating existing sub-unit', [
                    'product_id' => $product->id,
                    'sub_unit_id' => $subUnit['id'],
                    'sub_unit_index' => $index
                ]);
                
                $product->subUnits()->where('id', $subUnit['id'])->update([
                    'serial_number' => $subUnit['serial_number'] ?? null,
                    'name' => $subUnit['name'] ?? null,
                    'buy_price' => $subUnit['buy_price'] ?? 0,
                    'sale_price' => $subUnit['sale_price'] ?? 0,
                    'quantity_of_minimum_unit' => $subUnit['quantity_of_minimum_unit'] ?? null,
                ]);
            } else {
                Log::info('ProductService@updateSubUnits: Creating new sub-unit', [
                    'product_id' => $product->id,
                    'sub_unit_index' => $index,
                    'sub_unit_name' => $subUnit['name'] ?? 'unnamed'
                ]);
                
                $product->subUnits()->create([
                    'serial_number' => $subUnit['serial_number'] ?? null,
                    'name' => $subUnit['name'] ?? null,
                    'buy_price' => $subUnit['buy_price'] ?? 0,
                    'sale_price' => $subUnit['sale_price'] ?? 0,
                    'quantity_of_minimum_unit' => $subUnit['quantity_of_minimum_unit'] ?? null,
                ]);
            }
        }
        
        Log::info('ProductService@updateSubUnits: Sub-units updated successfully', [
            'product_id' => $product->id,
            'processed_count' => count($subUnits)
        ]);
    }
}