@push('styles')
    <!-- Include Slim CSS -->
    <link rel="stylesheet" href="{{ asset('slim/css/slim.min.css') }}">
@endpush

<div role="tabpanel" class="tab-pane profile fade" id="profile">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="text-center">
                {{-- <img  class="img-responsive img-circle user-avatar" alt=""/> --}}
                <div
                    id="slim-avatar_panel"
                    class="slim"
                    data-size="300,300"
                    data-ratio="1:1"
                    data-shape="circle"
                    data-instant-edit="true"
                    data-service="{{ route('upload.avatar') }}"
                    data-token="{{ csrf_token() }}"
                    data-max-file-size="2"
                    data-label="{{ __t('profile.click_or_drag_photo', 'Click or drag your photo') }}"
                    {{-- data-push="true" [auto upload]--}}
                    data-save-initial-image="true"
                    data-will-request="handleRequest"
                    data-did-upload="handleUpload"
                    {{-- data-did-remove="handleAvatarDelete" --}}
                    data-did-receive-server-error="handleServerError"
                    style="
                        width: 200px; 
                        height: 200px;
                        margin: 0 auto;
                        border-radius: 50%;
                        overflow: hidden;"
                >
                    <!-- Default avatar image -->
                    <img 
                    src="{{ Auth::user()->profile && Auth::user()->profile->avatar ? Auth::user()->profile->avatar : asset('assets/images/faces/face_default.png') }}" 
                        alt="Default Icon" 
                        class="img-fluid"
                    />

                    <!-- File input for uploading/replacing the image -->
                    <input 
                        type="file" 
                        name="avatar" 
                        accept="image/jpeg, image/png, image/gif"
                    />
                </div>
                
                <!-- Confirm Upload Button (hidden by default) -->
                <div id="avatar-confirm-container" style="display: none; margin-top: 10px;">
                    <button type="button" id="confirm-avatar-upload" class="btn btn-success btn-sm">
                        <i class="icon-checkmark"></i> Confirm Upload
                    </button>
                    <button type="button" id="cancel-avatar-upload" class="btn btn-default btn-sm" style="margin-left: 5px;">
                        <i class="icon-cross"></i> Cancel
                    </button>
                </div>
                
                <!-- Delete Avatar Button -->
                <div class="text-center m-t-10">
                    <button type="button" class="btn btn-sm btn-danger" onclick="handleAvatarDelete()">
                        <i class="icon-trash"></i> Delete Avatar
                    </button>
                </div>
                
                <h4 class="no-margin-bottom m-t-10">{{ Auth::user()->profile->fullname_en ?? Auth::user()->username }}</h4>
                <div class="text-light text-size-small text-white">{{ Auth::user()->profile->nickname ?? '' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 m-t-5">
        <button type="button" class="btn btn-block bg-primary mt-33" data-toggle="modal" data-target="#change-nickname-modal">
            {{ __t('profile.change_nickname', 'Change Nickname') }}
        </button>
    </div>
    <div class="col-md-12 col-sm-12  m-t-5">
            <button type="button" class="btn btn-block bg-warning mt-33" data-toggle="modal" data-target="#change-password-modal">{{ __t('profile.change_password', 'Change Password') }}</button>
    </div>
    
    
    <div class="col-md-12 col-sm-12 m-t-40">
        <label>{{ __t('profile.sign_name', 'Sign name') }}</label>
        <div
            id="slim-sign_panel"
            class="slim"
            data-size="300,150"
            data-ratio="2:1"
            data-instant-edit="true"
            data-service="{{ route('upload.signature') }}"
            data-token="{{ csrf_token() }}"
            data-max-file-size="2"
            data-label="{{ __t('profile.click_or_drag_sign', 'Click or drag your sign') }}"
            data-save-initial-image="true"
             data-will-request="handleRequestSign"
             data-did-upload="handleUpload"
             data-did-receive-server-error="handleServerError"
            style="
                width: 200px; 
                height: 100px;
                margin: 0 auto;
                border-radius: 5%;
                overflow: hidden;"
        >
            <!-- Default sign image -->
            @if (Auth::user()->profile && Auth::user()->profile->sign_img)
                <img 
                    src="{{ Auth::user()->profile->sign_img }}" 
                    alt="Default Sign" 
                    class="img-fluid"
                />
            @endif

            <!-- File input for uploading/replacing the image -->
            <input 
                type="file" 
                name="signature" 
                accept="image/jpeg, image/png, image/gif"
            />
        </div>
        
        <!-- Delete Signature Button -->
        <div class="text-center m-t-10">
            <button type="button" class="btn btn-sm btn-danger" onclick="handleSignatureDelete()">
                <i class="icon-trash"></i> Delete Signature
            </button>
        </div>
    </div>
</div>

<!-- Add this JavaScript -->
<script>

function handleRequest(xhr) {
    // Add CSRF token to request
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    const slim = document.getElementById('slim-avatar_panel');
    console.debug('[handleRequest] slim element:', slim);
    if (!slim) {
        console.debug('[handleRequest] No slim element found, aborting.');
        return;
    }

    // Get base64 data from Slim - try multiple methods
    const base64Data = getSlimResultImage(slim);
    
    console.debug('[handleRequest] Final base64Data:', base64Data ? base64Data.substring(0, 50) + '...' : 'null');

    if (base64Data) {
        // Store for window access
        window.output = base64Data;
        
        // Add the base64 data to the FormData that Slim is sending
        if (xhr.upload && xhr.upload.addEventListener) {
            // For newer browsers, we can modify the request
            const formData = new FormData();
            formData.append('avatar', base64Data);
            formData.append('image', base64Data);
            formData.append('base64_image', base64Data);
            
            // Override the send method to include our data
            const originalSend = xhr.send;
            xhr.send = function(data) {
                // If data is FormData, append our base64
                if (data instanceof FormData) {
                    data.append('avatar', base64Data);
                    data.append('image', base64Data);
                    data.append('base64_image', base64Data);
                } else {
                    // If data is string/other, create new FormData
                    const newFormData = new FormData();
                    newFormData.append('avatar', base64Data);
                    newFormData.append('image', base64Data);
                    newFormData.append('base64_image', base64Data);
                    if (data) {
                        newFormData.append('original_data', data);
                    }
                    data = newFormData;
                }
                return originalSend.call(this, data);
            };
        }
    }
}

function handleRequestSign(xhr) {
    // Add CSRF token to request
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    const slim = document.getElementById('slim-sign_panel');
    console.debug('[handleRequestSign] slim element:', slim);
    if (!slim) {
        console.debug('[handleRequestSign] No slim-sign element found, aborting.');
        return;
    }

    // Get base64 data from Slim - try multiple methods
    const base64Data = getSlimResultImage(slim);
    console.debug('[handleRequestSign] Final base64Data:', base64Data ? base64Data.substring(0, 50) + '...' : 'null');

    if (base64Data) {
        window.sign_output = base64Data;

        if (xhr.upload && xhr.upload.addEventListener) {
            const originalSend = xhr.send;
            xhr.send = function(data) {
                if (data instanceof FormData) {
                    data.append('signature', base64Data);
                    data.append('image', base64Data);
                    data.append('base64_image', base64Data);
                    data.append('sign_img', base64Data);
                } else {
                    const newFormData = new FormData();
                    newFormData.append('signature', base64Data);
                    newFormData.append('image', base64Data);
                    newFormData.append('base64_image', base64Data);
                    newFormData.append('sign_img', base64Data);
                    if (data) {
                        newFormData.append('original_data', data);
                    }
                    data = newFormData;
                }
                return originalSend.call(this, data);
            };
        }
    }
}

function handleUpload(error, data, response) {
    if (error) {
        console.error('Upload error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Upload Error',
            text: 'Error uploading image. Please try again.',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Handle successful upload
    if (response.success) {
        console.log('Upload successful:', response.message);
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Image uploaded successfully!',
            timer: 1000,
            showConfirmButton: false
        }).then(() => {
            // Refresh the page to update all avatar instances
            window.location.reload();
        });
    }
}

function handleServerError(error, defaultError) {
    console.error('Server error:', error || defaultError);
    Swal.fire({
        icon: 'error',
        title: 'Server Error',
        text: 'Error uploading image. Please try again.',
        confirmButtonText: 'OK'
    });
}

function handleAvatarDelete() {
    console.debug('[handleAvatarDelete] called');
    
    // Show confirmation dialog
    Swal.fire({
        title: 'Delete Avatar?',
        text: 'Are you sure you want to remove your avatar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete avatar
            fetch('/upload/avatar/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the avatar image to default
                    const avatarImg = document.querySelector('#slim-avatar_panel img');
                    if (avatarImg) {
                        avatarImg.src = '{{ asset("assets/images/faces/face_default.png") }}';
                    }
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Avatar deleted successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Refresh the page to update all avatar instances
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete avatar',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting avatar:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete avatar. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function handleSignatureDelete() {
    console.debug('[handleSignatureDelete] called');
    
    // Show confirmation dialog
    Swal.fire({
        title: 'Delete Signature?',
        text: 'Are you sure you want to remove your signature?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete signature
            fetch('/upload/signature/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the signature image to default
                    const signImg = document.querySelector('#slim-sign_panel img');
                    if (signImg) {
                        signImg.src = '{{ asset("assets/images/faces/face_default.png") }}';
                    }
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Signature deleted successfully!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Refresh the page to update all signature instances
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete signature',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting signature:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete signature. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function confirmAvatarUpload() {
    // Handle avatar upload confirmation
    console.log('Avatar upload confirmed');
    // You can add specific logic here for avatar upload
}

function cancelAvatarUpload() {
    // Handle avatar upload cancellation
    console.log('Avatar upload cancelled');
    // You can add specific logic here for avatar upload cancellation
}

// Add event listener when DOM and modal are ready
function attachNicknameSaveListener() {
    const saveNicknameBtn = document.getElementById('save-nickname');
    if (saveNicknameBtn && !saveNicknameBtn.hasAttribute('data-listener-attached')) {
        saveNicknameBtn.addEventListener('click', function() {
            const nickname = document.getElementById('new-nickname').value;
            const form = document.getElementById('change-nickname-form');
            const errorDiv = document.getElementById('nickname-error');
            
            // Reset error state
            if (errorDiv) errorDiv.textContent = '';
            const nicknameInput = document.getElementById('new-nickname');
            if (nicknameInput) nicknameInput.classList.remove('is-invalid');

            fetch('/users/update-nickname', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nickname: nickname })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the displayed nickname
                    const nicknameDisplay = document.querySelector('.text-light.text-size-small.text-white');
                    if (nicknameDisplay) {
                        nicknameDisplay.textContent = nickname;
                    }
                    
                    // Close the modal
                    $('#change-nickname-modal').modal('hide');
                    
                    // Show success message using SweetAlert (system alert removed)
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Nickname updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    // Show error message
                    if (nicknameInput) nicknameInput.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = data.message || 'Error updating nickname';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (nicknameInput) nicknameInput.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = 'Error updating nickname. Please try again.';
            });
        });
        
        // Mark as attached to prevent duplicate listeners
        saveNicknameBtn.setAttribute('data-listener-attached', 'true');
    }
}
</script>

@push('scripts')
<script>
    function initSlimProfile() {
        console.log('Initializing slim for profile panel');
        
        // Remove old script if exists
        const oldScript = document.getElementById('slim-profile-script');
        if (oldScript) {
            oldScript.remove();
        }

        // Create and append new script
        const script = document.createElement('script');
        script.id = 'slim-profile-script';
        script.src = "{{ asset('slim/js/slim.kickstart.min.js') }}";
        script.onload = function() {
            console.log('Slim script loaded for profile panel');
            
            // Initialize slim after script loads
            setTimeout(() => {
                if (typeof Slim !== 'undefined') {
                    console.log('Slim is available and ready!');
                } else {
                    console.warn('Slim not available after script load');
                }
            }, 100);
        };
        script.onerror = function() {
            console.error('Failed to load Slim script');
            Swal.fire({
                icon: 'error',
                title: 'Script Error',
                text: 'Failed to load image upload script. Please refresh the page.',
                confirmButtonText: 'OK'
            });
        };
        document.body.appendChild(script);
    }

    // Initialize slim when the profile tab is shown
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize slim immediately
        initSlimProfile();
        
        // Add event listeners for confirm and cancel buttons
        const confirmBtn = document.getElementById('confirm-avatar-upload');
        const cancelBtn = document.getElementById('cancel-avatar-upload');
        
        if (confirmBtn) {
            confirmBtn.addEventListener('click', confirmAvatarUpload);
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', cancelAvatarUpload);
        }
        
        // Attach nickname save listener with a delay to ensure modal is loaded
        setTimeout(() => {
            attachNicknameSaveListener();
        }, 100);
        
        // Re-initialize when profile tab is clicked
        const profileTab = document.querySelector('#tab-profile a');
        if (profileTab) {
            profileTab.addEventListener('click', function() {
                setTimeout(() => {
                    initSlimProfile();
                    // Re-attach listeners when switching tabs
                    attachNicknameSaveListener();
                }, 100);
            });
        }
    });
</script>
@endpush