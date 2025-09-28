@extends('layout.master_layout')

{{-- Define the skip_global_styles section so the master layout won't load them --}}
@section('skip_global_styles')
@endsection

{{-- Define the skip_global_script section so the master layout won't load them --}}
@section('skip_global_script')
@endsection

@push('body-styles')
<style>
body {
    background: url("{{ asset('assets/images/assets/login_bg.jpg') }}") no-repeat center center;
    background-size: cover;
}
</style>
@endpush

@section('content')
	<div class="login-container">

		<!-- Page content -->
		<div class="page-content">

			@if (isset($errors))
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
			@endif

			<!-- Simple login form -->
			<form method="post" action="/user/user_create">			
				@csrf
				<div class="panel panel-body login-form border-left border-left-lg border-left-success">							
					<div class="text-center m-b-20">
						<div class="icon-object bg-success"><i class="icon-user"></i></div>
						<h5>{{ __t('auth.create_new_account', 'Create new account') }}</h5>
					</div>

					<div class="form-group has-feedback has-feedback-left">
						<input type="text" class="form-control" placeholder="{{ __t('auth.name', 'Name') }}" name="name" required="required">
						<div class="form-control-feedback">
							<i class="icon-user text-muted"></i>
						</div>
					</div>
					
					<div class="form-group has-feedback has-feedback-left">
						<input type="email" class="form-control" placeholder="{{ __t('auth.email', 'Email') }}" name="email" required="required">
						<div class="form-control-feedback">
							<i class="icon-envelope text-muted"></i>
						</div>
					</div>

					<div class="form-group has-feedback has-feedback-left">
						<input type="password" class="form-control" placeholder="{{ __t('auth.password', 'Password') }}" name="password" required="required">
						<div class="form-control-feedback">
							<i class="icon-lock text-muted"></i>
						</div>
					</div>
					
					<div class="form-group has-feedback has-feedback-left">
						<input type="password" class="form-control" placeholder="{{ __t('auth.confirm_password', 'Confirm password') }}" name="confirm" required="required">
						<div class="form-control-feedback">
							<i class="icon-lock text-muted"></i>
						</div>
					</div>

					<div class="login-options">						
						<div class="checkbox m-l-5">
							<label>
								<input type="checkbox" class="styled">
															{{ __t('auth.mail_account_details', 'Mail me my account details') }}
							</label>
						</div>	
						<div class="checkbox m-l-5">
							<label>
								<input type="checkbox" class="styled">
															{{ __t('auth.accept_terms', 'Accept terms & conditions') }}
							</label>
						</div>							
					</div>

					<div class="form-group">
							<button type="submit" class="btn btn-success btn-labeled btn-labeled-right btn-block"><b><i class="icon-user-plus"></i></b> {{ __t('auth.register_now', 'Register now') }}</button>
					</div>
				</div>
				
			</form>
			<!-- /simple login form -->

			@include('includes.footer_layout')

		</div>
		<!-- /page content -->

	</div>
@endsection