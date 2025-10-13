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
                <!-- Hidden username field for accessibility -->
                <input type="text" 
                       name="username" 
                       value="{{ Auth::user()->username ?? '' }}" 
                       autocomplete="username"
                       style="display: none;"
                       tabindex="-1">
                <div class="panel panel-body login-form border-left border-left-lg border-left-success">
                    <div class="text-center m-b-20">
                        <div class="icon-object bg-success"><i class="icon-lock"></i></div>
                        <h5>{{ __t('auth.change_password_required', 'Change your password to continue') }}</h5>
                        <span class="display-block text-muted">{{ __t('auth.change_password_notice', 'For security reasons, you must update your password before accessing the system.') }}</span>
                        
                        @php
                            $username = Auth::user()->username ?? '';
                            $maskedUsername = '';
                            if ($username !== '') {
                                $len = strlen($username);
                                if ($len <= 2) {
                                    $maskedUsername = $username;
                                } else {
                                    $maskedUsername = substr($username, 0, 1)
                                        . str_repeat('*', $len - 2)
                                        . substr($username, -1);
                                }
                            }
                        @endphp
                        
                        @if($username)
                        <div class="m-t-15">
                            <span class="text-muted small">
                                {{ __t('auth.username', 'Username') }}: <strong class="text-dark">{{ $maskedUsername }}</strong>
                            </span>
                        </div>
                        @endif
                        
                        <!-- Session timeout warning -->
                        <div class="m-t-15">
                            <div class="alert alert-warning alert-sm">
                                <i class="icon-clock"></i>
                                <strong>{{ __t('auth.session_timeout', 'Session Timeout') }}:</strong>
                                <span id="session-countdown">{{ __t('auth.timeout_message', 'You have 5 minutes to change your password or your session will expire.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group has-feedback has-feedback-left">
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
                    </div> --}}

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password"
                               class="form-control @error('new_password') border-danger @enderror"
                               placeholder="{{ __t('auth.new_password', 'New password') }}"
                               name="new_password"
                               autocomplete="new-password"
                               required>
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                        <div class="help-block text-muted">
                            <small><i class="icon-info"></i> {{ __t('auth.password_requirement_hint', 'Password must be at least 8 characters') }}</small>
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
                               autocomplete="new-password"
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

    <!-- Session countdown timer script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial time (5 minutes = 300 seconds)
            let timeLeft = 300; // 5 minutes in seconds
            const countdownElement = document.getElementById('session-countdown');
            
            function updateCountdown() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                if (timeLeft <= 0) {
                    countdownElement.innerHTML = '<span class="text-danger">Session expired! Redirecting to login...</span>';
                    // Redirect to login after 2 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                    return;
                }
                
                // Update the display
                countdownElement.innerHTML = `You have <strong>${minutes}:${seconds.toString().padStart(2, '0')}</strong> to change your password or your session will expire.`;
                
                // Change color when time is running low
                if (timeLeft <= 60) { // Last minute
                    countdownElement.className = 'text-danger';
                } else if (timeLeft <= 120) { // Last 2 minutes
                    countdownElement.className = 'text-warning';
                }
                
                timeLeft--;
            }
            
            // Update countdown every second
            updateCountdown(); // Initial call
            setInterval(updateCountdown, 1000);
        });
    </script>
@endsection