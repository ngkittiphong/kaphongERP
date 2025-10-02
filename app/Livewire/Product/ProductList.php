<?php

namespace App\Livewire\Product;

use App\Livewire\BaseListComponent;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;

class ProductList extends BaseListComponent
{
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'productListUpdated' => 'refreshList',
        'refreshProductList' => '$refresh',
    ];

    protected function getController()
    {
        // Use Laravel's service container to resolve dependencies
        return app(ProductController::class);
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

    public function getStatusColor($statusName)
    {
        return match($statusName) {
            'Active' => 'success',
            'Inactive' => 'warning',
            'Discontinued' => 'danger',
            'Draft' => 'secondary',
            default => 'secondary'
        };
    }

    public function getStatusIcon($statusName)
    {
        return match($statusName) {
            'Active' => 'checkmark3',
            'Inactive' => 'pause2',
            'Discontinued' => 'cross2',
            'Draft' => 'pencil3',
            default => 'question'
        };
    }
}