<!doctype html>
<html style="height:100%" lang="{{ session('locale', 'en') }}">

@include('includes.head_layout')

<body>
	<!--@stack('body-styles')-->

	@yield('content')
	@show

	<!-- Language Switcher moved to sidebar -->

	@livewireScripts
	{{-- @livewire('livewire-ui-modal')	 --}}
</body>

@include('includes.global_scripts')

</html>