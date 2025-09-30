<div class="elements">
    <button type="button" class="btn btn-sm btn-success btn-labeled"
        wire:click="$dispatch('showAddEditProductForm')">
        <b><i class="icon-box-add"></i></b> {{ __t('product.add_new_product', 'Add new product') }}
    </button>
</div>