@error('form')
<div class="alert alert-danger">
    {{ $message }}
</div>
@enderror
<div class="card p-4">
    <h4>Add New User</h4>
    <!-- Unified form -->
    <form wire:submit.prevent="saveUserAndProfile" id="userForm">
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
                                    id="slim-avatar"
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
                                        accept="image/jpeg, image/png"
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

                    // Handle form submission
                    document.getElementById('userForm').addEventListener('submit', function(e) {
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