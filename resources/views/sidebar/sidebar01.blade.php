@include('sidebar.panal_main_menu')


<div role="tabpanel" class="tab-pane email fade" id="email">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <button type="button" class="btn bg-success btn-labeled btn-lg btn-block m-t-5" data-toggle="modal" data-target="#new-email"><b><i class="icon-pen-plus"></i></b> {{ __t('sidebar.compose_new_mail', 'Compose new mail') }}</button>
            <div class="email-buttons">
                <div class="row m-t-5">
                    <div class="col-xs-6 no-padding-left">
                        <button class="btn bg-primary btn-block btn-float btn-float-lg" type="button"><i class="icon-inbox"></i> <span>{{ __t('sidebar.inbox', 'Inbox') }}</span></button>
                        <button class="btn bg-success btn-block btn-float btn-float-lg" type="button"><i class="icon-inbox-alt"></i> <span>{{ __t('sidebar.sent', 'Sent') }}</span></button>
                    </div>
                    
                    <div class="col-xs-6 no-padding-right">
                        <button class="btn bg-info btn-block btn-float btn-float-lg" type="button"><i class="icon-floppy-disk"></i> <span>{{ __t('sidebar.draft', 'Draft') }}</span></button>
                        <button class="btn bg-danger btn-block btn-float btn-float-lg" type="button"><i class="icon-trash"></i> <span>{{ __t('sidebar.trash', 'Trash') }}</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="menu-list m-t-10 m-b-20">
        <li class="list-title">{{ __t('sidebar.folders', 'Folders') }}</li>
        <li><a href="index2.htm#"><i class="icon-home2"></i> {{ __t('sidebar.home', 'Home') }} <span class="badge badge-info">4</span></a></li>
        <li><a href="index2.htm#"><i class="icon-briefcase3"></i> {{ __t('sidebar.work', 'Work') }} <span class="badge badge-warning">16</span></a></li>
        <li><a href="index2.htm#"><i class="icon-files-empty2"></i> {{ __t('sidebar.documents', 'Documents') }}</a></li>
        <li><a href="index2.htm#"><i class="icon-images2"></i> {{ __t('sidebar.images', 'Images') }}</a></li>
        <li><a href="index2.htm#"><i class="icon-flag7"></i> {{ __t('sidebar.flagged', 'Flagged') }}</a></li>						
    </ul>
    <h6 class="mt-20 text-uppercase text-semibold">{{ __t('sidebar.completeness_stats', 'Completeness stats') }}</h6>
    <label>{{ __t('sidebar.disk_space_used', 'Disk space used') }} <span>80%</span></label>
    <div class="progress progress-xxs">
        <div class="progress-bar progress-bar-danger" style="width: 80%">
            <span class="sr-only">80% {{ __t('sidebar.complete', 'Complete') }}</span>
        </div>
    </div>
</div>


@include('sidebar.panal_profile')
@include('sidebar.panal_setting')