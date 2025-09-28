@error('form')
    <div class="alert alert-danger">
        {{ $message }}
    </div>
@enderror

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
                            <div class="col-md-4 col-xs-12">
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
                            </div>
                        </div>

                        <!-- User Type -->
                        <div class="col-md-10 col-xs-12">
                            <div class="form-group">
                                <select class="form-control" wire:model="user_type_id">
                                    @foreach ($userTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_type_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- User Status -->
                            <div class="form-group">
                                <select class="form-control" wire:model="user_status_id">
                                    @foreach ($userStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_status_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- 2) Hide the Form While Loading -->
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-7 col-xs-12">
                        <div class="panel-heading no-padding-bottom">
                            <h4 class="panel-title">{{ __t('user.add_new_user', 'Add New User') }}</h4>
                        </div>

                        <!-- User profile form fields -->
                        <div class="list-group list-group-lg list-group-borderless">
                            <!-- Username field (readonly) -->
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control" wire:model="username" placeholder="{{ __t('user.username', 'Username') }}"
                                    required>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                @error('username')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email fields (readonly) -->
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="email" class="form-control" wire:model="email" placeholder="{{ __t('user.email', 'Email') }}"
                                    required>
                                <div class="form-control-feedback">
                                    <i class="icon-envelope text-muted"></i>
                                </div>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group has-feedback has-feedback-left">
                                <input type="password" class="form-control" wire:model="password" placeholder="{{ __t('user.password', 'Password') }}"
                                    required>
                                <div class="form-control-feedback">
                                    <i class="icon-lock text-muted"></i>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="password" class="form-control" wire:model="password_confirmation"
                                    placeholder="{{ __t('user.confirm_password', 'Confirm password') }}" required>
                                <div class="form-control-feedback">
                                    <i class="icon-lock text-muted"></i>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Example: More Profile Fields -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control" wire:model="prefix_th">
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="{{ __t('user.fullname_th', 'ชื่อ - นามสกุล') }}"
                                            wire:model="fullname_th">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-control" wire:model="prefix_en">
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" placeholder="{{ __t('user.fullname_en', 'Fullname') }}" class="form-control"
                                            wire:model="fullname_en" value="{{ $user->profile->fullname_en ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Nickname field -->
                            <div class="form-group">
                                <label>{{ __t('user.nickname', 'Nickname') }}</label>
                                <input type="text" class="form-control" wire:model="nickname"
                                    placeholder="{{ __t('user.nickname', 'Nickname') }}">
                            </div>

                            <!-- Description field -->
                            <div class="form-group">
                                <label>{{ __t('user.description', 'Description') }}</label>
                                <textarea class="form-control" wire:model="description" rows="3">{{ $user->profile->description ?? '' }}</textarea>
                            </div>
                            <!-- Additional profile fields... -->

                            <div class="text-right">
                                <button type="submit" class="btn bg-primary">
                                    {{ __t('user.create_now', 'Create now') }}
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
