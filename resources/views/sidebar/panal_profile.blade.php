<div role="tabpanel" class="tab-pane profile fade" id="profile">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="text-center">
                {{-- <img  class="img-responsive img-circle user-avatar" alt=""/> --}}
                <div
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
            <button type="button" class="btn btn-block bg-warning mt-33" data-toggle="modal" data-target="#new-email">{{ __t('profile.change_password', 'Change Password') }}</button>
    </div>
    
    
    <div class="col-md-12 col-sm-12 m-t-40">
        <label>{{ __t('profile.sign_name', 'Sign name') }}</label>
        <div
            class="slim"
            data-size="300,150"
            data-ratio="1:2"
            data-instant-edit="true"
            style="
                width: 200px; 
                height: 100px;
                margin: 0 auto;
                border-radius: 5%;
                overflow: hidden;"
        >
            <!-- Default avatar image -->
            <img 
                src="{{ asset('assets/images/faces/face_default.png') }}" 
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
    </div>
</div>

<!-- Add this modal HTML after your existing content but before the closing </div> -->
<div class="modal fade" id="change-nickname-modal" tabindex="-1" role="dialog" aria-labelledby="changeNicknameModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="changeNicknameModalLabel">{{ __t('profile.change_nickname', 'Change Nickname') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="change-nickname-form">
                    @csrf
                    <div class="form-group">
                        <label for="new-nickname">{{ __t('profile.new_nickname', 'New Nickname') }}</label>
                        <input type="text" 
                               class="form-control" 
                               id="new-nickname" 
                               name="nickname" 
                               value="{{ Auth::user()->profile->nickname ?? '' }}"
                               required>
                        <div class="invalid-feedback" id="nickname-error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __t('common.cancel', 'Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="save-nickname">{{ __t('common.save_changes', 'Save Changes') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Add this JavaScript after the div -->
<script>
function handleRequest(xhr) {
    // Add CSRF token to request
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
}

function handleUpload(error, data, response) {
    if (error) {
        console.error('Upload error:', error);
        alert('Error uploading image. Please try again.');
        return;
    }
    
    // Handle successful upload
    if (response.success) {
        // Refresh the avatar image
        const avatarImg = document.querySelector('.slim img');
        if (avatarImg) {
            avatarImg.src = response.path;
        }
        console.log('Upload successful:', response.message);
    }
}

function handleServerError(error, defaultError) {
    console.error('Server error:', error || defaultError);
    alert('Error uploading image. Please try again.');
}

document.getElementById('save-nickname').addEventListener('click', function() {
    const nickname = document.getElementById('new-nickname').value;
    const form = document.getElementById('change-nickname-form');
    const errorDiv = document.getElementById('nickname-error');
    
    // Reset error state
    errorDiv.textContent = '';
    document.getElementById('new-nickname').classList.remove('is-invalid');

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
            
            // Show success message (you can customize this)
            alert('Nickname updated successfully!');
        } else {
            // Show error message
            document.getElementById('new-nickname').classList.add('is-invalid');
            errorDiv.textContent = data.message || 'Error updating nickname';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('new-nickname').classList.add('is-invalid');
        errorDiv.textContent = 'Error updating nickname. Please try again.';
    });
});
</script>