<!doctype html>
<html style="height:100%" lang="{{ session('locale', 'en') }}">

@include('includes.head_layout')

<body>
	<!--@stack('body-styles')-->

	@yield('content')
	@show

	<!-- Language Switcher moved to sidebar -->

	@livewireScripts
	{{-- @livewire('livewire-ui-modal')	 --}}

	<!-- Change Nickname Modal - Centered -->
	<div class="modal fade" id="change-nickname-modal" tabindex="-1" role="dialog" aria-labelledby="changeNicknameModalLabel" style="z-index: 9999;">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="changeNicknameModalLabel">{{ __t('profile.change_nickname', 'Change Nickname') }}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="change-nickname-form">
						@csrf
						<div class="form-group">
							<label for="new-nickname">{{ __t('profile.new_nickname', 'New Nickname') }}</label>
							<input type="text" 
								   class="form-control" 
								   id="new-nickname" 
								   name="nickname" 
								   value="{{ Auth::user()->profile->nickname ?? '' }}"
								   required>
							<div class="invalid-feedback" id="nickname-error"></div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ __t('common.cancel', 'Cancel') }}</button>
					<button type="button" class="btn btn-primary" id="save-nickname">{{ __t('common.save_changes', 'Save Changes') }}</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Change Password Modal - Centered -->
	<div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" style="z-index: 9999;">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="changePasswordModalLabel">{{ __t('profile.change_password', 'Change Password') }}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="change-password-form">
						@csrf
						<!-- Hidden username field for accessibility -->
						<input type="text" 
							   name="username" 
							   value="{{ Auth::user()->username ?? '' }}" 
							   autocomplete="username"
							   style="display: none;"
							   tabindex="-1">
						<div class="form-group">
							<label for="current-password">{{ __t('profile.current_password', 'Current Password') }}</label>
							<input type="password" 
								   class="form-control" 
								   id="current-password" 
								   name="current_password" 
								   autocomplete="current-password"
								   required>
							<div class="text-danger" id="current-password-error"></div>
						</div>
						<div class="form-group">
							<label for="new-password">{{ __t('profile.new_password', 'New Password') }}</label>
							<input type="password" 
								   class="form-control" 
								   id="new-password" 
								   name="new_password" 
								   autocomplete="new-password"
								   required>
							<div class="help-block text-muted">
								<small><i class="icon-info"></i> {{ __t('auth.password_requirement_hint', 'Password must be at least 8 characters') }}</small>
							</div>
							<div class="text-danger" id="new-password-error"></div>
						</div>
						<div class="form-group">
							<label for="confirm-password">{{ __t('profile.confirm_password', 'Confirm New Password') }}</label>
							<input type="password" 
								   class="form-control" 
								   id="confirm-password" 
								   name="confirm_password" 
								   autocomplete="new-password"
								   required>
							<div class="text-danger" id="confirm-password-error"></div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ __t('common.cancel', 'Cancel') }}</button>
					<button type="button" class="btn btn-primary" id="save-password">{{ __t('common.change_password', 'Change Password') }}</button>
				</div>
			</div>
		</div>
	</div>

</body>

@include('includes.global_scripts')

</html>