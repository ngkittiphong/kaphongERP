<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <strong>{{ __t('common.success', 'Success!') }}</strong> {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <strong>{{ __t('common.error', 'Error!') }}</strong> {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="stockIn">
        <div class="row">
            <!-- Warehouse Selection -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __t('warehouse.warehouse', 'Warehouse') }} <span class="text-danger">*</span></label>
                    <select wire:model="warehouseId" class="form-control">
                        <option value="">{{ __t('warehouse.select_warehouse', 'Select Warehouse') }}</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">
                                {{ $warehouse->name }} ({{ $warehouse->branch->name_en ?? 'No Branch' }})
                            </option>
                        @endforeach
                    </select>
                    @error('warehouseId') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Product Selection -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __t('product.product', 'Product') }} <span class="text-danger">*</span></label>
                    <select wire:model="productId" class="form-control">
                        <option value="">{{ __t('product.select_product', 'Select Product') }}</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} ({{ $product->unit_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('productId') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        @if($selectedWarehouse && $selectedProduct)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <strong>{{ __t('product.current_stock', 'Current Stock') }}:</strong> {{ number_format($currentStockBalance) }} {{ $selectedProduct->unit_name }}
                    {{ __t('common.in', 'in') }} {{ $selectedWarehouse->name }}
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Quantity -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{{ __t('product.quantity', 'Quantity') }} <span class="text-danger">*</span></label>
                    <input type="number" wire:model="quantity" class="form-control" placeholder="{{ __t('product.enter_quantity', 'Enter quantity') }}" min="1" step="1">
                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Unit Price -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{{ __t('product.unit_price', 'Unit Price') }}</label>
                    <input type="number" wire:model="unitPrice" class="form-control" placeholder="0.00" min="0" step="0.01">
                    @error('unitPrice') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Sale Price -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{{ __t('product.sale_price', 'Sale Price') }}</label>
                    <input type="number" wire:model="salePrice" class="form-control" placeholder="0.00" min="0" step="0.01">
                    @error('salePrice') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Date -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __t('product.date', 'Date') }}</label>
                    <input type="date" wire:model="dateActivity" class="form-control">
                    @error('dateActivity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Detail -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __t('product.detail_notes', 'Detail/Notes') }}</label>
                    <input type="text" wire:model="detail" class="form-control" placeholder="{{ __t('product.optional_notes', 'Optional notes') }}">
                    @error('detail') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Calculation Display -->
        @if($quantity && $unitPrice)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <strong>{{ __t('product.total_value', 'Total Value') }}:</strong> {{ number_format($quantity * $unitPrice, 2) }}
                    @if($selectedProduct)
                        <br><strong>{{ __t('product.new_stock_balance', 'New Stock Balance') }}:</strong> {{ number_format($currentStockBalance + $quantity) }} {{ $selectedProduct->unit_name }}
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="text-right">
                    <button type="button" wire:click="resetForm" class="btn btn-default">
                        <i class="icon-reload-alt position-left"></i> {{ __t('common.reset', 'Reset') }}
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <i class="icon-plus-circle2 position-left"></i>
                        <span wire:loading.remove>{{ __t('warehouse.stock_in', 'Stock In') }}</span>
                        <span wire:loading>{{ __t('common.processing', 'Processing...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
