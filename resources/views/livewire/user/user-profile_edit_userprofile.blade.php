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

<div class="tab-pane active" id="tab-detail">
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <div id="slim-avatar" class="slim" data-size="300,300" data-ratio="1:1" data-shape="circle"
                data-instant-edit="true"
                style="
                width: 200px; 
                height: 200px;
                margin: 0 auto;
                border-radius: 50%;
                overflow: hidden;">
                <!-- Default avatar image -->
                @if ($user->profile && $user->profile->avatar)
                    <img src="{{ $user->profile->avatar }}" alt="{{ $user->username }}'s Avatar" class="img-fluid" />
                @else
                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="{{ __t('user.default_icon', 'Default Icon') }}"
                        class="img-fluid" />
                @endif

                <!-- File input for uploading/replacing the image -->
                <input type="file" name="slim" accept="image/jpeg, image/png" />
            </div>
            <h4 class="no-margin-bottom m-t-10"><i class=""
                    alt="{{ $user->status->name }}"></i>{{ $user->profile?->fullname_th }}
                ({{ $user->profile->nickname }})</h4>
            <div class="form-group col-md-8">
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

            <div class="form-group col-md-8">
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


    <form wire:submit.prevent="updateUserAndProfile" id="updateUserProfileForm">
        <div class="col-md-8 col-xs-12">
            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title">{{ __t('user.edit_user', 'Edit User') }}</h4>
            </div>

            <!-- User profile form fields -->
            <div class="list-group list-group-lg list-group-borderless">
                <!-- Username field (readonly) -->
                <div class="form-group has-feedback has-feedback-left">
                    <input type="text" class="form-control" wire:model="username" placeholder="{{ $user->username }}"
                        value="{{ $user->username }}" readonly>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <!-- Email fields (readonly) -->
                <div class="form-group has-feedback has-feedback-left">
                    <input type="email" class="form-control" wire:model="email" placeholder="{{ $user->email }}"
                        value="{{ $user->email }}" readonly>
                    <div class="form-control-feedback">
                        <i class="icon-envelope text-muted"></i>
                    </div>
                </div>

                <!-- Thai name fields -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Prefix (Thai)</label>
                            <select class="form-control @error('prefix_th') is-invalid @enderror" wire:model="prefix_th">
                                <option value="นาย" {{ ($user->profile->prefix_th ?? '') == 'นาย' ? 'selected' : '' }}>นาย</option>
                                <option value="นาง" {{ ($user->profile->prefix_th ?? '') == 'นาง' ? 'selected' : '' }}>นาง</option>
                                <option value="นางสาว" {{ ($user->profile->prefix_th ?? '') == 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                            </select>
                            @error('prefix_th')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label">Full Name (Thai) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('fullname_th') is-invalid @enderror" wire:model="fullname_th"
                                value="{{ $user->profile->fullname_th ?? '' }}" required>
                            @error('fullname_th')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- English name fields -->
                <div class="row" style="display: none;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Prefix (English)</label>
                            <select class="form-control @error('prefix_en') is-invalid @enderror" wire:model="prefix_en">
                                <option value="Mr." {{ ($user->profile->prefix_en ?? '') == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Mrs." {{ ($user->profile->prefix_en ?? '') == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                <option value="Ms." {{ ($user->profile->prefix_en ?? '') == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            </select>
                            @error('prefix_en')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="control-label">Full Name (English)</label>
                            <input type="text" class="form-control @error('fullname_en') is-invalid @enderror" wire:model="fullname_en"
                                value="{{ $user->profile->fullname_en ?? '' }}">
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
                        value="{{ $user->profile->nickname ?? '' }}" required>
                    @error('nickname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Card ID field -->
                <div class="form-group" style="display: none;">
                    <label class="control-label">Card ID Number</label>
                    <input type="text" class="form-control @error('card_id_no') is-invalid @enderror" wire:model="card_id_no"
                        value="{{ $user->profile->card_id_no ?? '' }}" placeholder="Card ID Number">
                    @error('card_id_no')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Birth Date field -->
                <div class="form-group" style="display: none;">
                    <label class="control-label">Birth Date</label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" wire:model="birth_date"
                        value="{{ $user->profile && $user->profile->birth_date ? date('Y-m-d', strtotime($user->profile->birth_date)) : '' }}">
                    @error('birth_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description field -->
                <div class="form-group">
                    <label class="control-label">{{ __t('user.description', 'Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3" 
                        placeholder="Enter description...">{{ $user->profile->description ?? '' }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit button -->
                <div class="text-right">
                    <button type="submit" class="btn bg-primary-darkest">{{ __t('common.save_changes', 'Save Changes') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
