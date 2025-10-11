<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h4 class="panel-title">{{ $product ? __t('product.edit_product', 'Edit Product') : __t('product.add_new_product', 'Add New Product') }}</h4>
                </div>
                <form wire:submit.prevent="{{ $product ? 'updateProduct' : 'createProduct' }}" id="addProductForm" onsubmit="console.log('🚀 Form submitted, product exists:', {{ $product ? 'true' : 'false' }});">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="text-center">
                            <div class="col-md-4 col-xs-12">
                                <div class="text-center">
                                    <div id="slim-image" class="slim" data-size="300,300" data-ratio="1:1"
                                        data-instant-edit="true"
                                        style="
                                            width: 300px; 
                                            height: 300px;
                                            margin: 0 auto;
                                            border-radius: 0;
                                            overflow: hidden;">

                                            @if($product && $product->image)
                                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" />
                                            @else
                                                <img src="{{ asset('assets/images/default_product.png') }}" alt="Default Icon" class="img-fluid" />
                                            @endif

                                        <!-- File input for uploading/replacing the image -->
                                        <input type="file" name="slim" accept="image/jpeg, image/png" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">{{ __t('product.product_name', 'Product Name') }} *</label>
                                <input type="text" class="form-control" id="name" wire:model="name" required>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="sku_number">{{ __t('product.sku_number', 'SKU Number') }}</label>
                                <input type="text" class="form-control" id="sku_number" wire:model="sku_number">
                                @error('sku_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="serial_number">{{ __t('product.serial_number', 'Serial Number') }}</label>
                                <input type="text" class="form-control" id="serial_number" wire:model="serial_number">
                                @error('serial_number') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="product_type_id">{{ __t('product.product_type', 'Product Type') }} *</label>
                                <select class="form-control" id="product_type_id" wire:model="product_type_id" required>
                                    <option value="">{{ __t('product.select_type', 'Select Type') }}</option>
                                    @foreach($productTypes as $type)
                                        <option value="{{ $type->id }}" {{ $product && $product->product_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="product_group_name">{{ __t('product.product_group', 'Product Group') }} *</label>
                                <input
                                type="text"
                                id="product_group_name"
                                class="form-control typeahead"
                                wire:model.defer="product_group_name"
                                value="{{ $product && $product->productGroup ? $product->productGroup->name : '' }}"
                                required
                              />
                                @error('product_group_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="product_status_id">{{ __t('product.product_status', 'Product Status') }} *</label>
                                <select class="form-control" id="product_status_id" wire:model="product_status_id" required>
                                    <option value="">{{ __t('product.select_status', 'Select Status') }}</option>
                                    @foreach($productStatuses as $status)
                                        <option value="{{ $status->id }}" {{ $product && $product->product_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_status_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Pricing Information -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unit_name">{{ __t('product.unit_name', 'Unit Name') }} *</label>
                                <input type="text" class="form-control" id="unit_name" wire:model="unit_name" required>
                                @error('unit_name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="buy_price">{{ __t('product.buy_price', 'Buy Price') }} ({{ currency_symbol() }})</label>
                                <input type="number" step="0.01" class="form-control" id="buy_price" wire:model="buy_price" value="{{ $product ? $product->buy_price : 0.00 }}">
                                @error('buy_price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="buy_vat_id">{{ __t('product.buy_vat', 'Buy VAT') }}</label>
                                <select class="form-control" id="buy_vat_id" wire:model="buy_vat_id">
                                    <option value="">{{ __t('product.select_vat', 'Select VAT') }}</option>
                                    @foreach($vats as $vat)
                                        <option value="{{ $vat->id }}" {{ $product && $product->buy_vat_id == $vat->id ? 'selected' : '' }}>
                                            {{ $vat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('buy_vat_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="buy_withholding_id">{{ __t('product.buy_withholding', 'Buy Withholding') }}</label>
                                <select class="form-control" id="buy_withholding_id" wire:model="buy_withholding_id">
                                    <option value="">{{ __t('product.select_withholding', 'Select Withholding') }}</option>
                                    @foreach($withholdings as $withholding)
                                        <option value="{{ $withholding->id }}" {{ $product && $product->buy_withholding_id == $withholding->id ? 'selected' : '' }}>
                                            {{ $withholding->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('buy_withholding_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="sale_price">{{ __t('product.sale_price', 'Sale Price') }} ({{ currency_symbol() }}) *</label>
                                <input type="number" step="0.01" class="form-control" id="sale_price" wire:model="sale_price" required value="{{ $product ? $product->sale_price : 0.00 }}">
                                @error('sale_price') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="sale_vat_id">{{ __t('product.sale_vat', 'Sale VAT') }}</label>
                                <select class="form-control" id="sale_vat_id" wire:model="sale_vat_id">
                                    <option value="">{{ __t('product.select_vat', 'Select VAT') }}</option>
                                    @foreach($vats as $vat)
                                        <option value="{{ $vat->id }}" {{ $product && $product->sale_vat_id == $vat->id ? 'selected' : '' }}>
                                            {{ $vat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sale_vat_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="sale_withholding_id">{{ __t('product.sale_withholding', 'Sale Withholding') }}</label>
                                <select class="form-control" id="sale_withholding_id" wire:model="sale_withholding_id">
                                    <option value="">{{ __t('product.select_withholding', 'Select Withholding') }}</option>
                                    @foreach($withholdings as $withholding)
                                        <option value="{{ $withholding->id }}" {{ $product && $product->sale_withholding_id == $withholding->id ? 'selected' : '' }}>
                                            {{ $withholding->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sale_withholding_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="minimum_quantity">{{ __t('product.minimum_quantity', 'Minimum Quantity') }}</label>
                                <input type="number" class="form-control" id="minimum_quantity" wire:model="minimum_quantity" value="{{ $product ? $product->minimum_quantity : '' }}">
                                @error('minimum_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="maximum_quantity">{{ __t('product.maximum_quantity', 'Maximum Quantity') }}</label>
                                <input type="number" class="form-control" id="maximum_quantity" wire:model="maximum_quantity" value="{{ $product ? $product->maximum_quantity : '' }}">
                                @error('maximum_quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Descriptions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buy_description">{{ __t('product.buy_description', 'Buy Description') }}</label>
                                <textarea class="form-control" id="buy_description" wire:model="buy_description" rows="3">{{ $product ? $product->buy_description : '' }}</textarea>
                                @error('buy_description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_description">{{ __t('product.sale_description', 'Sale Description') }}</label>
                                <textarea class="form-control" id="sale_description" wire:model="sale_description" rows="3">{{ $product ? $product->sale_description : '' }}</textarea>
                                @error('sale_description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">{{ $product ? __t('product.update_product', 'Update Product') : __t('product.add_new_product', 'Add new Product') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showAddEditProductForm', () => {
            $('#addProductModal').modal('show');
        });

        Livewire.on('closeModal', () => {
            $('#addProductModal').modal('hide');
        });

        // Success message handler - handled by main detail view

        // Error message handler
        Livewire.on('showErrorMessage', (data) => {
            Swal.fire({
                title: '{{ __t('common.error', 'Error!') }}',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
@endpush


