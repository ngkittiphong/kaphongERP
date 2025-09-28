<div class="elements">
    <button type="button" class="btn btn-sm btn-success btn-labeled"
        wire:click="$dispatch('showAddUserForm')">
        <b><i class="icon-user-plus"></i></b> {{ __t('category.add_new_category', 'Add new category') }}
    </button>
</div>
