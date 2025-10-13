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
{{-- Removed legacy SweetAlert v1 include to ensure SweetAlert2 is used consistently --}}
{{-- <script src="{{ asset('js/sweetalert.js') }}"></script> --}}



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
                    window.showSuccessAlert('Success!', 'Nickname updated successfully!');
                } else {
                    // Show error message with SweetAlert2
                    window.showErrorAlert('Error', data.message || 'Error updating nickname');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showErrorAlert('Error', 'Error updating nickname. Please try again.');
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
                    window.showSuccessAlert('Success!', 'Password changed successfully!');
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
                        window.showErrorAlert('Error', data.message || 'Error changing password. Please try again.');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showErrorAlert('Error', 'Error changing password. Please try again.');
            });
        });
    }
});
</script>
<!-- /page scripts -->

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert Global Utilities -->
<script>
/**
 * Global SweetAlert utilities for consistent UI alerts across the application
 * These functions provide standardized interfaces for all SweetAlert interactions
 */

// Default configuration constants
window.SWEETALERT_DEFAULTS = {
    width: '400px',
    allowOutsideClick: true,
    allowEscapeKey: true,
    confirmButtonColor: '#007bff',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'OK',
    cancelButtonText: 'Cancel'
};

/**
 * Normalize boolean values from various sources (strings, numbers, booleans)
 * @param {*} value - The value to normalize
 * @param {boolean} defaultValue - Default value if normalization fails
 * @returns {boolean} Normalized boolean value
 */
window.resolveBoolean = function(value, defaultValue = false) {
    if (typeof value === 'boolean') {
        return value;
    }
    
    if (typeof value === 'string') {
        const lowerValue = value.toLowerCase();
        if (lowerValue === 'true' || lowerValue === '1' || lowerValue === 'yes') {
            return true;
        }
        if (lowerValue === 'false' || lowerValue === '0' || lowerValue === 'no') {
            return false;
        }
    }
    
    if (typeof value === 'number') {
        return value !== 0;
    }
    
    return defaultValue;
};

/**
 * Show a SweetAlert with standardized configuration
 * Handles info, success, error, and warning alerts
 * @param {Object} data - Alert configuration object
 */
window.showSweetAlert = function(data) {
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert is not loaded!');
        // Fallback to native alert
        const fallback = Array.isArray(data) ? data[0] : (data || {});
        const message = (fallback.title || 'Notice') + ': ' + 
                       (fallback.html ? fallback.html.replace(/<[^>]*>/g, '') : (fallback.text || ''));
        alert(message);
        return;
    }

    const eventData = Array.isArray(data) ? data[0] : (data || {});
    
    // Normalize boolean values
    const showConfirmButton = window.resolveBoolean(eventData.showConfirmButton, true);
    const allowOutsideClick = window.resolveBoolean(eventData.allowOutsideClick, true);
    const allowEscapeKey = window.resolveBoolean(eventData.allowEscapeKey, true);
    
    // Parse timer value
    const timerValue = eventData.timer !== undefined ? Number(eventData.timer) : undefined;
    const timer = Number.isNaN(timerValue) ? undefined : timerValue;

    // Remove any existing modals/overlays that might interfere
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');

    // Create SweetAlert with standardized configuration
    Swal.fire({
        title: eventData.title || '{{ __t('alert.success_title', 'Success') }}',
        text: eventData.text || undefined,
        html: eventData.html || undefined,
        icon: eventData.icon || 'success',
        timer: timer,
        showConfirmButton: showConfirmButton,
        confirmButtonText: eventData.confirmButtonText || window.SWEETALERT_DEFAULTS.confirmButtonText,
        confirmButtonColor: eventData.confirmButtonColor || window.SWEETALERT_DEFAULTS.confirmButtonColor,
        allowOutsideClick: allowOutsideClick,
        allowEscapeKey: allowEscapeKey,
        width: eventData.width || window.SWEETALERT_DEFAULTS.width,
        customClass: {
            popup: 'swal-wide'
        }
    }).catch((error) => {
        console.error('SweetAlert error:', error);
        // Fallback to native alert if SweetAlert fails
        const message = (eventData.title || 'Notice') + ': ' + 
                       (eventData.html ? eventData.html.replace(/<[^>]*>/g, '') : (eventData.text || ''));
        alert(message);
    });
};

/**
 * Show a SweetAlert confirmation dialog with callback support
 * Handles confirmation dialogs with standardized configuration
 * @param {Object} data - Alert configuration object with callback information
 */
