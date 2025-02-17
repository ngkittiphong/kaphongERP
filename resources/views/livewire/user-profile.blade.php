<!-- resources/views/livewire/user-profile.blade.php -->
<div wire:ignore.self>
    <div class="row p-l-10 p-r-10">
        <div wire:loading class="text-center p-3">
            <i class="icon-spinner2 spinner"></i> Loading profile...
        </div>

        @if($showAddUsetForm)
            <div class="card p-4">
                <h4>Add New User</h4>
            <!-- Simple login form -->
            <form wire:submit.prevent="saveUser">
                @csrf
                <div class="panel panel-body login-form border-left border-left-lg border-left-success">							
                    <div class="text-center m-b-20">
                        <div class="icon-object bg-success"><i class="icon-user"></i></div>
                        <h5>Create new account</h5>
                        
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="text" class="form-control" wire:model="username" placeholder="username" name="username" required="required">
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group has-feedback has-feedback-left">
                        <input type="email" class="form-control" wire:model="email" placeholder="Email" name="email" required="required">
                        <div class="form-control-feedback">
                            <i class="icon-envelope text-muted"></i>
                        </div>
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group has-feedback has-feedback-left" >
                        <input type="password" class="form-control" wire:model="password" placeholder="Password" name="password" required="required">
                        <div class="form-control-feedback">
                            <i class="icon-lock text-muted"></i>
                        </div>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password" class="form-control" wire:model="password_confirmation" placeholder="Confirm password" name="confirm" required="required">
                        <div class="form-control-feedback">
                            <i class="icon-lock text-muted"></i>
                        </div>
                    </div>

                    <!-- User Type Dropdown -->
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

                    <!-- User Type Dropdown -->
                    <div class="form-group">
                        <label>User Type:</label>
                        <select class="form-control" wire:model="user_status_id">
                            <option value="">-- Select User Status --</option>
                            @foreach($userStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        @error('user_status_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-labeled btn-labeled-right btn-block"><b><i class="icon-user-plus"></i></b> Register now</button>								
                    </div>
                </div>
                
            </form>
            <!-- /simple login form -->
            </div>
        @elseif($user && $user->profile && $showEditProfileForm==false)
        <div class="col-md-4 col-xs-12">
            <div class="text-center">
                <img src="{{ asset('assets/images/faces/face1.png') }}" class="img-responsive img-circle user-avatar" alt="">
                <h2 class="no-margin-bottom m-t-10">{{ $user->profile->fullname_en }}</h2>
                <div>Company Secretary</div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="panel panel-flat">
                <div class="panel-heading no-padding-bottom">
                    <h5 class="panel-title">User profile</h5>						
                </div>
                
                <div class="list-group list-group-lg list-group-borderless">												
                    <a href="people.htm#" class="list-group-item p-l-20">
                        <i class="icon-envelop3"></i> {{ $user->email }}
                    </a>
                    <a href="people.htm#" class="list-group-item p-l-20">
                        <i class="icon-profile"></i> 
                        <div>Full Name (EN): {{ $user->profile->prefix_en }} {{ $user->profile->fullname_en }}</div>
                        <div>Full Name (TH): {{ $user->profile->prefix_th }} {{ $user->profile->fullname_th }}</div>
                        <div>Nickname: {{ $user->profile->nickname }}</div>
                        <div>ID Number: {{ $user->profile->id_no }}</div>

                    </a>
                    <a href="people.htm#" class="list-group-item p-l-20">
                        <i class="icon-location4"></i> {{ $user->profile->address }}
                    </a>
                </div>	
                <div class="panel-footer">
                    <button class="btn btn-primary" wire:click="$dispatch('showEditProfileForm')">Edit User</button>
                    <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Delete User</button>
                </div>
            </div>
        </div>
        @elseif($showEditProfileForm && $user)
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">{{ $user->profile ? 'Edit Profile' : 'Add Profile' }}</h5>
                    </div>
                    <div class="panel-body">
                        <form wire:submit.prevent="saveUserProfile">
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
                                <input type="text" class="form-control" wire:model="id_no" value="{{ $user->profile->id_no ?? '' }}">
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

<!-- Ensure JavaScript is properly loaded -->
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
@endpush





{{-- 

<div class="w-3/4 p-6">
    @if ($user)
        <h2 class="text-xl font-bold">{{ $user->email }}</h2>
        <div class="mt-4">
            <label class="block">Fullname</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->fullname_en }}" readonly>
        </div>
        <div class="mt-4">
            <label class="block">Nickname</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->nickname }}" readonly>
        </div>
        <div class="mt-4">
            <label class="block">Description</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->description }}" readonly>
        </div>
        <div class="mt-4">
            <label class="block">Card ID No</label>
            <input type="text" class="border rounded w-full p-2" value="{{ $user->profile->card_id_no }}" readonly>
        </div>
        <div class="flex space-x-4 mt-6">
            <button class="bg-green-500 text-white px-4 py-2 rounded">Edit</button>
            <button class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
        </div>
    @else
        <p>Select a user to view details</p>
    @endif
</div> --}}
