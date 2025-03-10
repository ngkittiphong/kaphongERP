<div class="tab-pane active" id="tab-detail">
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <div style="width: 200px; height: 200px; margin: 0 auto; border-radius: 50%; overflow: hidden;">
                <img src="{{ $user->profile && $user->profile->avatar ? $user->profile->avatar : asset('assets/images/faces/face_default.png') }}"
                    alt="{{ $user->username }}'s Avatar"
                    class="img-fluid"
                    style="width: 100%; height: 100%; object-fit: cover;" />
            </div>

            <h4 class="no-margin-bottom m-t-10"><i class=""
                    alt="{{ $user->status->name }}"></i>{{ $user->profile?->fullname_th }}
                ({{ $user->profile->nickname }})</h4>
            <div>user.status.name</div>
        </div>
    </div>
    <div class="col-md-8 col-xs-12">
        <!--<div class="panel panel-flat">-->
        <div class="panel-heading no-padding-bottom">
            <h4 class="panel-title"><?= __('User details') ?></h4>
            <div class="elements">
                <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                <button class="btn bg-amber-darkest"
                    wire:click="$dispatch('showEditProfileForm')">Edit User</button>
                <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">Delete
                    User</button>
            </div>
            <a class="elements-toggle"><i class="icon-more"></i></a>
        </div>
        <div class="list-group list-group-lg list-group-borderless">
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-user-lock"></i> {{ $user->username }}
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-lock"></i> change password click for popup(modal)
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-puzzle"></i> user.type.name
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-plus3"></i> user.date_create
            </a>
        </div>
        <div class="panel-heading no-padding-bottom">
            <h4 class="panel-title"><?= __('Contact details') ?></h4>
        </div>
        <div class="list-group list-group-lg list-group-borderless">
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-envelop3"></i>
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-phone2"></i>
            </a>
            <a href="#" class="list-group-item p-l-20">
                <i class="icon-location4"></i>
            </a>
        </div>
    </div>
</div>