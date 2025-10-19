@extends('layout.master_layout')

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

    <section class="main-container">
        <div class="header no-margin-bottom">
            <div class="header-content">
                <div class="page-title">
                    <i class="icon-key position-left"></i> {{ __t('menu.permissions', 'Permissions') }}
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @livewire('user.permission-manager')
                </div>
            </div>
        </div>
    </section>

    @include('includes.scroll_top')
</body>
@endsection