window.showSweetAlertConfirm = function(data) {
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert is not loaded!');
        // Fallback to native confirm
        const fallback = Array.isArray(data) ? data[0] : (data || {});
        const message = fallback.text || fallback.title || 'Are you sure?';
        if (confirm(message)) {
            // Execute callback if provided
            if (fallback.callbackMethod && window.Livewire) {
                window.Livewire.dispatch(fallback.callbackMethod, fallback.callbackParams || {});
            }
        }
        return;
    }

    const eventData = Array.isArray(data) ? data[0] : (data || {});
    
    // Normalize boolean values
    const showCancelButton = window.resolveBoolean(eventData.showCancelButton, true);
    const allowOutsideClick = window.resolveBoolean(eventData.allowOutsideClick, false);
    const allowEscapeKey = window.resolveBoolean(eventData.allowEscapeKey, true);

    // Remove any existing modals/overlays that might interfere
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    
    // Create SweetAlert with proper configuration
    Swal.fire({
        title: eventData.title || '{{ __t('alert.confirm_title', 'Confirm Action') }}',
        text: eventData.text || '{{ __t('alert.confirm_message', 'Are you sure you want to continue?') }}',
        html: eventData.html || undefined,
        icon: eventData.icon || 'warning',
        showCancelButton: showCancelButton,
        confirmButtonText: eventData.confirmButtonText || '{{ __t('alert.confirm_action', 'Yes, Continue') }}',
        cancelButtonText: eventData.cancelButtonText || window.SWEETALERT_DEFAULTS.cancelButtonText,
        confirmButtonColor: eventData.confirmButtonColor || '#dc3545',
        cancelButtonColor: eventData.cancelButtonColor || window.SWEETALERT_DEFAULTS.cancelButtonColor,
        allowOutsideClick: allowOutsideClick,
        allowEscapeKey: allowEscapeKey,
        width: eventData.width || '420px',
        customClass: {
            popup: 'swal-wide'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Execute callback if provided
            if (eventData.callbackMethod && window.Livewire) {
                window.Livewire.dispatch(eventData.callbackMethod, eventData.callbackParams || {});
            }
        }
    }).catch((error) => {
        console.error('SweetAlert confirmation error:', error);
        // Fallback to native confirm if SweetAlert fails
        const message = eventData.text || eventData.title || 'Are you sure?';
        if (confirm(message)) {
            if (eventData.callbackMethod && window.Livewire) {
                window.Livewire.dispatch(eventData.callbackMethod, eventData.callbackParams || {});
            }
        }
    });
};

/**
 * Show a standardized success alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {Object} options - Additional options
 */
window.showSuccessAlert = function(title, message, options = {}) {
    window.showSweetAlert({
        title: title,
        text: message,
        icon: 'success',
        timer: 3000,
        showConfirmButton: false,
        allowOutsideClick: false,
        ...options
    });
};

/**
 * Show a standardized error alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {Object} options - Additional options
 */
window.showErrorAlert = function(title, message, options = {}) {
    window.showSweetAlert({
        title: title,
        text: message,
        icon: 'error',
        showConfirmButton: true,
        confirmButtonText: '{{ __t('alert.try_again', 'Try Again') }}',
        confirmButtonColor: '#dc3545',
        ...options
    });
};

/**
 * Show a standardized confirmation dialog
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {Function} onConfirm - Callback function for confirmation
 * @param {Object} options - Additional options
 */
window.showConfirmDialog = function(title, message, onConfirm, options = {}) {
    window.showSweetAlertConfirm({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '{{ __t('alert.confirm_action', 'Yes, Continue') }}',
        cancelButtonText: '{{ __t('alert.cancel_action', 'Cancel') }}',
        confirmButtonColor: '#dc3545',
        width: '420px',
        allowOutsideClick: false,
        ...options,
        callbackMethod: onConfirm
    });
};

console.log('SweetAlert utilities loaded successfully');
</script>

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

// Session expiration handling
(function() {
    let sessionCheckInterval;
    let lastActivity = Date.now();
    
    // Track user activity
    function updateLastActivity() {
        lastActivity = Date.now();
    }
    
    // Check for session expiration
    function checkSessionExpiration() {
        const now = Date.now();
        const timeSinceLastActivity = now - lastActivity;
        
        // Check if session might be expired (5 minutes for force password change, 2 hours for normal)
        const sessionTimeout = {{ Auth::check() && Auth::user()->request_change_pass ? 5 * 60 * 1000 : 120 * 60 * 1000 }};
        
        if (timeSinceLastActivity > sessionTimeout) {
            // Session likely expired, redirect to login
            console.warn('Session appears to be expired, redirecting to login');
            window.location.href = '{{ route("login") }}';
            return;
        }
        
        // Show warning when approaching timeout
        const warningTime = sessionTimeout - (5 * 60 * 1000); // 5 minutes before timeout
        if (timeSinceLastActivity > warningTime && timeSinceLastActivity < sessionTimeout) {
            console.warn('Session will expire soon');
        }
    }
    
    // Initialize session monitoring
    function initSessionMonitoring() {
        // Track various user activities
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(function(event) {
            document.addEventListener(event, updateLastActivity, true);
        });
        
        // Check session every 30 seconds
        sessionCheckInterval = setInterval(checkSessionExpiration, 30000);
        
        // Check on page visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(sessionCheckInterval);
            } else {
                updateLastActivity();
                sessionCheckInterval = setInterval(checkSessionExpiration, 30000);
            }
        });
    }
    
    // Start session monitoring when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSessionMonitoring);
    } else {
        initSessionMonitoring();
    }
})();
</script>


@stack('scripts')