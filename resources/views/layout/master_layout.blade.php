<!doctype html>
<html style="height:100%">

@include('includes.head_layout')

<body>
	@stack('body-styles')

	@yield('content')
	@show

</body>

@include('includes.global_scripts')

</html>