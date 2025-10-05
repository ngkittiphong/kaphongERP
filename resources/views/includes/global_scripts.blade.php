<!-- Global scripts -->
<script src="{{ asset('js/jquery.js') }}"></script>	
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/forms/uniform.min.js') }}"></script>

{{-- If a child view defines this section, skip global script --}}
@unless (View::hasSection('skip_global_script'))

<!--<script src="{{ asset('js/jquery.ui.js') }}"></script>--> 
<!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
<script src="{{ asset('js/nav.accordion.js') }}"></script>
<script src="{{ asset('js/hammerjs.js') }}"></script>
<script src="{{ asset('js/jquery.hammer.js') }}"></script>
<script src="{{ asset('js/scrollup.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
<!--{{-- <script src="{{ asset('js/jquery.slimscroll.js') }}"></script> --}}-->
<script src="{{ asset('js/smart-resize.js') }}"></script>
<!--<script src="{{ asset('js/blockui.min.js') }}"></script>-->
<!--<script src="{{ asset('js/wow.min.js') }}"></script>-->
<script src="{{ asset('js/fancybox.min.js') }}"></script>
<script src="{{ asset('js/venobox.js') }}"></script>

<script src="{{ asset('js/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('js/forms/switchery.js') }}"></script>
<script src="{{ asset('js/forms/select2.min.js') }}"></script>	
<script src="{{ asset('js/core.js') }}"></script>
<script src="{{ asset('js/sweetalert.js') }}"></script>



<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/forms/daterangepicker.js') }}"></script>





<!-- /global scripts -->
<!-- Page scripts -->
<!--{{-- <script src="https://www.google.com/jsapi"></script> --}}-->
<!--{{-- <script src="{{ asset('js/pages/dashboard_default.js') }}"></script> --}}-->
<!--<script src="{{ asset('js/maps/jvectormap/jvectormap.min.js') }}"></script>-->
<!--<script src="{{ asset('js/maps/jvectormap/map_files/world.js') }}"></script>-->

<!-- Change Nickname Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveNicknameBtn = document.getElementById('save-nickname');
    if (saveNicknameBtn) {
        saveNicknameBtn.addEventListener('click', function() {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Nickname updated successfully!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    // Show error message with SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error updating nickname',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating nickname. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        });
    }
});
</script>

<!-- Change Password Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const savePasswordBtn = document.getElementById('save-password');
    if (savePasswordBtn) {
        savePasswordBtn.addEventListener('click', function() {
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Reset error states
            document.getElementById('current-password').classList.remove('is-invalid');
            document.getElementById('new-password').classList.remove('is-invalid');
            document.getElementById('confirm-password').classList.remove('is-invalid');
            document.getElementById('current-password-error').textContent = '';
            document.getElementById('new-password-error').textContent = '';
            document.getElementById('confirm-password-error').textContent = '';

            // Validate passwords match
            if (newPassword !== confirmPassword) {
                document.getElementById('confirm-password').classList.add('is-invalid');
                document.getElementById('confirm-password-error').textContent = 'Passwords do not match';
                return;
            }

            // Validate password length
            if (newPassword.length < 8) {
                document.getElementById('new-password').classList.add('is-invalid');
                document.getElementById('new-password-error').textContent = 'Password must be at least 8 characters long';
                return;
            }

            fetch('/users/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    current_password: currentPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal
                    $('#change-password-modal').modal('hide');
                    
                    // Clear form
                    document.getElementById('change-password-form').reset();
                    
                    // Show success message with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Password changed successfully!',
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    // Show error messages
                    if (data.errors) {
                        if (data.errors.current_password) {
                            document.getElementById('current-password').classList.add('is-invalid');
                            document.getElementById('current-password-error').textContent = data.errors.current_password[0];
                        }
                        if (data.errors.new_password) {
                            document.getElementById('new-password').classList.add('is-invalid');
                            document.getElementById('new-password-error').textContent = data.errors.new_password[0];
                        }
                        if (data.errors.confirm_password) {
                            document.getElementById('confirm-password').classList.add('is-invalid');
                            document.getElementById('confirm-password-error').textContent = data.errors.confirm_password[0];
                        }
                    } else {
                        // Show error message with SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error changing password. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error changing password. Please try again.',
                    confirmButtonText: 'OK'
                });
            });
        });
    }
});
</script>
<!-- /page scripts -->

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endunless

