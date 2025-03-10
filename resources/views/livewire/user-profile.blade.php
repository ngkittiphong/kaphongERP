<!-- resources/views/livewire/user-profile.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="flex items-center justify-center w-full" style="position: fixed; top: 50%; left: 65%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="panel-body">
            <div class="loader">
                <div class="loader-inner ball-beat">
                  <div></div>
                  <div></div>
                  <div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2) Hide the Form While Loading -->
    <div wire:loading.remove>
        {{-- Show/Hide Add User Form based on showAddUserForm flag --}}
        @if($showAddUserForm)
        @error('form')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
        @enderror
        <div class="card p-4">
            <h4>Add New User</h4>
            <!-- Unified form -->
            <form wire:submit.prevent="saveUserAndProfile">
                @csrf
                <div class="panel panel-body border-left border-left-lg border-left-success">							
                    <div class="container my-4">
                        <div class="row">
                            <div class="col-md-4 col-xs-12 mx-auto">
                                
                                <!-- Bootstrap card (optional) -->
                                <div class="card">
                                    <div class="card-body text-center">
                                        
                                        <!-- Slim Image Cropper Container -->
                                        <div
                                            class="slim"
                                            data-size="300,300"
                                            data-ratio="1:1"
                                            data-shape="circle"
                                            data-instant-edit="true"
                                            style="
                                                width: 150px; 
                                                height: 150px;
                                                margin: 0 auto;
                                                border-radius: 50%;
                                                overflow: hidden;"
                                        >
                                            <!-- Default avatar image -->
                                            <img 
                                                src="{{ asset('assets/images/faces/face1.png') }}" 
                                                alt="Default Icon" 
                                                class="img-fluid"
                                            />
                                            
                                            <!-- File input for uploading/replacing the image -->
                                            <input 
                                                type="file" 
                                                name="slim" 
                                                accept="image/jpeg, image/png, image/gif"
                                            />
                                        </div>
                                        
                                        <!-- Optional: instructions or a button to remove/re-crop -->
                                        <p class="mt-2 small text-muted">
                                            Click or drag your image here to crop a circular avatar.
                                        </p>
                                        
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- 2) Hide the Form While Loading -->
                    @script
                    <script>
                        $nextTick(() => {
                            // Remove old script if exists
                            const oldScript = document.getElementById('slim-script');
                            if (oldScript) {
                                oldScript.remove();
                            }
                    
                            // Create and append new script using Alpine
                            const script = document.createElement('script');
                            script.id = 'slim-script';
                            script.src = "{{ asset('slim/js/slim.kickstart.min.js') }}";
                            document.body.appendChild(script);
                        });
                    </script>
                    @endscript
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- User Fields -->
                    <div class="form-group has-feedback has-feedback-left">
                        <input type="text" class="form-control"
                               wire:model="username"
                               placeholder="Username" required>
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group has-feedback has-feedback-left">
                        <input type="email" class="form-control"
                               wire:model="email"
                               placeholder="Email" required>
                        <div class="form-control-feedback">
                            <i class="icon-envelope text-muted"></i>
                        </div>
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group has-feedback has-feedback-left" >
                        <input type="password" class="form-control"
                               wire:model="password"
                               placeholder="Password" required>
                        <div class="form-control-feedback">
                            <i class="icon-lock text-muted"></i>
                        </div>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password" class="form-control"
                               wire:model="password_confirmation"
                               placeholder="Confirm password" required>
                        <div class="form-control-feedback">
                            <i class="icon-lock text-muted"></i>
                        </div>
                        @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- User Type -->
                    <div class="form-group">
                        <label>User Type:</label>
                        <select class="form-control" wire:model="user_type_id">
                            <option value="">-- Select User Type --</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('user_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- User Status -->
                    <div class="form-group">
                        <label>User Status:</label>
                        <select class="form-control" wire:model="user_status_id">
                            <option value="">-- Select User Status --</option>
                            @foreach($userStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        @error('user_status_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Profile Fields -->
                    <hr>
                    <h5>Profile Information</h5>

                    <div class="form-group">
                        <label>Nickname</label>
                        <input type="text" class="form-control"
                               wire:model="nickname"
                               placeholder="Nickname">
                    </div>

                    <div class="form-group">
                        <label>ID Number</label>
                        <input type="text" class="form-control"
                               wire:model="card_id_no"
                               placeholder="ID No">
                    </div>

                    <!-- Example: More Profile Fields -->
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Prefix (Thai)</label>
                                <input type="text" class="form-control"
                                       wire:model="prefix_th">
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Full Name (Thai)</label>
                                <input type="text" class="form-control"
                                       wire:model="fullname_th">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Prefix (English)</label>
                                <input type="text" class="form-control" wire:model="prefix_en" value="{{ $user->profile->prefix_en ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Full Name (English)</label>
                                <input type="text" class="form-control" wire:model="fullname_en" value="{{ $user->profile->fullname_en ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Birth Date</label>
                        <input type="date" class="form-control" wire:model="birth_date" value="{{ $user->profile->birth_date ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" wire:model="description" rows="3">{{ $user->profile->description ?? '' }}</textarea>
                    </div>

                    <!-- Additional profile fields... -->

                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-labeled btn-block">
                            <b><i class="icon-user-plus"></i></b> Register now
                        </button>								
                    </div>
                </div>
            </form>
        </div>
        {{-- Show user profile if user exists, has profile and edit form is not shown --}}
        @elseif($user && $user->profile && $showEditProfileForm==false)
        
        
        
        
        
        
        
        
        
        
{{----------------------------------- view user profile form ------------------------------}}
        
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
                                            <h3 class="panel-title">Detail</h3>

                                        </div>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#tab-access" data-toggle="tab" aria-expanded="false">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Access</h3>						
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
                                        <!--<img src="{{ asset('assets/images/faces/face1.png') }}" class="img-responsive img-circle user-avatar" alt="{{$user->status->name}}">-->
                                        <div
                                            class="slim"
                                            data-size="300,300"
                                            data-ratio="1:1"
                                            data-shape="circle"
                                            data-instant-edit="true"
                                            style="
                                                width: 200px; 
                                                height: 200px;
                                                margin: 0 auto;
                                                border-radius: 50%;
                                                overflow: hidden;"
                                        >
                                            <!-- Default avatar image -->
                                            <img 
                                                src="{{ asset('assets/images/faces/face1.png') }}" 
                                                alt="Default Icon" 
                                                class="img-fluid"
                                            />

                                            <!-- File input for uploading/replacing the image -->
                                            <input 
                                                type="file" 
                                                name="slim" 
                                                accept="image/jpeg, image/png, image/gif"
                                            />
                                        </div>

                                        <h4 class="no-margin-bottom m-t-10"><i class="" alt="{{$user->status->name}}"></i>{{$user->profile?->fullname_th}} ({{$user->profile->nickname}})</h4>
                                        <div>user.status.name</div>
                                </div>
                            </div>
                            <div class="col-md-8 col-xs-12">
                                <!--<div class="panel panel-flat">-->
                                    <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title"><?= __('User details')?></h4>	
                                            <div class="elements">
                                                    <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                                    <button class="btn bg-amber-darkest" wire:click="$dispatch('showEditProfileForm')">Edit User</button>
                                                    <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Delete User</button>
                                            </div>
                                            <a class="elements-toggle"><i class="icon-more"></i></a>
                                    </div>

                                    <div class="list-group list-group-lg list-group-borderless">	
                                        <a href="#" class="list-group-item p-l-20">
                                                <i class="icon-user-lock"></i> {{$user->username}}
                                        </a>

                                        <a href="#" class="list-group-item p-l-20">
                                                <i class="icon-lock"></i> change password click for popup(modal)
                                        </a>


                                        <a href="#" class="list-group-item p-l-20">
                                                <i class="icon-puzzle"></i> user.type.name
                                        </a>
                                        <a href="#" class="list-group-item p-l-20">
                                                <i class="icon-plus3"></i> user.date_create
                                        </a>


                                    </div>									

                                    <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title"><?= __('Contact details')?></h4>	
                                    </div>

                                    <div class="list-group list-group-lg list-group-borderless">												
                                            <a href="#" class="list-group-item p-l-20">
                                                    <i class="icon-envelop3"></i> 
                                            </a>
                                            <a href="#" class="list-group-item p-l-20">
                                                    <i class="icon-phone2"></i>
                                            </a>
                                            <a href="#" class="list-group-item p-l-20">
                                                    <i class="icon-location4"></i> 
                                            </a>
                                    </div>									

                            </div>

                        </div>
                        <div class="tab-pane" id="tab-access">

                            {{-- Access --}}
                            Access detail statement
                            
                        </div>


                    </div>



            </div>

        </div>
    </div>

</div>
        
        
        
{{----------------------------------- end of view user profile form ------------------------------}}        
        
        
        






  



{{----------------------------------- edit user profile form ------------------------------}} 


{{-- Show edit profile form if showEditProfileForm is true and user exists --}}
@elseif($showEditProfileForm && $user)
        
        
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
                                        <!--<img src="{{ asset('assets/images/faces/face1.png') }}" class="img-responsive img-circle user-avatar" alt="{{$user->status->name}}">-->
                                        <div
                                            class="slim"
                                            data-size="300,300"
                                            data-ratio="1:1"
                                            data-shape="circle"
                                            data-instant-edit="true"
                                            style="
                                                width: 200px; 
                                                height: 200px;
                                                margin: 0 auto;
                                                border-radius: 50%;
                                                overflow: hidden;"
                                        >
                                            <!-- Default avatar image -->
                                            <img 
                                                src="{{ asset('assets/images/faces/face1.png') }}" 
                                                alt="Default Icon" 
                                                class="img-fluid"
                                            />

                                            <!-- File input for uploading/replacing the image -->
                                            <input 
                                                type="file" 
                                                name="slim" 
                                                accept="image/jpeg, image/png, image/gif"
                                            />
                                        </div>

                                        <h4 class="no-margin-bottom m-t-10"><i class="" alt="{{$user->status->name}}"></i>{{$user->profile?->fullname_th}} ({{$user->profile->nickname}})</h4>
                                        <div class="form-group col-md-8">
                                            <select class="form-control" >
                                                <option>Admin</option>
                                                <option>User</option>
                                            </select>

                                        </div>

                                        <div class="form-group col-md-8">
                                            <select class="form-control" >
                                                <option>Active</option>
                                                <option>Hold</option>
                                            </select>

                                        </div>
                                </div>
                            </div>
                            
                            
                            <form wire:submit.prevent="updateUserAndProfile">
                                <div class="col-md-8 col-xs-12">
                                        <div class="panel-heading no-padding-bottom">
                                                <h4 class="panel-title"><?= __('Edit User')?></h4>	

                                        </div>

                                        <div class="list-group list-group-lg list-group-borderless">	
                                            
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input type="text" class="form-control" wire:model="username" placeholder="{{ $user->username }}" value="{{ $user->username }}" readonly>
                                                <div class="form-control-feedback">
                                                    <i class="icon-user text-muted"></i>
                                                </div>
                                            </div>

                                            <div class="form-group has-feedback has-feedback-left">
                                                <input type="email" class="form-control" wire:model="email" placeholder="{{ $user->email }}" value="{{ $user->email }}" readonly>
                                                <div class="form-control-feedback">
                                                    <i class="icon-envelope text-muted"></i>
                                                </div>
                                            </div>
                                            

                                            
                                            <div class="form-group has-feedback has-feedback-left">
                                                <input type="email" class="form-control" wire:model="email" placeholder="{{ $user->email }}" value="{{ $user->email }}" readonly>
                                                <div class="form-control-feedback">
                                                    <i class="icon-envelope text-muted"></i>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Prefix (Thai)</label>
                                                        <input type="text" class="form-control" wire:model="prefix_th" value="{{ $user->profile->prefix_th ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Full Name (Thai)</label>
                                                        <input type="text" class="form-control" wire:model="fullname_th" value="{{ $user->profile->fullname_th ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Prefix (English)</label>
                                                        <input type="text" class="form-control" wire:model="prefix_en" value="{{ $user->profile->prefix_en ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Full Name (English)</label>
                                                        <input type="text" class="form-control" wire:model="fullname_en" value="{{ $user->profile->fullname_en ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label>Nickname</label>
                                                <input type="text" class="form-control" wire:model="nickname" value="test">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" wire:model="description" rows="3">{{ $user->profile->description ?? '' }}</textarea>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" class="btn bg-primary-darkest">Save Changes</button>
                                            </div>
                                            
                                            

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
        
{{----------------------------------- end of edit user profile form ------------------------------}}
        
        
        
        
        
{{--        Old edit
        
        
        
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">{{ $user->profile ? 'Edit Profile' : 'Add Profile' }}</h5>
                    </div>
                    <div class="panel-body">
                        <form wire:submit.prevent="updateUserAndProfile">
                            <!-- Username (Read-only) -->
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="text" class="form-control" wire:model="username" placeholder="{{ $user->username }}" value="{{ $user->username }}" readonly>
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>
        
                            <!-- Email (Read-only) -->
                            <div class="form-group has-feedback has-feedback-left">
                                <input type="email" class="form-control" wire:model="email" placeholder="{{ $user->email }}" value="{{ $user->email }}" readonly>
                                <div class="form-control-feedback">
                                    <i class="icon-envelope text-muted"></i>
                                </div>
                            </div>
        
                            <!-- Avatar Upload -->
                            <div class="form-group">
                                <label>Avatar Picture</label>
                                <input type="file" class="form-control" wire:model="avatar">
                            </div>
        
                            <!-- Dynamic Profile Fields -->
                            <div class="form-group">
                                <label>Nickname</label>
                                <input type="text" class="form-control" wire:model="nickname" value="test">
                            </div>
        
                            <div class="form-group">
                                <label>ID Number</label>
                                <input type="text" class="form-control" wire:model="card_id_no" value="{{ $user->profile->card_id_no ?? '' }}">
                            </div>
        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prefix (Thai)</label>
                                        <input type="text" class="form-control" wire:model="prefix_th" value="{{ $user->profile->prefix_th ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name (Thai)</label>
                                        <input type="text" class="form-control" wire:model="fullname_th" value="{{ $user->profile->fullname_th ?? '' }}">
                                    </div>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prefix (English)</label>
                                        <input type="text" class="form-control" wire:model="prefix_en" value="{{ $user->profile->prefix_en ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name (English)</label>
                                        <input type="text" class="form-control" wire:model="fullname_en" value="{{ $user->profile->fullname_en ?? '' }}">
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <label>Birth Date</label>
                                <input type="date" class="form-control" wire:model="birth_date" value="{{ $user->profile->birth_date ?? '' }}">
                            </div>
        
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" wire:model="description" rows="3">{{ $user->profile->description ?? '' }}</textarea>
                            </div>
        
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




--}}




        {{-- Show add profile form if user exists but profile is not created --}}
        @elseif($user && $user->profile==null)
            <div class="form-group has-feedback has-feedback-left">
                <button type="button" class="btn btn-sm btn-success btn-labeled"
                    wire:click="$dispatch('showEditProfileForm')">
                    <b><i class="icon-plus3"></i></b> Add User Profile
                </button>
            </div>
        @endif
    </div>
</div>

@push('styles')
<!-- Include Slim CSS -->
<link rel="stylesheet" href="{{ asset('slim/css/slim.min.css') }}">
@endpush

@push('scripts')
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteUser', { userId: userId });
            }
        });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    Livewire.on('userCreated', data => {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: data.message,
        });
    });
</script>

<!-- Include Slim JS -->
<script src="{{ asset('slim/js/slim.kickstart.min.js') }}"></script>
@endpush