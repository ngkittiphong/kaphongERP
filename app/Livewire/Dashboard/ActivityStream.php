<?php

namespace App\Livewire\Dashboard;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActivityStream extends Component
{
    public array $transfers = [];
    public array $stockChecks = [];
    public array $userEvents = [];

    private DashboardService $dashboard;

    public function boot(DashboardService $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public function mount(): void
    {
        $this->loadActivity();
    }

    public function refreshActivity(): void
    {
        $this->loadActivity();
    }

    public function render()
    {
        return view('livewire.dashboard.activity-stream');
    }

    private function loadActivity(): void
    {
        $user = Auth::user()?->loadMissing('roles', 'status', 'type', 'profile');

        if (! $user) {
            $this->transfers = [];
            $this->stockChecks = [];
            $this->userEvents = [];
            return;
        }

        $data = $this->dashboard->getActivityStream($user);

        $this->transfers = collect($data['transfers'] ?? [])
            ->map(function ($slip) {
                return [
                    'id' => $slip->id,
                    'number' => $slip->transfer_slip_number,
                    'status' => optional($slip->status)->name ?? __t('dashboard.labels.pending', 'Pending'),
                    'origin' => optional($slip->warehouseOrigin)->name ?? __t('dashboard.labels.unknown_warehouse', 'Unknown warehouse'),
                    'destination' => optional($slip->warehouseDestination)->name ?? __t('dashboard.labels.unknown_warehouse', 'Unknown warehouse'),
                    'updated_at' => optional($slip->updated_at)?->diffForHumans(),
                ];
            })
            ->toArray();

        $this->stockChecks = collect($data['stock_checks'] ?? [])
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'warehouse' => optional($report->warehouse)->name ?? __t('dashboard.labels.unknown_warehouse', 'Unknown warehouse'),
                    'status' => $report->closed
                        ? __t('dashboard.labels.closed', 'Closed')
                        : __t('dashboard.labels.open', 'Open'),
                    'performed_at' => optional($report->datetime_create)?->diffForHumans(),
                ];
            })
            ->toArray();

        $this->userEvents = collect($data['user_events'] ?? [])
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'username' => $event->username,
                    'name' => $event->profile->fullname_en ?? $event->profile->fullname_th ?? $event->username,
                    'status' => optional($event->status)->name ?? 'Active',
                    'occurred_at' => optional($event->updated_at)?->diffForHumans(),
                ];
            })
            ->toArray();
    }
}
