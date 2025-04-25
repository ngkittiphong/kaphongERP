<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class ProductDetail extends Component
{
    public $product;
    public $productId;

    protected $listeners = [
        'ProductSelected' => 'loadProduct',
        'refreshComponent' => '$refresh',
        'createProduct' => 'createProduct',
        'deleteProduct' => 'deleteProduct'
    ];

    public function mount($productId = null)
    {
        if ($productId) {
            $this->loadProduct($productId);
        }
    }

    public function loadProduct($productId)
    {
        $this->productId = $productId;
        $this->product = Product::with([
            'type',
            'group',
            'status',
            'subUnits',
            'inventories'
        ])->find($productId);
    }

    public function render()
    {
        return view('livewire.product.product-detail');
    }
} 