<?php

namespace App\Livewire\Dashboard;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KpiSummary extends Component
{
    public array $totals = [];
    public string $currencyCode = '';
    public ?string $lastUpdatedHuman = null;

    private DashboardService $dashboard;

    public function boot(DashboardService $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public function mount(): void
    {
        $this->loadMetrics();
    }

    public function refreshMetrics(): void
    {
        $this->loadMetrics();
    }

    public function render()
    {
        return view('livewire.dashboard.kpi-summary');
    }

    private function loadMetrics(): void
    {
        $user = Auth::user()?->loadMissing('roles', 'status', 'type', 'profile');

        if (! $user) {
            $this->totals = [];
            $this->currencyCode = '';
            $this->lastUpdatedHuman = null;
            return;
        }

        $data = $this->dashboard->getKpiSummary($user);
        $this->totals = $data['totals'] ?? [];
        $this->currencyCode = $data['currency'] ?? '';
        $lastUpdated = $data['last_updated'] ?? null;
        $this->lastUpdatedHuman = $lastUpdated instanceof Carbon
            ? $lastUpdated->diffForHumans()
            : null;
    }
}
