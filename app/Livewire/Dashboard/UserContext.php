<?php

namespace App\Livewire\Dashboard;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserContext extends Component
{
    public array $userInfo = [];
    public array $openTasks = [];
    public array $quickActions = [];
    public ?string $status = null;
    public ?string $lastLoginHuman = null;

    private DashboardService $dashboard;

    public function boot(DashboardService $dashboard): void
    {
        $this->dashboard = $dashboard;
    }

    public function mount(): void
    {
        $this->loadContext();
    }

    public function refreshContext(): void
    {
        $this->loadContext();
    }

    public function render()
    {
        return view('livewire.dashboard.user-context');
    }

    private function loadContext(): void
    {
        $user = Auth::user()?->loadMissing('roles', 'status', 'type', 'profile');

        if (! $user) {
            $this->userInfo = [];
            $this->openTasks = [];
            $this->quickActions = [];
            $this->status = null;
            $this->lastLoginHuman = null;
            return;
        }

        $context = $this->dashboard->getUserContext($user);
        $this->userInfo = $context['user'] ?? [];
        $this->openTasks = $context['open_tasks'] ?? [];
        $this->status = $context['status'] ?? null;

        $lastLogin = $context['last_login'] ?? null;
        if ($lastLogin instanceof Carbon) {
            $this->lastLoginHuman = $lastLogin->diffForHumans();
        } elseif (is_string($lastLogin) && ! empty($lastLogin)) {
            try {
                $this->lastLoginHuman = Carbon::parse($lastLogin)->diffForHumans();
            } catch (\Throwable $exception) {
                $this->lastLoginHuman = null;
            }
        } else {
            $this->lastLoginHuman = null;
        }

        $this->quickActions = $this->dashboard->getQuickActions($user);
    }
}
