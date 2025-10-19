<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">{{ __t('permissions.access_control', 'Access Control') }}</h5>
    </div>

    <div class="panel-body">
        @if (session()->has('permissionMessage'))
            <div class="alert alert-success">{{ session('permissionMessage') }}</div>
        @endif

        @if (session()->has('permissionError'))
            <div class="alert alert-danger">{{ session('permissionError') }}</div>
        @endif

        @if (! $user)
            <p class="text-muted">{{ __t('permissions.select_user_hint', 'Select a user to manage roles and permissions.') }}</p>
        @else
            <div class="alert alert-info">
                <strong>{{ __t('permissions.user_label', 'User:') }}</strong> {{ $user->username }} &mdash; {{ $user->email }}
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-semibold">{{ __t('permissions.roles', 'Roles') }}</h6>
                    <p class="text-muted text-size-small">{{ __t('permissions.assign_roles_hint', 'Assign one or more roles to this user.') }}</p>
                    <select class="form-control" multiple size="8" wire:model="assignedRoles" wire:key="roles-{{ $user->id }}" aria-describedby="access-role-help">
                        @foreach ($availableRoles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <div class="text-muted text-size-small m-t-10">
                        {{ __t('permissions.current_roles', 'Current roles:') }}
                        <span class="text-semibold">{{ implode(', ', $assignedRoles) ?: __t('permissions.none', 'None') }}</span>
                    </div>
                    <p id="access-role-help" class="text-muted text-size-small">
                        {{ __t('permissions.multi_select_help', 'Hold Ctrl (Cmd on Mac) to select or unselect multiple roles.') }}
                    </p>
                </div>

                <div class="col-md-6">
                    <h6 class="text-semibold">{{ __t('permissions.direct_permissions', 'Direct Permissions') }}</h6>
                    <p class="text-muted text-size-small">{{ __t('permissions.assign_permissions_hint', 'Grant or revoke permissions outside of role membership.') }}</p>
                    <select class="form-control" multiple size="8" wire:model="assignedPermissions" wire:key="perms-{{ $user->id }}" aria-describedby="access-permission-help">
                        @foreach ($availablePermissions as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-muted text-size-small m-t-10">
                        {{ __t('permissions.current_permissions', 'Current permissions:') }}
                        <span class="text-semibold">{{ implode(', ', $assignedPermissions) ?: __t('permissions.none', 'None') }}</span>
                    </div>
                    <p id="access-permission-help" class="text-muted text-size-small">
                        {{ __t('permissions.multi_select_permissions_help', 'Hold Ctrl (Cmd on Mac) to select or unselect multiple permissions.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
