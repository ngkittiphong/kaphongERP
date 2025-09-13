	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-user position-left"></i> User
            </div>
            @livewire('user.user-add-user-btn')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    @livewire('user.user-list')
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                @livewire('user.user-profile')
            </div>
        </div> 
    </div>
</section>
<!--/Page Container-->