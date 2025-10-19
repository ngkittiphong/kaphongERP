<?php

namespace App\Livewire\Dashboard;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActionAlerts extends Component
{
    public array $alerts = [];

    private DashboardService $dashboard;

    public function boot(DashboardService $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public function mount(): void
    {
        $this->loadAlerts();
    }

    public function refreshAlerts(): void
    {
        $this->loadAlerts();
    }

    public function render()
    {
        return view('livewire.dashboard.action-alerts');
    }

    private function loadAlerts(): void
    {
        $user = Auth::user()?->loadMissing('roles', 'status', 'type', 'profile');

        if (! $user) {
            $this->alerts = [];
            return;
        }

        $data = $this->dashboard->getActionAlerts($user);
        $this->alerts = $data['alerts'] ?? [];
    }
}
