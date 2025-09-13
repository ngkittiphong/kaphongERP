<?php

namespace App\Livewire\Product;

use App\Livewire\BaseListComponent;
use App\Models\Product;
use App\Http\Controllers\ProductController;

class ProductList extends BaseListComponent
{
    protected function getController()
    {
        return new ProductController();
    }

    protected function getModel()
    {
        return Product::class;
    }

    protected function getItemName()
    {
        return 'products';
    }

    protected function getEventPrefix()
    {
        return 'product';
    }

    protected function getViewName()
    {
        return 'livewire.product.product-list';
    }

    // Alias methods for backward compatibility
    public function getProductsProperty()
    {
        return $this->items;
    }

    public function getSelectedProductProperty()
    {
        return $this->selectedItem;
    }

    public function selectProduct($productId)
    {
        $this->selectItem($productId);
    }

    public function deleteProduct($productId)
    {
        $this->deleteItem($productId);
    }

    public function loadProducts()
    {
        $this->loadItems();
    }
}