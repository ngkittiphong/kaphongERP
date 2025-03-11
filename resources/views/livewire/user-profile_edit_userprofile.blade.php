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
                @if($user->profile && $user->profile->avatar)
                    <img src="{{ $user->profile->avatar }}" alt="{{ $user->username }}'s Avatar" class="img-fluid" />
                @else
                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="Default Icon" class="img-fluid" />
                @endif

                <!-- File input for uploading/replacing the image -->
                <input type="file" name="slim" accept="image/jpeg, image/png" />
            </div>
            @script
            <script>
                $nextTick(() => {
                    // Handle form submission
                    document.getElementById('updateUserProfileForm').addEventListener('submit', function(e) {
                        e.preventDefault();

                        console.log('submit');
                        
                        // Get Slim instance
                        const slim = document.getElementById('slim-avatar');

                        console.log("slim element found");
                        
                        // Get base64 data
                        if (slim) {
                            console.log("convert to base64");
                            const base64Data = document.querySelector('#slim-avatar .slim-result img.in').src;
                            //const base64Data = slim.data.output.image;
                            console.log("base64Data", base64Data);
                            // Set avatar value before submitting
                            @this.set('avatar', base64Data);
                        }
                        
                        // Continue with form submission
                        @this.saveUserAndProfile();
                    });
                });
            </script>
            @endscript


            <h4 class="no-margin-bottom m-t-10"><i class=""
                    alt="{{ $user->status->name }}"></i>{{ $user->profile?->fullname_th }}
                ({{ $user->profile->nickname }})</h4>
            <div class="form-group col-md-8">
                <select class="form-control">
                    <option>Admin</option>
                    <option>User</option>
                </select>
            </div>

            <div class="form-group col-md-8">
                <select class="form-control">
                    <option>Active</option>
                    <option>Hold</option>
                </select>
            </div>
        </div>
    </div>


    <form wire:submit.prevent="updateUserAndProfile" id="updateUserProfileForm">
        <div class="col-md-8 col-xs-12">
            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title"><?= __('Edit User') ?></h4>
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
                            <label>Prefix (Thai)</label>
                            <select class="form-control" wire:model="prefix_th">
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Full Name (Thai)</label>
                            <input type="text" class="form-control" wire:model="fullname_th"
                                value="{{ $user->profile->fullname_th ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- English name fields -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Prefix (English)</label>
                            <select class="form-control" wire:model="prefix_en">
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Ms.">Ms.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Full Name (English)</label>
                            <input type="text" class="form-control" wire:model="fullname_en"
                                value="{{ $user->profile->fullname_en ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Nickname field -->
                <div class="form-group">
                    <label>Nickname</label>
                    <input type="text" class="form-control" wire:model="nickname"
                        value="test">
                </div>

                <!-- Description field -->
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" wire:model="description" rows="3">{{ $user->profile->description ?? '' }}</textarea>
                </div>

                <!-- Submit button -->
                <div class="text-right">
                    <button type="submit" class="btn bg-primary-darkest">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>