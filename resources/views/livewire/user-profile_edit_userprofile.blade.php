<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="active">
                                <a href="#tab-detail" data-toggle="tab" class="panel-title" aria-expanded="true">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Profile</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-access" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Contact</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-doc" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Document</h3>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-detail">
                        <div class="col-md-4 col-xs-12">
                            <div class="text-center">
                                <div class="slim" data-size="300,300" data-ratio="1:1" data-shape="circle"
                                    data-instant-edit="true"
                                    style="
                                                width: 200px; 
                                                height: 200px;
                                                margin: 0 auto;
                                                border-radius: 50%;
                                                overflow: hidden;">
                                    <!-- Default avatar image -->
                                    <img src="{{ asset('assets/images/faces/face1.png') }}" alt="Default Icon"
                                        class="img-fluid" />

                                    <!-- File input for uploading/replacing the image -->
                                    <input type="file" name="slim" accept="image/jpeg, image/png, image/gif" />
                                </div>

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


                        <form wire:submit.prevent="updateUserAndProfile">
                            <div class="col-md-8 col-xs-12">
                                <div class="panel-heading no-padding-bottom">
                                    <h4 class="panel-title"><?= __('Edit User') ?></h4>
                                </div>

                                <!-- User profile form fields -->
                                <div class="list-group list-group-lg list-group-borderless">
                                    <!-- Username field (readonly) -->
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input type="text" class="form-control" wire:model="username"
                                            placeholder="{{ $user->username }}" value="{{ $user->username }}" readonly>
                                        <div class="form-control-feedback">
                                            <i class="icon-user text-muted"></i>
                                        </div>
                                    </div>

                                    <!-- Email fields (readonly) -->
                                    <div class="form-group has-feedback has-feedback-left">
                                        <input type="email" class="form-control" wire:model="email"
                                            placeholder="{{ $user->email }}" value="{{ $user->email }}" readonly>
                                        <div class="form-control-feedback">
                                            <i class="icon-envelope text-muted"></i>
                                        </div>
                                    </div>

                                    <!-- Thai name fields -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Prefix (Thai)</label>
                                                <select class="form-control" wire:model="prefix_th">
                                                    <option value="นาย">นาย</option>
                                                    <option value="นาง">นาง</option>
                                                    <option value="นางสาว">นางสาว</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Full Name (Thai)</label>
                                                <input type="text" class="form-control" wire:model="fullname_th"
                                                    value="{{ $user->profile->fullname_th ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- English name fields -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Prefix (English)</label>
                                                <select class="form-control" wire:model="prefix_en">
                                                    <option value="Mr.">Mr.</option>
                                                    <option value="Mrs.">Mrs.</option>
                                                    <option value="Ms.">Ms.</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
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
                                    </div
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab-access">
                        s
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
