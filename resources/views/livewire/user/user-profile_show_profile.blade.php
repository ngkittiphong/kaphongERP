<div class="tab-pane active" id="tab-detail">
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <div style="width: 200px; height: 200px; margin: 0 auto; border-radius: 50%; overflow: hidden;">
                <img src="{{ $user->profile && $user->profile->avatar ? $user->profile->avatar : asset('assets/images/faces/face_default.png') }}"
                    alt="{{ $user->username }}'s Avatar"
                    class="img-fluid"
                    style="width: 100%; height: 100%; object-fit: cover;" />
            </div>

            <h4 class="no-margin-bottom m-t-10"><i class=""
                    alt="{{ $user->status->name }}"></i>{{ $user->profile?->fullname_th }}
                ({{ $user->profile->nickname }})</h4>
            <div>{{ $user->status->name }}</div>
        </div>
    </div>
    <div class="col-md-8 col-xs-12">
        <!--<div class="panel panel-flat">-->
        <div class="panel-heading no-padding-bottom">
            <h4 class="panel-title"><?= __('User details') ?></h4>
            <div class="elements">
                <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                <button class="btn bg-amber-darkest"
                    wire:click="$dispatch('showEditProfileForm')">Edit User</button>
                <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Delete
                    User</button>
            </div>
            <a class="elements-toggle"><i class="icon-more"></i></a>
        </div>
        <div class="list-group list-group-lg list-group-borderless">
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-user-lock"></i> {{ $user->username }}
            </a>
            <a href="#" class="list-group-item p-l-20" onclick="openPasswordModal({{ $user->id }}); return false;" data-user-id="{{ $user->id }}">
                <i class="icon-lock"></i> Change Password
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-puzzle"></i> {{ $user->type->name }}
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-plus3"></i> {{ $user->created_at }}
            </a>
        </div>
        <div class="panel-heading no-padding-bottom">
            <h4 class="panel-title"><?= __('Contact details') ?></h4>
        </div>
        <div class="list-group list-group-lg list-group-borderless">
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-envelop3"></i>
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-phone2"></i>
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-location4"></i>
            </a>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div class="modal fade" id="passwordChangeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
                <form id="passwordChangeForm">
                    <!-- Username field for accessibility -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" autocomplete="username" value="{{ $user->username }}" readonly>
                        <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password">
                        <div class="text-danger" id="new_password_error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
                        <div class="text-danger" id="new_password_confirmation_error"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitPasswordChange()">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($showChangePasswordModal)
<!-- Keep the Livewire modal for backward compatibility but hide it -->
<div style="display: none;">
    <div class="modal fade in" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" wire:click="closeChangePasswordModal">&times;</button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="changePassword">
                        <!-- Username field for accessibility - visually hidden but available to screen readers -->
                        <div class="form-group" style="display: none;">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" autocomplete="username" value="{{ $user->username }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" wire:model="new_password" autocomplete="new-password">
                            @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" wire:model="new_password_confirmation" autocomplete="new-password">
                            @error('new_password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" wire:click="closeChangePasswordModal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif