<?php

namespace App\Livewire\Dashboard;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InventoryHealthPanel extends Component
{
    public array $topMovers = [];
    public array $lowStock = [];
    public array $branchCapacity = [];
    public array $aging = [];

    private DashboardService $dashboard;

    public function boot(DashboardService $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public function mount(): void
    {
        $this->loadData();
    }

    public function refreshPanel(): void
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.dashboard.inventory-health-panel');
    }

    private function loadData(): void
    {
        $user = Auth::user()?->loadMissing('roles', 'status', 'type', 'profile');

        if (! $user) {
            $this->topMovers = [];
            $this->lowStock = [];
            $this->branchCapacity = [];
            $this->aging = [];
            return;
        }

        $data = $this->dashboard->getInventoryHealth($user);
        $this->topMovers = $data['top_movers'] ?? [];
        $this->lowStock = $data['low_stock'] ?? [];
        $this->branchCapacity = $data['branch_capacity'] ?? [];
        $this->aging = $data['aging'] ?? [];
    }
}
