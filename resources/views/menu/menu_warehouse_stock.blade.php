@extends('layout.master_layout')

@section('title', 'Stock Operations')

@section('content')
<body class="material-menu" id="top">
	<div id="preloader">
		<div id="status">
			<div class="loader">
				<div class="loader-inner ball-pulse">
				  <div class="bg-blue"></div>
				  <div class="bg-amber"></div>
				  <div class="bg-success"></div>
				</div>
			</div>
		</div>
	</div>
	
	@include('layout.main_nav_header')

	@include('layout.main_sidebar')
	
	@include('layout.warehouse_stock_operation_container')


	{{-- <!--@include('includes.theme_switch')--> --}}

	@include('includes.scroll_top')


@endsection


@push('styles')
<style>
    .alert-component {
        border-left: 3px solid #2196F3;
    }
    
    .panel-flat {
        border-radius: 3px;
    }
    
    .text-semibold {
        font-weight: 600;
    }
    
    .mt-3 {
        margin-top: 1rem;
    }
</style>
@endpush


