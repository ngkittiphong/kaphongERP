	<!--Page Container-->
	<section class="main-container">					
		
        <!--Page Header-->
        <div class="header no-margin-bottom">
            <div class="header-content">
                <div class="page-title">
                    <i class="icon-user position-left"></i> User
                </div>
                <div class="elements">
                    <button type="button" class="btn btn-sm btn-success btn-labeled"><b><i class="icon-plus3"></i></b> Add new user</button>
                </div>
            </div>
        </div>		
        <!--/Page Header-->
        
        <div class="container-fluid page-people">
            <div class="row">
                <div class="col-md-3 col-sm-3 secondary-sidebar">
                    <div class="sidebar-content">
                        @livewire('user-list')
                    </div>
                </div>
                <div class="col-md-7 col-sm-7">
                    <div class="people-container">
                        <div id="contact" style="display:inherit">
                            <div class="row">
                                <div class="col-md-12 p-t-20 p-b-20">
                                    <ul class="list-inline pull-right actions">
                                        <li>
                                            <div class="dropdown clearfix"> 
                                                <button class="btn btn-link btn-icon dropdown-toggle no-padding-left" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-circles icon-1x"></i></button> 
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-l-20" aria-labelledby="dropdownMenu1"> 
                                                    <li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" class="styled" checked="checked">
                                                                Family
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" class="styled">
                                                                Friends
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" class="styled">
                                                                Work
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" class="styled">
                                                                Following
                                                            </label>
                                                        </div>
                                                    </li>
                                                </ul> 
                                            </div>
                                        </li>																		
                                        <li><a href="people.htm#" data-popup="tooltip" data-placement="left" data-original-title="Edit contact" class="p-t-5"><i class="icon-pencil icon-1x position-left"></i></a></li>
                                        <li><a href="people.htm#" data-popup="tooltip" data-placement="left" data-original-title="Add to favourites" class="p-t-5"><i class="icon-heart6 icon-1x position-left"></i></a></li>
                                        <li>
                                            <div class="dropdown clearfix"> 
                                                <button class="btn btn-link btn-icon dropdown-toggle no-padding-left" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-more2 icon-1x"></i></button> 
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right m-r-10" aria-labelledby="dropdownMenu2"> 
                                                    <li><a href="people.htm#"><i class="icon-user"></i> View profile</a></li> 
                                                    <li><a href="people.htm#"><i class="icon-user-plus"></i> Add to groups</a></li> 
                                                    <li><a href="people.htm#"><i class="icon-trash"></i> Delete contact</a></li> 													
                                                    <li class="divider"></li>
                                                    <li><a href="people.htm#"><i class="icon-phone2"></i> Call contact</a></li>
                                                </ul> 
                                            </div>
                                        </li>																		
                                    </ul>
                                </div>
                            </div>
                        @livewire('user-profile')
                        </div>
                    </div>
                </div>
            </div> 
    </div>
</section>
<!--/Page Container-->