<script>
//	$(function() {
//		$(".styled, .multiselect-container input").uniform({
//			radioClass: 'choice'
//		});
//		$('input,textarea').focus(function(){
//		   $(this).data('placeholder',$(this).attr('placeholder'))
//				  .attr('placeholder','');
//		}).blur(function(){
//		   $(this).attr('placeholder',$(this).data('placeholder'));
//		});
//	});

// Language switching functionality
$(document).ready(function() {
    console.log('🌍 Language switcher script loaded');
    console.log('Current locale from server:', '{{ session("locale", "en") }}');
    
    // Wait a bit to ensure all elements are loaded
    setTimeout(function() {
        console.log('🔍 Setting up language switcher event handlers');
        
        // Handle language switcher clicks
        $(document).on('click', '.language-option', function(e) {
            e.preventDefault();
            
            const locale = $(this).data('locale');
            const currentLocale = '{{ session("locale", "en") }}';
            
            console.log('🔄 Language switch clicked:', locale, 'Current:', currentLocale);
            console.log('Element clicked:', $(this));
            
            if (locale === currentLocale) {
                console.log('⚠️ Already selected locale, ignoring');
                return; // Already selected
            }
            
            // Show loading state
            const $switcher = $('.language-switcher-sidebar');
            const $currentDisplay = $('.current-language-display');
            const originalText = $('.current-locale').text();
            $('.current-locale').text('Switching...');
            $switcher.addClass('switching');
            
            // Get CSRF token from meta tag or form
            const token = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
            
            console.log('🔑 CSRF Token found:', token ? 'Yes' : 'No');
            console.log('🌐 Sending AJAX request to:', '{{ route("language.switch") }}');
            console.log('📦 Request data:', { locale: locale, _token: token });
            
            // Send AJAX request to switch language
            $.ajax({
                url: '{{ route("language.switch") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: {
                    locale: locale,
                    _token: token
                },
                success: function(response) {
                    console.log('✅ Language switch success:', response);
                    if (response.success) {
                        console.log('🎉 Server confirmed language switch success');
                        // Update the UI immediately
                        $('.language-option').removeClass('active');
                        $(`.language-option[data-locale="${locale}"]`).addClass('active');
                        
                        // Update sidebar language display
                        const newText = locale === 'th' ? 'ไทย' : 'English';
                        $('.current-locale').text(newText);
                        
                        console.log('🔄 Reloading page in 500ms to apply translations...');
                        // Reload the page to apply translations
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    } else {
                        // Handle error
                        console.error('❌ Language switch failed:', response.error);
                        $('.current-locale').text(originalText);
                        alert('Language switch failed: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Language switch error:', xhr.responseText, status, error);
                    console.error('📊 Response status:', xhr.status);
                    console.error('📊 Response headers:', xhr.getAllResponseHeaders());
                    $('.current-locale').text(originalText);
                    
                    // Show error message
                    let errorMsg = 'Failed to switch language. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    
                    alert('Error: ' + errorMsg);
                },
                complete: function() {
                    $switcher.removeClass('switching');
                }
            });
        });
        
        // Initialize current locale display
        const currentLocale = '{{ session("locale", "en") }}';
        const currentText = currentLocale === 'th' ? 'ไทย' : 'English';
        $('.current-locale').text(currentText);
        
        console.log('Language switcher initialized. Current locale:', currentLocale);
    }, 100);
});
</script>

<script>
<!-- Slim Script -->
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
</script>


@stack('scripts')