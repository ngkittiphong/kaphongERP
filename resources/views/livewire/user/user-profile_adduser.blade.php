@error('form')
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Error:</strong> {{ $message }}
    </div>
@enderror

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Error:</strong> {{ session('error') }}
    </div>
@endif

<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <!--<h4 class="panel-title">Add New User</h4>-->
                </div>
                <form wire:submit.prevent="saveUserAndProfile" id="addUserForm">
                    @csrf
                    <div class="col-md-5 col-xs-12">
                        <div class="text-center">
                            {{-- <div class="col-md-4 col-xs-12 style="display: none;">
                                <div class="text-center">
                                    <div id="slim-avatar" class="slim" data-size="300,300" data-ratio="1:1"
                                        data-shape="circle" data-instant-edit="true"
                                        style="
                                            width: 300px; 
                                            height: 300px;
                                            margin: 0 auto;
                                            border-radius: 50%;
                                            overflow: hidden;">
                                        <!-- Default avatar image -->
                                        <img src="{{ asset('assets/images/faces/face_default.png') }}"
                                            alt="Default Icon" class="img-fluid" />

                                        <!-- File input for uploading/replacing the image -->
                                        <input type="file" name="slim" accept="image/jpeg, image/png" />
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <!-- User Type -->
                        <div class="col-md-10 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">User Type</label>
                                <select class="form-control @error('user_type_id') is-invalid @enderror" wire:model="user_type_id">
                                    @foreach ($userTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_type_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- User Status -->
                            <div class="form-group">
                                <label class="control-label">User Status</label>
                                <select class="form-control @error('user_status_id') is-invalid @enderror" wire:model="user_status_id">
                                    @foreach ($userStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_status_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 col-xs-12">
                        <div class="panel-heading no-padding-bottom">
                            <h4 class="panel-title">
                                {{ __t('user.add_new_user', 'Add New User') }}
                                @if($profile_no_preview)
                                    <span class="text-muted">({{ $profile_no_preview }})</span>
                                @endif
                            </h4>
                        </div>

                        <!-- User profile form fields -->
                        <form wire:submit.prevent="saveUserAndProfile">
                            <div class="list-group list-group-lg list-group-borderless">
                            <!-- Username field -->
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-label">{{ __t('user.username', 'Username') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" wire:model="username" placeholder="{{ __t('user.username', 'Username') }}"
                                    required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email field -->
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-label">{{ __t('user.email', 'Email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email" placeholder="{{ __t('user.email', 'Email') }}"
                                    required>
                                <div class="form-control-feedback">
                                    <i class="icon-envelope text-muted"></i>
                                </div>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password field -->
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-label">{{ __t('user.password', 'Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" placeholder="{{ __t('user.password', 'Password') }}"
                                    autocomplete="new-password" required>
                                <div class="form-control-feedback">
                                    <i class="icon-lock text-muted"></i>
                                </div>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Confirmation field -->
                            <div class="form-group has-feedback has-feedback-left">
                                <label class="control-label">{{ __t('user.confirm_password', 'Confirm password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" wire:model="password_confirmation"
                                    placeholder="{{ __t('user.confirm_password', 'Confirm password') }}" autocomplete="new-password" required>
                                <div class="form-control-feedback">
                                    <i class="icon-lock text-muted"></i>
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thai Name Fields -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Prefix (TH)</label>
                                        <select class="form-control @error('prefix_th') is-invalid @enderror" wire:model="prefix_th">
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                        </select>
                                        @error('prefix_th')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="control-label">Full Name (TH) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('fullname_th') is-invalid @enderror" placeholder="{{ __t('user.fullname_th', 'ชื่อ - นามสกุล') }}"
                                            wire:model="fullname_th" required>
                                        @error('fullname_th')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- English Name Fields -->
                            <div class="row" style="display: none;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Prefix (EN)</label>
                                        <select class="form-control @error('prefix_en') is-invalid @enderror" wire:model="prefix_en">
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                        </select>
                                        @error('prefix_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="control-label">Full Name (EN)</label>
                                        <input type="text" placeholder="{{ __t('user.fullname_en', 'Fullname') }}" class="form-control @error('fullname_en') is-invalid @enderror"
                                            wire:model="fullname_en">
                                        @error('fullname_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Nickname field -->
                            <div class="form-group">
                                <label class="control-label">{{ __t('user.nickname', 'Nickname') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nickname') is-invalid @enderror" wire:model="nickname"
                                    placeholder="{{ __t('user.nickname', 'Nickname') }}" required>
                                @error('nickname')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Card ID field -->
                            <div class="form-group" style="display: none;">
                                <label class="control-label">Card ID Number</label>
                                <input type="text" class="form-control @error('card_id_no') is-invalid @enderror" wire:model="card_id_no"
                                    placeholder="Card ID Number">
                                @error('card_id_no')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Birth Date field -->
                            <div class="form-group" style="display: none;">
                                <label class="control-label">Birth Date</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" wire:model="birth_date">
                                @error('birth_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label class="control-label">{{ __t('user.description', 'Description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3" placeholder="Enter description..."></textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Additional profile fields... -->

                            <div class="text-right">
                                <button type="submit" class="btn bg-primary">
                                    {{ __t('user.create_now', 'Create now') }}
                                </button>
                            </div>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
