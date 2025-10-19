<div class="row">
    <div class="col-lg-4 col-md-5">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">{{ $selectedRoleId ? __('Edit Role') : __('Create Role') }}</h5>
            </div>
            <div class="panel-body">
                @if (session()->has('permissionMessage'))
                    <div class="alert alert-success">
                        {{ session('permissionMessage') }}
                    </div>
                @endif

                @if (session()->has('permissionError'))
                    <div class="alert alert-danger">
                        {{ session('permissionError') }}
                    </div>
                @endif

                <form wire:submit.prevent="saveRole">
                    <div class="form-group">
                        <label for="role-name">{{ __t('permissions.role_name', 'Role name') }}</label>
                        <input id="role-name" type="text" class="form-control" wire:model.defer="roleName" placeholder="{{ __t('permissions.role_name_placeholder', 'Enter role name') }}">
                        @error('roleName')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>{{ __t('permissions.permissions', 'Permissions') }}</label>
                        <p class="text-muted text-size-small">{{ __t('permissions.select_permissions_hint', 'Tick the permissions this role should grant.') }}</p>
                        <div class="permissions-list">
                            @foreach ($permissions as $permission)
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}">
                                        <span class="text-capitalize">{{ str_replace('menu.', '', $permission->name) }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedPermissions.*')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ $selectedRoleId ? __t('permissions.update_role', 'Update Role') : __t('permissions.create_role', 'Create Role') }}
                        </button>

                        <button type="button" class="btn btn-default" wire:click="startCreate">
                            {{ __t('common.clear', 'Clear') }}
                        </button>

                        @if ($selectedRoleId)
                            <button type="button" class="btn btn-danger" wire:click="confirmDelete">
                                {{ __t('common.delete', 'Delete') }}
                            </button>
                        @endif
                    </div>

                    @if ($confirmingDeletion)
                        <div class="alert alert-warning">
                            <p class="small">{{ __t('permissions.delete_role_warning', 'Are you sure you want to delete this role? This action cannot be undone.') }}</p>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-xs btn-danger" wire:click="deleteRole">{{ __t('permissions.confirm_delete', 'Yes, delete') }}</button>
                                <button type="button" class="btn btn-xs btn-default" wire:click="$set('confirmingDeletion', false)">{{ __t('common.cancel', 'Cancel') }}</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">{{ __t('permissions.existing_roles', 'Existing Roles') }}</h5>
            </div>
            <div class="list-group no-border">
                @forelse ($roles as $role)
                    <a href="#" class="list-group-item" wire:click.prevent="selectRole({{ $role->id }})">
                        <div class="media-body">
                            <span class="text-semibold text-capitalize">{{ $role->name }}</span>
                            <div class="text-muted text-size-small">
                                {{ $role->permissions->count() }} {{ __t('permissions.permissions_count_label', 'permission(s)') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="list-group-item text-muted">
                        {{ __t('permissions.no_roles', 'No roles defined yet.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">{{ __t('permissions.user_role_assignments', 'User Role Assignments') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __t('permissions.username', 'Username') }}</th>
                            <th>{{ __t('permissions.email', 'Email') }}</th>
                            <th style="width: 40%;">{{ __t('permissions.roles', 'Roles') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td>
                                    <strong>{{ $user->username }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <select class="form-control" multiple size="4" wire:model="userRoles.{{ $user->id }}" aria-describedby="role-help-{{ $user->id }}">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    <p id="role-help-{{ $user->id }}" class="text-muted text-size-small m-t-5">
                                        {{ __t('permissions.multi_select_help', 'Hold Ctrl (Cmd on Mac) to select or unselect multiple roles.') }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
