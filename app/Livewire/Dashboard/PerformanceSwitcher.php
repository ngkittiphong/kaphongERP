<?php

namespace App\Livewire\Dashboard;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PerformanceSwitcher extends Component
{
    public string $timeframe = 'monthly';
    public array $series = [];
    public array $tableRows = [];

    private DashboardService $dashboard;
    private array $allowedTimeframes = ['daily', 'weekly', 'monthly'];

    public function boot(DashboardService $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public function mount(string $timeframe = 'monthly'): void
    {
        if (in_array($timeframe, $this->allowedTimeframes, true)) {
            $this->timeframe = $timeframe;
        }

        $this->loadSeries();
    }

    public function setTimeframe(string $timeframe): void
    {
        if (! in_array($timeframe, $this->allowedTimeframes, true)) {
            return;
        }

        $this->timeframe = $timeframe;
        $this->loadSeries();
    }

    public function render()
    {
        return view('livewire.dashboard.performance-switcher', [
            'timeframes' => $this->allowedTimeframes,
        ]);
    }

    private function loadSeries(): void
    {
        $user = Auth::user()?->loadMissing('roles', 'status', 'type', 'profile');

        if (! $user) {
            $this->series = [];
            return;
        }

        $data = $this->dashboard->getPerformanceSeries($user, $this->timeframe);
        $this->series = $data['series'] ?? [];
        $this->tableRows = $this->buildTableRows($this->series);
    }

    private function buildTableRows(array $series): array
    {
        $rows = [];
        $order = [];

        foreach ($series as $group) {
            foreach ($group['points'] ?? [] as $index => $point) {
                $period = $point['period'] ?? '';
                if (! isset($rows[$period])) {
                    $rows[$period] = [
                        'period' => $period,
                    ];
                    $order[] = $period;
                }
                $rows[$period][$group['key']] = $point['value'] ?? 0;
            }
        }

        return collect($order)->map(function ($period) use ($rows) {
            return $rows[$period];
        })->toArray();
    }
}
