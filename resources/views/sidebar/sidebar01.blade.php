<div role="tabpanel" class="tab-pane active fadeIn" id="menu">
    <ul class="sidebar-accordion">
    
        <li class="list-title">Main</li>
        <li>
            <a href="index2.htm#"><i class="icon-display4"></i><span class="list-label"> Dashboards</span></a>
            <ul>
                <li><a href="index2.htm">Analytical dashboard</a></li>								
            </ul>
        </li>
        <li class="list-title">User Management</li>	
        <li><a href="/menu/menu_user"><i class="icon-users2"></i> <span>Users list</span></a></li>


        <li class="list-title">Sell</li>	
        <li><a href="https://pos.ultimatefosters.com/pos/create"><i class="icon-versions"></i> <span>POS</span></a></li>	

        <li class="list-title">Wherehouse</li>	
        <li><a href=""><i class="icon-briefcase"></i> <span>Wherehouse</span></a></li>	

        <li class="list-title">Products</li>						

        <li>
            <a href="index2.htm#"><i class="icon-alignment-unalign"></i> <span>Products</span></a>
            <ul>							
                <li><a href="datatable_extension_row_reorder.htm">Products</a></li>
                <li><a href="datatable_extension_column_reorder.htm">Category</a></li>																				
            </ul>
        </li>													
    </ul>
</div>


<div role="tabpanel" class="tab-pane email fade" id="email">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <button type="button" class="btn bg-success btn-labeled btn-lg btn-block m-t-5" data-toggle="modal" data-target="#new-email"><b><i class="icon-pen-plus"></i></b> Compose new mail</button>
            <div class="email-buttons">
                <div class="row m-t-5">
                    <div class="col-xs-6 no-padding-left">
                        <button class="btn bg-primary btn-block btn-float btn-float-lg" type="button"><i class="icon-inbox"></i> <span>Inbox</span></button>
                        <button class="btn bg-success btn-block btn-float btn-float-lg" type="button"><i class="icon-inbox-alt"></i> <span>Sent</span></button>
                    </div>
                    
                    <div class="col-xs-6 no-padding-right">
                        <button class="btn bg-info btn-block btn-float btn-float-lg" type="button"><i class="icon-floppy-disk"></i> <span>Draft</span></button>
                        <button class="btn bg-danger btn-block btn-float btn-float-lg" type="button"><i class="icon-trash"></i> <span>Trash</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="menu-list m-t-10 m-b-20">
        <li class="list-title">Folders</li>
        <li><a href="index2.htm#"><i class="icon-home2"></i> Home <span class="badge badge-info">4</span></a></li>
        <li><a href="index2.htm#"><i class="icon-briefcase3"></i> Work <span class="badge badge-warning">16</span></a></li>
        <li><a href="index2.htm#"><i class="icon-files-empty2"></i> Documents</a></li>
        <li><a href="index2.htm#"><i class="icon-images2"></i> Images</a></li>
        <li><a href="index2.htm#"><i class="icon-flag7"></i> Flagged</a></li>						
    </ul>
    <h6 class="mt-20 text-uppercase text-semibold">Completeness stats</h6>
    <label>Disk space used <span>80%</span></label>
    <div class="progress progress-xxs">
        <div class="progress-bar progress-bar-danger" style="width: 80%">
            <span class="sr-only">80% Complete</span>
        </div>
    </div>
</div>


