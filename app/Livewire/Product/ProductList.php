<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Http\Controllers\ProductController;

class ProductList extends Component
{
    public $products;
    public $selectedProduct;
    protected $productController;

    public function mount()
    {
        $this->productController = new ProductController();
        $this->loadProducts();
    }

    public function loadProducts()
    {
        //$this->products = $this->productController->index()->getData()['products'];
        $this->products = $this->productController->index();
    }

    public function selectProduct($productId)
    {
        $product = Product::with(['type', 'group', 'status', 'subUnits', 'inventories'])
            ->find($productId);
            
        if ($product) {
            $this->selectedProduct = $product;
            $this->dispatch('productSelected', $this->selectedProduct);
        }
    }

    public function deleteProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $response = $this->productController->destroy($product);
            if ($response->getData()->success) {
                $this->loadProducts();
                $this->dispatch('productDeleted');
            }
        }
    }

    public function render()
    {
        return view('livewire.product.product-list');
    }
}