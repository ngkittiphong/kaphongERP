<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionManager extends Component
{
    public $roles = [];
    public $permissions = [];
    public $users = [];
    public $userRoles = [];

    public $selectedRoleId;
    public $roleName = '';
    public $selectedPermissions = [];

    public $confirmingDeletion = false;
    public $protectedRoles = ['admin1', 'admin2', 'admin3'];

    public function mount(): void
    {
        $this->loadAll();
    }

    public function render()
    {
        return view('livewire.user.permission-manager');
    }

    public function selectRole(int $roleId): void
    {
        $role = $this->roles->firstWhere('id', $roleId) ?? Role::find($roleId);

        if (! $role) {
            return;
        }

        $this->selectedRoleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions()->pluck('name')->toArray();
        $this->confirmingDeletion = false;
    }

    public function startCreate(): void
    {
        $this->selectedRoleId = null;
        $this->roleName = '';
        $this->selectedPermissions = [];
        $this->confirmingDeletion = false;
    }

    public function saveRole(): void
    {
        $validated = $this->validate($this->validationRules(), [], [
            'roleName' => __('Role name'),
            'selectedPermissions' => __('Permissions'),
        ]);

        if ($this->selectedRoleId) {
            $role = Role::findOrFail($this->selectedRoleId);
            $role->name = $validated['roleName'];
            $role->save();
        } else {
            $role = Role::create([
                'name' => $validated['roleName'],
                'guard_name' => 'web',
            ]);
            $this->selectedRoleId = $role->id;
        }

        $role->syncPermissions($validated['selectedPermissions'] ?? []);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->loadAll();
        $this->selectRole($role->id);

        session()->flash('permissionMessage', __('Role saved successfully.'));
    }

    public function confirmDelete(): void
    {
        if (! $this->selectedRoleId) {
            return;
        }

        $role = Role::find($this->selectedRoleId);
        if ($role && in_array($role->name, $this->protectedRoles, true)) {
            session()->flash('permissionError', __('This role is protected and cannot be deleted.'));
            return;
        }

        $this->confirmingDeletion = true;
    }

    public function deleteRole(): void
    {
        if (! $this->selectedRoleId) {
            return;
        }

        $role = Role::find($this->selectedRoleId);

        if (! $role) {
            return;
        }

        if (in_array($role->name, $this->protectedRoles, true)) {
            session()->flash('permissionError', __('This role is protected and cannot be deleted.'));
            return;
        }

        $role->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->startCreate();
        $this->loadAll();

        session()->flash('permissionMessage', __('Role deleted.'));
    }

    public function updatedUserRoles($value, $key): void
    {
        $userId = (int) $key;
        $roles = $value ?? [];

        $this->syncUserRoles($userId, $roles);
    }

    public function syncUserRoles(int $userId, array $roles): void
    {
        $user = User::find($userId);

        if (! $user) {
            return;
        }

        if ($user->username === 'admin1' && ! in_array('admin1', $roles, true)) {
            $roles[] = 'admin1';
        }

        $user->syncRoles($roles);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->loadAll();

        session()->flash('permissionMessage', __('User roles updated.'));
    }

    private function loadAll(): void
    {
        $this->roles = Role::with('permissions')->orderBy('name')->get();
        $this->permissions = Permission::orderBy('name')->get();
        $this->users = User::with('roles')->orderBy('username')->get();

        $this->userRoles = [];
        foreach ($this->users as $user) {
            $this->userRoles[$user->id] = $user->roles->pluck('name')->toArray();
        }
    }

    private function validationRules(): array
    {
        return [
            'roleName' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique('roles', 'name')->ignore($this->selectedRoleId),
            ],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['string', Rule::exists('permissions', 'name')],
        ];
    }
}
