<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                            </div>
                            <h2 class="h4 fw-bold text-dark mb-2">
                                Password Change Required
                            </h2>
                            <p class="text-muted small">
                                For security reasons, you must change your password before continuing.
                            </p>
                        </div>

                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="changePassword">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input id="current_password" 
                                       name="current_password" 
                                       type="password" 
                                       wire:model="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       placeholder="Enter current password">
                                @error('current_password') 
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input id="new_password" 
                                       name="new_password" 
                                       type="password" 
                                       wire:model="new_password"
                                       class="form-control @error('new_password') is-invalid @enderror" 
                                       placeholder="Enter new password">
                                @error('new_password') 
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       type="password" 
                                       wire:model="new_password_confirmation"
                                       class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                       placeholder="Confirm new password">
                                @error('new_password_confirmation') 
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @error('form')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-times-circle me-2"></i>
                                    {{ $message }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @enderror

                            <div class="d-grid mb-3">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-key me-2"></i>Change Password
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Changing Password...
                                    </span>
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('user.signOut') }}" 
                                   class="text-decoration-none text-muted small">
                                    <i class="fas fa-sign-out-alt me-1"></i>Sign Out
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>