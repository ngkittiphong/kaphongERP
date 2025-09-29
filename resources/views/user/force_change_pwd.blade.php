@extends('layout.master_layout')

@section('content')
    <div class="login-container">
        <div class="page-content">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user.change-password.update') }}">
                @csrf
                <div class="panel panel-body login-form border-left border-left-lg border-left-success">
                    <div class="text-center m-b-20">
                        <div class="icon-object bg-success"><i class="icon-lock"></i></div>
                        <h5>{{ __t('auth.change_password_required', 'Change your password to continue') }}</h5>
                        <span class="display-block text-muted">{{ __t('auth.change_password_notice', 'For security reasons, you must update your password before accessing the system.') }}</span>
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password"
                               class="form-control @error('current_password') border-danger @enderror"
                               placeholder="{{ __t('auth.current_password', 'Current password') }}"
                               name="current_password"
                               autofocus
                               required>
                        <div class="form-control-feedback">
                            <i class="icon-lock text-muted"></i>
                        </div>
                        @error('current_password')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password"
                               class="form-control @error('new_password') border-danger @enderror"
                               placeholder="{{ __t('auth.new_password', 'New password') }}"
                               name="new_password"
                               required>
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                        @error('new_password')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password"
                               class="form-control @error('new_password_confirmation') border-danger @enderror"
                               placeholder="{{ __t('auth.confirm_password', 'Confirm new password') }}"
                               name="new_password_confirmation"
                               required>
                        <div class="form-control-feedback">
                            <i class="icon-lock4 text-muted"></i>
                        </div>
                        @error('new_password_confirmation')
                            <span class="help-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-labeled btn-labeled-right btn-block">
                            <b><i class="icon-checkmark4"></i></b> {{ __t('auth.update_password', 'Update password') }}
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('user.signOut') }}" class="text-muted">
                            {{ __t('auth.sign_out', 'Sign out') }}
                        </a>
                    </div>
                </div>
            </form>

            @include('includes.footer_layout')
        </div>
    </div>
@endsection