<div role="tabpanel" class="tab-pane profile fade" id="profile">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="text-center">
                <img src="{{ asset('assets/images/faces/face7.png') }}" class="img-responsive img-circle user-avatar" alt=""/>
                <h4 class="no-margin-bottom m-t-10">Hi! Ann Porter</h4>
                <div class="text-light text-size-small text-white">Company Secretary</div>							
            </div>
        </div>
    </div>
    <div class="row m-t-10 connect-buttons">
        <div class="col-xs-6 p-r-5">
            <button type="button" class="btn btn-block btn-info btn-rounded"><i class="icon-twitter position-left"></i> Follow</button>
        </div>
        <div class="col-xs-6 p-l-5">
            <div class="btn-group dropleft">
              <button type="button" class="btn btn-block btn-danger btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-share3 position-left"></i>  Connect
              </button>
              <ul class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                <li><a href="index2.htm#"><i class="icon-comment-discussion"></i> Chat with user</a></li>
                <li><a href="index2.htm#"><i class="icon-phone-wave"></i> Call user</a></li>
                <li><a href="index2.htm#"><i class="icon-comments"></i> Send message</a></li>
              </ul>
            </div>							
        </div>
    </div>
    <div class="row m-t-20 followers text-muted">
        <div class="col-xs-6 text-center">
            <h3 class="text-semibold no-margin">400+</h3>
            <div>Connections</div>
        </div>
        <div class="col-xs-6 text-center">
            <h3 class="text-semibold no-margin">1200+</h3>
            <div>Followers</div>
        </div>
    </div>
    <div class="row m-t-10 users-online">
        <div class="col-xs-12">
            <div class="leftbar-heading text-semibold m-b-15">FRIENDS ONLINE</div>
            <ul class="media-list">
                <li class="media">
                    <a href="index2.htm#" class="media-left"><img src="{{ asset('assets/images/faces/face5.png') }}" class="img-sm img-circle" alt=""></a>
                    <div class="media-body">
                        <a href="index2.htm#" class="media-heading">Florence Douglas</a>
                        <span class="text-size-mini text-muted display-block">Online</span>
                    </div>
                    <div class="media-right media-middle">
                        <span class="status-mark bg-success"></span>
                    </div>
                </li>

                <li class="media">
                    <a href="index2.htm#" class="media-left"><img src="{{ asset('assets/images/faces/face6.png') }}" class="img-sm img-circle" alt=""></a>
                    <div class="media-body">
                        <a href="index2.htm#" class="media-heading">Eugine Turner</a>
                        <span class="text-size-mini text-muted display-block">Busy</span>
                    </div>
                    <div class="media-right media-middle">
                        <span class="status-mark bg-danger-light"></span>
                    </div>
                </li>

                <li class="media">
                    <a href="index2.htm#" class="media-left"><img src="{{ asset('assets/images/faces/face7.png') }}" class="img-sm img-circle" alt=""></a>
                    <div class="media-body">
                        <a href="index2.htm#" class="media-heading">Jacqueline Howell</a>
                        <span class="text-size-mini text-muted display-block">Online</span>
                    </div>
                    <div class="media-right media-middle">
                        <span class="status-mark bg-success"></span>
                    </div>
                </li>

                <li class="media">
                    <a href="index2.htm#" class="media-left"><img src="{{ asset('assets/images/faces/face8.png') }}" class="img-sm img-circle" alt=""></a>
                    <div class="media-body">
                        <a href="index2.htm#" class="media-heading">Marilyn Romero</a>
                        <span class="text-size-mini text-muted display-block">Away</span>
                    </div>
                    <div class="media-right media-middle">
                        <span class="status-mark bg-warning-light"></span>
                    </div>
                </li>

                <li class="media">
                    <a href="index2.htm#" class="media-left"><img src="{{ asset('assets/images/faces/face9.png') }}" class="img-sm img-circle" alt=""></a>
                    <div class="media-body">
                        <a href="index2.htm#" class="media-heading">Andrew Brewer</a>
                        <span class="text-size-mini text-muted display-block">Invisible</span>
                    </div>
                    <div class="media-right media-middle">
                        <span class="status-mark bg-grey-light"></span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>


<div role="tabpanel" class="tab-pane settings fade" id="settings">
    <div class="leftbar-heading m-b-5">GENERAL SETTINGS</div>
    <div class="row">
        <div class="col-xs-9">
            <p class="text-size-mini">Make calls to friends and family right from your account.</p>
        </div>
        <div class="col-xs-2">
            <div class="checkbox checkbox-switchery switchery-xs">						
                <label>
                    <input type="checkbox" class="switchery" checked="checked">							
                </label>
            </div>
        </div>
    </div>					
    <div class="leftbar-heading m-t-10 m-b-5">SECURITY SETTINGS</div>
    <div class="row">
        <div class="col-xs-9">
            <p class="text-size-mini">Get notified when someone else is trying to access your account.</p>
        </div>
        <div class="col-xs-2">
            <div class="checkbox checkbox-switchery switchery-xs">						
                <label>
                    <input type="checkbox" class="switchery" checked="checked">							
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-9">
            <p class="text-size-mini">Use your phone when login as an extra layer of security.</p>
        </div>
        <div class="col-xs-2">
            <div class="checkbox checkbox-switchery switchery-xs">						
                <label>
                    <input type="checkbox" class="switchery">							
                </label>
            </div>
        </div>
    </div>					
</div>