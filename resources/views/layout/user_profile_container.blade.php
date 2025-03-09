	<!--Page Container-->
<section class="main-container">					
		
    
        <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-user position-left"></i> User
            </div>
            <div class="elements">
                <button type="button" class="btn btn-sm btn-success btn-labeled"
                    wire:click="$dispatch('showAddUserForm')">
                    <b><i class="icon-plus3"></i></b> Add new user
                </button>

            </div>
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 85vh">
                    @livewire('user-list')
                </div>
            </div>
            <div class="col-md-8 col-sm-8">
                <!--<div class="people-container">-->
                    <!--<div id="contact" style="display:inherit">-->
                        <!--<div class="row">-->
                            <!--<div class="col-md-12 p-t-20 p-b-20">-->
                                @livewire('user-profile')
                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
            </div>
        </div> 
    </div>
</section>
<!--/Page Container-->