<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="icon-file-check position-left text-primary"></i>
                        {{ __t('warehouse.create_new_check_stock_report', 'Create New Check Stock Report') }}
                    </h3>
                </div>
                <div class="panel-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id" class="control-label">
                                        {{ __t('warehouse.warehouse', 'Warehouse') }} <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model="warehouse_id" 
                                            class="form-control select2" 
                                            id="warehouse_id"
                                            required>
                                        <option value="">{{ __t('warehouse.select_warehouse', 'Select Warehouse') }}</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_create_id" class="control-label">
                                        {{ __t('user.user', 'User') }} <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model="user_create_id" 
                                            class="form-control select2" 
                                            id="user_create_id"
                                            required>
                                        <option value="">{{ __t('user.select_user', 'Select User') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_create_id') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="datetime_create" class="control-label">
                                        {{ __t('warehouse.check_date', 'Check Date') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" 
                                           wire:model="datetime_create" 
                                           class="form-control" 
                                           id="datetime_create"
                                           required>
                                    @error('datetime_create') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description" class="control-label">
                                        {{ __t('common.description', 'Description') }}
                                    </label>
                                    <textarea wire:model="description" 
                                              class="form-control" 
                                              id="description"
                                              rows="3"
                                              placeholder="{{ __t('warehouse.optional_description_check_stock', 'Optional description for this check stock report') }}"></textarea>
                                    @error('description') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Product Selection Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-primary">
                                    <i class="icon-package position-left"></i>
                                    {{ __t('warehouse.products_to_check', 'Products to Check') }}
                                </h4>
                                <hr>
                            </div>
                        </div>

                        <!-- Add Product Form -->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="newProductId" class="control-label">
                                        {{ __t('product.product', 'Product') }} <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model="newProductId" 
                                            class="form-control select2-product" 
                                            id="newProductId">
                                        <option value="">{{ __t('product.select_product', 'Select Product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->sku_number }} - {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('newProductId') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="newProductQuantity" class="control-label">
                                        {{ __t('product.quantity', 'Quantity') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           wire:model="newProductQuantity" 
                                           class="form-control" 
                                           id="newProductQuantity"
                                           min="0"
                                           placeholder="0">
                                    @error('newProductQuantity') 
                                        <span class="text-danger">{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">&nbsp;</label>
                                    <div>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                wire:click="addProduct">
                                            <i class="icon-plus position-left"></i>
                                            {{ __t('product.add_product', 'Add Product') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Products Table -->
                        @if(count($selectedProducts) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="15%">{{ __t('product.sku', 'SKU') }}</th>
                                                    <th width="35%">{{ __t('product.product_name', 'Product Name') }}</th>
                                                    <th width="15%">{{ __t('product.quantity', 'Quantity') }}</th>
                                                    <th width="10%">{{ __t('product.unit', 'Unit') }}</th>
                                                    <th width="10%">{{ __t('common.actions', 'Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedProducts as $index => $product)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $product['sku_number'] }}</td>
                                                        <td>{{ $product['product_name'] }}</td>
                                                        <td>
                                                            <input type="number" 
                                                                   wire:model="selectedProducts.{{ $index }}.quantity"
                                                                   wire:change="updateProductQuantity({{ $index }}, $event.target.value)"
                                                                   class="form-control input-sm" 
                                                                   min="0">
                                                        </td>
                                                        <td>{{ $product['unit_name'] }}</td>
                                                        <td>
                                                            <button type="button" 
                                                                    class="btn btn-danger btn-xs" 
                                                                    wire:click="removeProduct({{ $index }})">
                                                                <i class="icon-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info text-center">
                                        <i class="icon-info"></i>
                                        {{ __t('warehouse.no_products_added_yet', 'No products added yet. Please add products to create the check stock report.') }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <button type="button" 
                                            class="btn btn-default" 
                                            wire:click="cancel">
                                        <i class="icon-cross position-left"></i>
                                        {{ __t('common.cancel', 'Cancel') }}
                                    </button>
                                    <button type="submit" 
                                            class="btn btn-primary">
                                        <i class="icon-checkmark position-left"></i>
                                        {{ __t('warehouse.create_check_stock_report', 'Create Check Stock Report') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Select2 for dropdowns
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        $('.select2-product').select2({
            width: '100%',
            placeholder: '{{ __t('product.search_for_product', 'Search for a product...') }}'
        });

        // Handle Select2 change events
        $('.select2').on('change', function (e) {
            var fieldName = $(this).attr('id');
            var value = $(this).val();
            
            if (fieldName === 'warehouse_id') {
                @this.set('warehouse_id', value);
            } else if (fieldName === 'user_create_id') {
                @this.set('user_create_id', value);
            }
        });

        // Handle product selection change
        $('.select2-product').on('change', function (e) {
            var value = $(this).val();
            @this.set('newProductId', value);
        });
    });

    // Re-initialize Select2 after Livewire updates
    document.addEventListener('livewire:load', function () {
        $('.select2').select2({
            width: '100%'
        });

        $('.select2-product').select2({
            width: '100%',
            placeholder: '{{ __t('product.search_for_product', 'Search for a product...') }}'
        });
    });

    // Listen for form events
    window.addEventListener('showAddNewCheckStockForm', event => {
        console.log('Show add check stock form');
        Livewire.dispatch('showAddForm', {}, 'warehouse.warehouse-check-stock-detail');
    });

    // Listen for success message
    window.addEventListener('showSuccessMessage', event => {
        // You can replace this with a toast notification library
        alert(event.detail);
    });

    // Listen for error message
    window.addEventListener('showErrorMessage', event => {
        alert(event.detail);
    });
</script>
@endpush
