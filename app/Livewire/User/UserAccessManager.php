<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserAccessManager extends Component
{
    public ?User $user = null;
    public ?int $userId = null;

    public Collection $availableRoles;
    public Collection $availablePermissions;

    public array $assignedRoles = [];
    public array $assignedPermissions = [];

    public array $protectedRoles = [];

    public function mount(?int $userId = null): void
    {
        $this->loadProtectedRoles();
        $this->refreshLookups();

        if ($userId) {
            $this->loadUser($userId);
        }
    }

    #[On('accessUserSelected')]
    public function loadUser(int $userId): void
    {
        $this->userId = $userId;
        $this->user = User::with(['roles', 'permissions'])->find($userId);

        if (! $this->user) {
            $this->reset(['assignedRoles', 'assignedPermissions']);
            session()->flash('permissionError', __('Selected user could not be found.'));
            return;
        }

        $this->refreshLookups();

        $this->assignedRoles = $this->user->roles->pluck('name')->toArray();
        $this->assignedPermissions = $this->user->permissions->pluck('name')->toArray();
    }

    public function updatedAssignedRoles(): void
    {
        if (! $this->user) {
            return;
        }

        $this->validate([
            'assignedRoles' => ['array'],
            'assignedRoles.*' => ['string', Rule::exists('roles', 'name')],
        ]);

        // // Ensure admin users keep their admin roles
        // if ($this->user->username === 'admin1' && ! in_array('super_admin', $this->assignedRoles, true)) {
        //     $this->assignedRoles[] = 'super_admin';
        // } elseif ($this->user->username === 'admin2' && ! in_array('product_admin', $this->assignedRoles, true)) {
        //     $this->assignedRoles[] = 'product_admin';
        // } elseif ($this->user->username === 'admin3' && ! in_array('warehouse_admin', $this->assignedRoles, true)) {
        //     $this->assignedRoles[] = 'warehouse_admin';
        // }

        $this->assignedRoles = array_values(array_unique($this->assignedRoles));

        $this->user->syncRoles($this->assignedRoles);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('permissionMessage', __('User roles updated.'));
        $this->loadUser($this->user->id);
    }

    public function updatedAssignedPermissions(): void
    {
        if (! $this->user) {
            return;
        }

        $this->validate([
            'assignedPermissions' => ['array'],
            'assignedPermissions.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        $this->assignedPermissions = array_values(array_unique($this->assignedPermissions));

        $this->user->syncPermissions($this->assignedPermissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('permissionMessage', __('User permissions updated.'));
        $this->loadUser($this->user->id);
    }

    public function render()
    {
        return view('livewire.user.user-access-manager');
    }

    private function loadProtectedRoles(): void
    {
        // Load admin roles from database
        $this->protectedRoles = Role::whereIn('name', ['super_admin'])
            ->pluck('name')
            ->toArray();
    }

    private function refreshLookups(): void
    {
        $this->availableRoles = Role::orderBy('name')->get();
        $this->availablePermissions = Permission::orderBy('name')->get();
    }
}
