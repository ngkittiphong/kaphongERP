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
			<form method="post" action="/user/signin_process">
				@csrf			
				<div class="panel panel-body login-form border-left border-left-lg border-left-info">							
					<div class="text-center m-b-20">
						<div class="icon-object bg-info"><i class="icon-user"></i></div>
						<h5>Sign in to your account</h5>
					</div>

					<div class="form-group has-feedback has-feedback-left">
						<input type="text" class="form-control" placeholder="email" name="email" required="required">
						<div class="form-control-feedback">
							<i class="icon-envelope text-muted"></i>
						</div>
					</div>

					<div class="form-group has-feedback has-feedback-left">
						<input type="password" class="form-control" placeholder="Password" name="password" required="required">
						<div class="form-control-feedback">
							<i class="icon-lock text-muted"></i>
						</div>
					</div>

					<div class="login-options">
						<div class="row">
							<div class="col-sm-6">
								<div class="checkbox m-l-5">
									<label>
										<input type="checkbox" class="styled" checked="checked">
										Remember me
									</label>
								</div>
							</div>

							<div class="col-sm-6 text-right m-t-10">
								<a href="http://localhost/templates/penguin/material/login_password_recover.html">Forgot password?</a>
							</div>
						</div>
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-info btn-labeled btn-labeled-right btn-block"><b><i class="icon-enter"></i></b> Sign in</button>								
					</div>

					<div class="form-group">
						<button type="button" onclick="window.location.href='/user/register'" class="btn btn-success btn-labeled btn-labeled-right btn-block"><b><i class="icon-user-plus"></i></b> Create an account</button>								
					</div>
				</div>
				
			</form>
			<!-- /simple login form -->

			@include('includes.footer_layout')
		</div>
		<!-- /page content -->

	</div>

@endsection