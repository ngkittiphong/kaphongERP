<!-- resources/views/livewire/user-profile.blade.php -->
    <div class="row p-l-10 p-r-10">
        <!-- 1) Show Loading Spinner (centered) when busy -->
        <div wire:loading.flex class="d-flex align-items-center justify-content-center w-100"
            style="position: fixed; top: 50%; left: 65%; transform: translate(-50%, -50%); z-index: 9999;">
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
            @if ($showAddUserForm)
                @include('livewire.user.user-profile_adduser')
            @elseif($user && $user->profile && $showEditProfileForm == false)
                @include('livewire.user.user-profile_tab')
            @elseif($showEditProfileForm && $user)
                @include('livewire.user.user-profile_tab')
            @elseif($user && $user->profile == null)
                {{-- <div class="form-group has-feedback has-feedback-left">
                    <button type="button" class="btn btn-sm btn-success btn-labeled"
                        wire:click="$dispatch('showEditProfileForm')">
                        <b><i class="icon-plus3"></i></b> Add User Profile
                    </button>
                </div> --}}
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
                    Livewire.dispatch('deleteUser', {
                        userId: userId
                    });
                }
            });
        }
        
        // Password change modal functions
        let currentUserId = null;
        
        function openPasswordModal(userId) {
            currentUserId = userId;
            // Update the username field in the modal
            const usernameField = document.querySelector('#passwordChangeModal #username');
            if (usernameField) {
                // Try to find the username from the link that was clicked
                const usernameText = document.querySelector(`a[data-user-id="${userId}"]`).previousElementSibling.textContent.trim();
                if (usernameText) {
                    usernameField.value = usernameText;
                }
            }
            $('#passwordChangeModal').modal('show');
        }
        
        function submitPasswordChange() {
            // Clear previous errors
            $('#new_password_error').text('');
            $('#new_password_confirmation_error').text('');
            
            // Get form values
            const newPassword = $('#new_password').val();
            const newPasswordConfirmation = $('#new_password_confirmation').val();
            const requestChangePass = $('#request_change_pass').is(':checked') ? 1 : 0;
            
            // Validate on client side
            let hasErrors = false;
            
            if (!newPassword) {
                $('#new_password_error').text('Password is required');
                hasErrors = true;
            } else if (newPassword.length < {{ \App\Services\ValidationRulesService::getPasswordRules()['min_length'] }}) {
                $('#new_password_error').text('Password must be at least {{ \App\Services\ValidationRulesService::getPasswordRules()['min_length'] }} characters');
                hasErrors = true;
            }
            
            if (!newPasswordConfirmation) {
                $('#new_password_confirmation_error').text('Please confirm your password');
                hasErrors = true;
            } else if (newPassword !== newPasswordConfirmation) {
                $('#new_password_confirmation_error').text('Passwords do not match');
                hasErrors = true;
            }
            
            if (hasErrors) {
                return;
            }
            
            // Use the stored user ID
            if (!currentUserId) {
                alert('User ID not found. Please refresh the page and try again.');
                return;
            }
            
            console.log('Changing password for user ID:', currentUserId);
            
            // Send AJAX request to change password
            $.ajax({
                url: '/users/' + currentUserId + '/change-password',
                type: 'POST',
                data: {
                    new_password: newPassword,
                    new_password_confirmation: newPasswordConfirmation,
                    request_change_pass: requestChangePass,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Password changed successfully!',
                    });
                    // Close modal
                    $('#passwordChangeModal').modal('hide');
                    // Clear form
                    $('#passwordChangeForm')[0].reset();
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.errors) {
                        // Display validation errors
                        if (response.errors.new_password) {
                            $('#new_password_error').text(response.errors.new_password[0]);
                        }
                        if (response.errors.new_password_confirmation) {
                            $('#new_password_confirmation_error').text(response.errors.new_password_confirmation[0]);
                        }
                    } else {
                        // Display general error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to change password. Please try again.',
                        });
                    }
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


    <script>
        function initSlim() {
            // Check if DataTable is already initialized and destroy it if exists
            // This prevents duplicate initialization errors
            console.log('slim script reload');
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
        }

        function normalizeImageSource(source) {
            console.debug('[normalizeImageSource] called with:', source);

            if (!source) {
                console.debug('[normalizeImageSource] source is falsy, returning null');
                return null;
            }

            if (source instanceof HTMLCanvasElement) {
                try {
                    const dataUrl = source.toDataURL('image/png');
                    console.debug('[normalizeImageSource] source is canvas, dataUrl:', dataUrl);
                    return dataUrl;
                } catch (error) {
                    console.error('[normalizeImageSource] Failed to convert Slim canvas to data URL:', error);
                    return null;
                }
            }

            if (source instanceof HTMLImageElement) {
                console.debug('[normalizeImageSource] source is image element, src:', source.src);
                return source.src || null;
            }

            if (typeof source === 'string' && source.trim() !== '') {
                console.debug('[normalizeImageSource] source is string:', source);
                return source;
            }

            console.debug('[normalizeImageSource] source did not match any type, returning null');
            return null;
        }

        function extractImage(data) {
            console.debug('[extractImage] called with:', data);

            if (!data) {
                console.debug('[extractImage] data is falsy, returning null');
                return null;
            }

            const candidates = [];

            if (data.output) {
                console.debug('[extractImage] data.output found:', data.output);
                candidates.push(data.output.image, data.output.data, data.output.dataUrl, data.output.src);
            }

            candidates.push(data.image, data.data, data.dataUrl, data.src);

            for (const candidate of candidates) {
                console.debug('[extractImage] checking candidate:', candidate);
                const normalized = normalizeImageSource(candidate);
                if (normalized) {
                    console.debug('[extractImage] found normalized candidate:', normalized);
                    return normalized;
                }
            }

            console.debug('[extractImage] no valid candidate found, returning null');
            return null;
        }

        function getSlimResultImage(slimElement) {
            console.debug('[getSlimResultImage] called with:', slimElement);

            if (!slimElement) {
                console.debug('[getSlimResultImage] slimElement is falsy, returning null');
                return null;
            }

            const possibleDatasets = [];

            if (slimElement.slim) {
                const controller = slimElement.slim;
                console.debug('[getSlimResultImage] slimElement.slim found:', controller);

                if (typeof controller.getData === 'function') {
                    try {
                        const data = controller.getData();
                        console.debug('[getSlimResultImage] controller.getData() result:', data);
                        possibleDatasets.push(data);
                    } catch (error) {
                        console.error('[getSlimResultImage] Error calling slim.getData():', error);
                    }
                }

                if (controller.data) {
                    console.debug('[getSlimResultImage] controller.data found:', controller.data);
                    possibleDatasets.push(controller.data);
                }

                if (controller._data) {
                    console.debug('[getSlimResultImage] controller._data found:', controller._data);
                    possibleDatasets.push(controller._data);
                }
            }

            if (typeof Slim !== 'undefined') {
                console.debug('[getSlimResultImage] Slim global is defined');
                if (typeof Slim.getData === 'function') {
                    try {
                        const data = Slim.getData(slimElement);
                        console.debug('[getSlimResultImage] Slim.getData() result:', data);
                        possibleDatasets.push(data);
                    } catch (error) {
                        console.error('[getSlimResultImage] Error calling Slim.getData:', error);
                    }
                }

                if (typeof Slim.getImages === 'function') {
                    try {
                        const images = Slim.getImages(slimElement);
                        console.debug('[getSlimResultImage] Slim.getImages() result:', images);
                        if (images) {
                            possibleDatasets.push(images);
                        }
                    } catch (error) {
                        console.error('[getSlimResultImage] Error calling Slim.getImages:', error);
                    }
                }
            }

            for (const dataset of possibleDatasets) {
                if (!dataset) {
                    console.debug('[getSlimResultImage] dataset is falsy, skipping');
                    continue;
                }

                const items = Array.isArray(dataset) ? dataset : [dataset];
                for (const item of items) {
                    console.debug('[getSlimResultImage] extracting image from item:', item);
                    const extracted = extractImage(item);
                    if (extracted) {
                        console.debug('[getSlimResultImage] extracted image:', extracted);
                        return extracted;
                    }
                }
            }

            const resultImage = slimElement.querySelector('.slim-result img.in');
            if (resultImage && resultImage.src) {
                console.debug('[getSlimResultImage] found .slim-result img.in:', resultImage.src);
                return resultImage.src;
            }

            const fallbackImage = slimElement.querySelector('.slim-area img');
            if (fallbackImage && fallbackImage.src) {
                console.debug('[getSlimResultImage] found .slim-area img:', fallbackImage.src);
                return fallbackImage.src;
            }

            console.debug('[getSlimResultImage] no image found, returning null');
            return null;
        }


        function setAvatarFromSlim() {
            const slim = document.getElementById('slim-avatar');
            console.debug('[setAvatarFromSlim] slim element:', slim);
            if (!slim) {
                console.debug('[setAvatarFromSlim] No slim element found, aborting.');
                return;
            }

            const base64Data = getSlimResultImage(slim);
            console.debug('[setAvatarFromSlim] base64Data:', base64Data);

            if (base64Data) {
                console.debug('[setAvatarFromSlim] Setting avatar on Livewire component.');
                @this.set('avatar', base64Data);
            } else {
                console.debug('[setAvatarFromSlim] No base64Data found, nothing to set.');
            }
        }

        function handleSlimSubmitForm(event) {
            event.preventDefault(); // Prevent the default form submission
            console.log('handleSlimSubmitForm');
            setAvatarFromSlim();
        }

        initSlim();

        document.addEventListener('livewire:initialized', () => {
            console.log('livewire:initialized');
            @this.on('profileUpdated', () => {
                console.log('profileUpdated');
                setTimeout(() => {
                    initSlim();
                    document.getElementById('updateUserProfileForm').addEventListener('submit',
                        handleSlimSubmitForm);
                }, 100);
            });

            @this.on('addUser', () => {
                console.log('addUser');
                setTimeout(() => {
                    initSlim();
                    document.getElementById('addUserForm').addEventListener('submit',
                        handleSlimSubmitForm);
                }, 100);
            });
        });
    </script>
@endpush
