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
        
        // Check if controller is initialized
        if (!$this->controller) {
            \Log::warning("⚠️ Controller not initialized, skipping refresh");
            return;
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
        if (!$this->controller) {
            \Log::warning("⚠️ Controller not initialized in loadItems");
            return;
        }
        
        $this->items = $this->controller->index();
        $this->dispatch($this->eventPrefix . 'ListUpdated');
    }

    public function selectItem($itemId)
    {
        $item = $this->model::with(['type', 'group', 'status', 'subUnits', 'inventories'])
            ->find($itemId);
            
        if ($item) {
            $this->selectedItem = $item;
            $this->dispatch($this->eventPrefix . 'Selected', $this->selectedItem);
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
