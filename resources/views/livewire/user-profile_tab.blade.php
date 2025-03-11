{{-- --------------------------------- view user profile form ---------------------------- --}}
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="active">
                                <a href="#tab-detail" data-toggle="tab" class="panel-title" aria-expanded="true">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Detail</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-access" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Access</h3>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    @include('livewire.user-profile_tab_detail')
                    <div class="tab-pane" id="tab-access">
                        {{-- Access --}}
                        Access detail statement
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
