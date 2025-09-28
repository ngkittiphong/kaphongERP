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
<!-- /page scripts -->

{{-- SweetAlert2 --}}
<!--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->

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
            const $button = $('#languageDropdown');
            const $switcher = $('.language-switcher');
            const originalText = $button.find('.current-locale').text();
            $button.find('.current-locale').text('Switching...');
            $button.prop('disabled', true);
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
                        
                        // Update button text
                        const newText = locale === 'th' ? 'ไทย' : 'English';
                        $button.find('.current-locale').text(newText);
                        
                        console.log('🔄 Reloading page in 500ms to apply translations...');
                        // Reload the page to apply translations
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    } else {
                        // Handle error
                        console.error('❌ Language switch failed:', response.error);
                        $button.find('.current-locale').text(originalText);
                        alert('Language switch failed: ' + (response.error || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Language switch error:', xhr.responseText, status, error);
                    console.error('📊 Response status:', xhr.status);
                    console.error('📊 Response headers:', xhr.getAllResponseHeaders());
                    $button.find('.current-locale').text(originalText);
                    
                    // Show error message
                    let errorMsg = 'Failed to switch language. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    
                    alert('Error: ' + errorMsg);
                },
                complete: function() {
                    $button.prop('disabled', false);
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

@stack('scripts')