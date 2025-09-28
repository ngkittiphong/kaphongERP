<div class="elements">
    <button type="button" class="btn btn-sm btn-success btn-labeled"
        wire:click="$dispatch('showAddUserForm')">
        <b><i class="icon-user-plus"></i></b> {{ __t('user.add_new_user', 'Add new user') }}
    </button>
</div>