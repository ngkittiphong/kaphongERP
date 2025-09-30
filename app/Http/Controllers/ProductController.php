<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = $this->productService->getAllProducts();
        return $products; //view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $formData = $this->productService->getCreateFormData();
        return view('product.create', $formData);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $result = $this->productService->createProduct($request->all());
        
        if ($result['success']) {
            return response()->json($result);
        } else {
            return response()->json($result, 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product = $this->productService->getProductDetails($product);
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $formData = $this->productService->getEditFormData($product);
        return view('product.edit', $formData);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $result = $this->productService->updateProduct($product, $request->all());
        
        if ($request->ajax()) {
            if ($result['success']) {
                return response()->json($result);
            } else {
                return response()->json($result, 500);
            }
        }

        if ($result['success']) {
            return redirect()->route('menu.menu_product')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message'])
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage (change status to inactive).
     */
    public function destroy(Product $product)
    {
        $result = $this->productService->softDeleteProduct($product);
        
        if (request()->ajax()) {
            if ($result['success']) {
                return response()->json($result);
            } else {
                return response()->json($result, 500);
            }
        }

        if ($result['success']) {
            return redirect()->route('menu.menu_product')
                ->with('success', $result['message']);
        } else {
            return redirect()->back()
                ->with('error', $result['message']);
        }
    }

    /**
     * Get product details for AJAX requests.
     */
    public function getProductDetails(Product $product)
    {
        $product = $this->productService->getProductDetails($product);
        return response()->json($product);
    }
}