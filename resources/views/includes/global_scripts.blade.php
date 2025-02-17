<!-- Global scripts -->
<script src="{{ asset('js/jquery.js') }}"></script>	
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/forms/uniform.min.js') }}"></script>

{{-- If a child view defines this section, skip global script --}}
@unless (View::hasSection('skip_global_script'))
<script src="{{ asset('js/jquery.ui.js') }}"></script>
<script src="{{ asset('js/nav.accordion.js') }}"></script>
<script src="{{ asset('js/hammerjs.js') }}"></script>
<script src="{{ asset('js/jquery.hammer.js') }}"></script>
<script src="{{ asset('js/scrollup.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/smart-resize.js') }}"></script>
<script src="{{ asset('js/blockui.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/fancybox.min.js') }}"></script>
<script src="{{ asset('js/venobox.js') }}"></script>

<script src="{{ asset('js/forms/switchery.js') }}"></script>
<script src="{{ asset('js/forms/select2.min.js') }}"></script>	
<script src="{{ asset('js/core.js') }}"></script>
<!-- /global scripts -->
<!-- Page scripts -->
{{-- <script src="https://www.google.com/jsapi"></script> --}}
{{-- <script src="{{ asset('js/pages/dashboard_default.js') }}"></script> --}}
<script src="{{ asset('js/maps/jvectormap/jvectormap.min.js') }}"></script>
<script src="{{ asset('js/maps/jvectormap/map_files/world.js') }}"></script>
<!-- /page scripts -->

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endunless

<script>
	$(function() {
		$(".styled, .multiselect-container input").uniform({
			radioClass: 'choice'
		});
		$('input,textarea').focus(function(){
		   $(this).data('placeholder',$(this).attr('placeholder'))
				  .attr('placeholder','');
		}).blur(function(){
		   $(this).attr('placeholder',$(this).data('placeholder'));
		});
	});
</script>

@stack('scripts')