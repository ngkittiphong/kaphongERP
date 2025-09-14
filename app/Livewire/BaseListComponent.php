<?php

namespace App\Livewire;

use Livewire\Component;

abstract class BaseListComponent extends Component
{
    public $items;
    public $selectedItem;
    protected $controller;
    protected $model;
    protected $itemName;
    protected $eventPrefix;

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function mount()
    {
        $this->controller = $this->getController();
        $this->model = $this->getModel();
        $this->itemName = $this->getItemName();
        $this->eventPrefix = $this->getEventPrefix();
        $this->loadItems();
    }

    public function refreshList()
    {
        \Log::info("🔄 Refreshing list for: " . $this->eventPrefix);
        
        // Ensure controller is initialized
        if (!$this->controller) {
            \Log::info("🔄 Controller not initialized, reinitializing...");
            $this->controller = $this->getController();
        }
        
        $this->loadItems();
    }

    abstract protected function getController();
    abstract protected function getModel();
    abstract protected function getItemName();
    abstract protected function getEventPrefix();
    abstract protected function getViewName();


    public function loadItems()
    {
        // Ensure controller is initialized
        if (!$this->controller) {
            \Log::info("🔄 Controller not initialized in loadItems, reinitializing...");
            $this->controller = $this->getController();
        }
        
        $this->items = $this->controller->index();
        $this->dispatch($this->eventPrefix . 'ListUpdated');
    }

    public function selectItem($itemId)
    {
        \Log::info("selectItem called with itemId: {$itemId}");
        \Log::info("Model class: " . $this->model);
        \Log::info("Model type: " . gettype($this->model));
        
        // Define relationships based on model type
        $relationships = [];
        
        if ($this->model === \App\Models\Product::class) {
            $relationships = ['type', 'group', 'status', 'subUnits', 'inventories'];
        } elseif ($this->model === \App\Models\Warehouse::class) {
            $relationships = ['branch', 'inventories'];
        } elseif ($this->model === \App\Models\Branch::class) {
            $relationships = ['company', 'warehouses'];
        } else {
            // Default relationships for other models
            $relationships = [];
        }
        
        \Log::info("Relationships to load: " . json_encode($relationships));
        
        try {
            // Use the model class directly
            $modelClass = $this->model;
            $item = $modelClass::with($relationships)->find($itemId);
            \Log::info("Item found: " . ($item ? 'Yes' : 'No'));
                
            if ($item) {
                $this->selectedItem = $item;
                $this->dispatch($this->eventPrefix . 'Selected', $this->selectedItem);
            }
        } catch (\Exception $e) {
            \Log::error("Error in selectItem: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }

    public function deleteItem($itemId)
    {
        $item = $this->model::find($itemId);
        if ($item) {
            $response = $this->controller->destroy($item);
            if ($response->getData()->success) {
                $this->loadItems();
                $this->dispatch($this->eventPrefix . 'Deleted');
            }
        }
    }

    public function render()
    {
        return view($this->getViewName());
    }
}
