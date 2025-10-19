<!--Page Container-->
<section class="main-container">
    <!--Page Header-->
    <div class="header">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-display4"></i> {{ __t('dashboard.title', 'Dashboard') }}
            </div>
            <ul class="breadcrumb">
                <li><a href="/">{{ __t('common.home', 'Home') }}</a></li>
                <li class="active"><a href="#">{{ __t('dashboard.overview', 'Overview') }}</a></li>
            </ul>
        </div>
    </div>
    <!--/Page Header-->

    <div class="container-fluid page-content">
        <div class="row">
            <div class="col-lg-12">
                @livewire('dashboard.user-context')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @livewire('dashboard.kpi-summary')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-12">
                @livewire('dashboard.performance-switcher')
            </div>
            <div class="col-lg-4 col-md-12">
                @livewire('dashboard.action-alerts')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-12">
                @livewire('dashboard.inventory-health-panel')
            </div>
            <div class="col-lg-4 col-md-12">
                @livewire('dashboard.activity-stream')
            </div>
        </div>
    </div>

    <!--Footer -->
    <footer class="footer-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="footer-left">
                        <span>
                            Ac 2016 Penguin&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;Web app kit by
                            <a href="http://followtechnique.com" target="_blank">FollowTechnique</a>
                            &nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;Version - 1.1.0
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/Footer-->
</section>
<!--/Page Container-->
