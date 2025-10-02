<?php

namespace App\Livewire\Product;

use App\Livewire\BaseListComponent;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;

class ProductList extends BaseListComponent
{
    protected $listeners = [
        'refreshProductList' => 'refreshList',
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

    /**
     * Force refresh the product list with fresh data from database
     */
    public function refreshList()
    {
        \Log::info("🔄 ProductList: Refreshing product list with fresh data");
        
        // Clear any potential caching
        if (method_exists($this, 'clearCache')) {
            $this->clearCache();
        }
        
        // Force reload items from database
        $this->loadItems();
        
        \Log::info("🔄 ProductList: Product list refreshed successfully", [
            'products_count' => $this->items ? $this->items->count() : 0
        ]);
    }

    public function getStatusColor($statusName)
    {
        return match($statusName) {
            'Active' => 'success',
            'Inactive' => 'warning',
            'Discontinued' => 'danger',
            'Delete' => 'danger',
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
            'Delete' => 'cross2',
            'Draft' => 'pencil3',
            default => 'question'
        };
    }
}