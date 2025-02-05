<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Penguin - Responsive admin dashboard template by Followtechnique</title>	
	<link href="{{ asset('assets/images/favicon.ico') }}" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="{{ asset('assets/images/favicon.ico') }}" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="{{ asset('assets/images/favicon.ico') }}" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="{{ asset('assets/images/favicon.ico') }}" rel="apple-touch-icon" type="image/png">
	<link href="{{ asset('assets/images/favicon.ico') }}" rel="icon" type="image/png">
	<link href="{{ asset('assets/images/favicon.ico') }}" rel="shortcut icon">
	
	<!-- Global stylesheets -->	
	<link type="text/css" rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/icons/icomoon/icomoon.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/core.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap-extended.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/plugins.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/color-system.css') }}">

	
	<!-- extry stylesheets -->	
	{{-- If a child view defines this section, skip global styles --}}
    @unless (View::hasSection('skip_global_styles'))
	<link type="text/css" rel="stylesheet" href="{{ asset('css/animate.min.css') }}"> 
	<link type="text/css" rel="stylesheet" href="{{ asset('css/layout.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/components.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/loaders.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/responsive.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/fancybox/jquery.fancybox.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('index.htm#') }}" id="theme">
	@endunless
	<!-- /global stylesheets -->

		<!-- Page css -->
	<link href="{{ asset('assets/icons/weather/weather-icons.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/icons/weather/weather-icons-wind.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /page css -->

</head>