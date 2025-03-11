<div role="tabpanel" class="tab-pane active fadeIn" id="menu">
    <ul class="sidebar-accordion">
    
        <li class="list-title">Main</li>
        <li>
            <a href="index2.htm#"><i class="icon-display4"></i><span class="list-label"> Dashboards</span></a>
            <ul>
                <li><a href="index2.htm">Analytical dashboard</a></li>								
            </ul>
        </li>
        
        <li class="list-title">Products</li>						

        <li>
            <a href="index2.htm#"><i class="icon-alignment-unalign"></i> <span>Products</span></a>
            <ul>							
                <li><a href="datatable_extension_row_reorder.htm">Products</a></li>
                <li><a href="datatable_extension_column_reorder.htm">Category</a></li>																				
            </ul>
        </li>
        
        <li class="list-title">User Management</li>	
        <li><a href="/menu/menu_user"><i class="icon-users2"></i> <span>Users list</span></a></li>


        <li class="list-title">Sell</li>	
        <li><a href="https://pos.ultimatefosters.com/pos/create"><i class="icon-versions"></i> <span>POS</span></a></li>	

        <li class="list-title">Wherehouse</li>	
        <li><a href=""><i class="icon-briefcase"></i> <span>Wherehouse</span></a></li>	

        													
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
                <!--<img src="{{ asset('assets/images/faces/face7.png') }}" class="img-responsive img-circle user-avatar" alt=""/>-->
                <div
                    class="slim"
                    data-size="300,300"
                    data-ratio="1:1"
                    data-shape="circle"
                    data-instant-edit="true"
                    style="
                        width: 200px; 
                        height: 200px;
                        margin: 0 auto;
                        border-radius: 50%;
                        overflow: hidden;"
                >
                    <!-- Default avatar image -->
                    <img 
                        src="{{ asset('assets/images/faces/face_default.png') }}" 
                        alt="Default Icon" 
                        class="img-fluid"
                    />

                    <!-- File input for uploading/replacing the image -->
                    <input 
                        type="file" 
                        name="slim" 
                        accept="image/jpeg, image/png, image/gif"
                    />
                </div>
                
                <h4 class="no-margin-bottom m-t-10">session.profile.fullname</h4>
                <div class="text-light text-size-small text-white">session.type.name</div>							
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12  m-t-5">
            <button type="button" class="btn btn-block bg-primary mt-33" data-toggle="modal" data-target="#new-email"> change nickname</button>
    </div>
    <div class="col-md-12 col-sm-12  m-t-5">
            <button type="button" class="btn btn-block bg-warning mt-33" data-toggle="modal" data-target="#new-email"> change password</button>
    </div>
    
    
    <div class="col-md-12 col-sm-12 m-t-40">
        <label>Sign name</label>
        <div
            class="slim"
            data-size="300,150"
            data-ratio="1:2"
            data-instant-edit="true"
            style="
                width: 200px; 
                height: 100px;
                margin: 0 auto;
                border-radius: 5%;
                overflow: hidden;"
        >
            <!-- Default avatar image -->
            <img 
                src="{{ asset('assets/images/faces/face_default.png') }}" 
                alt="Default Icon" 
                class="img-fluid"
            />

            <!-- File input for uploading/replacing the image -->
            <input 
                type="file" 
                name="slim" 
                accept="image/jpeg, image/png, image/gif"
            />
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