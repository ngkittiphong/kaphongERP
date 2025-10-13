<div class="tab-pane active" id="tab-detail" wire:key="product-detail-tab-{{ $product->id ?? 'none' }}">

    <div class="row col-md-12 col-xs-12">
        <div class="col-md-4 col-xs-12">
            <div class="text-center">
                <div class="thumb">
                    <a href="{{ asset('assets/images/default_product.png') }}" class="venobox">
                        <img src="{{ asset('assets/images/default_product.png') }}" alt="">
                        <span class="zoom-image"><i class="icon-plus2"></i></span>
                    </a>
                </div>

                <h4 class="no-margin-bottom m-t-10">
                    {{ $product->name ?? 'N/A' }}
                </h4>
                <div>{{ $product->sku_number ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <!--<div class="panel panel-flat">-->
            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title">{{ __t('product.product_details', 'Product details') }}</h4>
                <div class="elements">
                    <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                    <button class="btn bg-amber-darkest" wire:click="$dispatch('showEditProductForm')">{{ __t('product.edit_product', 'Edit Product') }}</button>
                    <button class="btn btn-danger" onclick="confirmDelete({{ $product->id ?? 0 }})">{{ __t('product.delete_product', 'Delete Product') }}</button>
                </div>
                <a class="elements-toggle"><i class="icon-more"></i></a>
            </div>
            <div class="list-group list-group-lg list-group-borderless">
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ __t('product.total_count', 'Total Count') }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ number_format($totalQuantity) }} {{ $product->unit_name ?? 'ชิ้น' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ __t('product.product_type', 'Product Type') }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->type->name ?? 'N/A' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ __t('product.product_group', 'Product Group') }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->group->name ?? 'N/A' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ __t('product.product_unit', 'Product Unit') }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->unit_name ?? 'ชิ้น' }}
                        </div>
                    </span>
                </div>
                
                @if($totalQuantity == 0)
                    <div class='row'>
                        <span href="#" class="list-group-item p-l-20">
                            <div class="col-md-12 col-xs-12 text-center">
                                <button class="btn btn-primary btn-sm" wire:click="openStockInModal">
                                    <i class="icon-plus2"></i> {{ __t('product.stock_in_operation', 'Stock in operation') }}
                                </button>
                            </div>
                        </span>
                    </div>
                @endif

                {{-- <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            จำนวนคงเหลือ :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            <div class="progress progress-xs m-b-10">
                                <div class="progress-bar progress-bar-warning  progress-bar-striped" style="width: 20%">
                                    <span class="sr-only">20% Complete</span>
                                </div>
                                
                            </div>
                            <div class='row col-md-12 col-xs-12'>
                                <div class="col-md-4 col-xs-4 text-left text-extrabold">
                                    20
                                </div>
                                <div class="col-md-4 col-xs-4 text-center">
                                    30
                                </div>
                                <div class="col-md-4 col-xs-4 text-right">
                                    100
                                </div>

                            </div>
                        </div>
                    </span>
                    
                    
                </div> --}}

            </div>

        </div>

    </div>






    <div class="row col-md-12 col-xs-12">
        <div class="row col-md-12 col-xs-12">
            <div class="panel-heading no-padding-bottom">
                <!--<h4 class="panel-title">{{ __t('warehouse.warehouse', 'Warehouse') }}</h4>-->
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-flat bg-light-light">
                        <div class="panel-heading text-dark">
                            <h4 class="text-default panel-title">{{ __t('product.total_all_warehouses', 'Total All Warehouses') }}</h4>
                        </div>
                        <div>
                            <div class="list-group text-default list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-7 col-xs-7 text-bold">
                                            {{ __t('product.remaining_quantity', 'Remaining Quantity') }} :
                                        </div>
                                        <div class="col-md-5 col-xs-5 text-left">
                                            {{ number_format($totalQuantity) }} {{ $product->unit_name ?? 'pcs' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-7 col-xs-7 text-bold">
                                            {{ __t('product.average_sale_price', 'Average Sale Price') }} :
                                        </div>
                                        <div class="col-md-5 col-xs-5 text-left">
                                            {{ currency($averageSalePrice) }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-7 col-xs-7 text-bold">
                                            {{ __t('product.average_buy_price', 'Average Buy Price') }} :
                                        </div>
                                        <div class="col-md-5 col-xs-5 text-left">
                                            {{ currency($averageBuyPrice) }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-7 col-xs-7 text-bold">
                                            {{ __t('product.total_value', 'Total Value') }} :
                                        </div>
                                        <div class="col-md-5 col-xs-5 text-left">
                                            {{ currency($totalValue) }}
                                        </div>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                @forelse($warehouseProducts as $index => $warehouseProduct)
                    @if($index < 2) {{-- Show only first 2 warehouses to fit the layout --}}
                        <div class="col-md-4">
                            <div class="panel panel-flat {{ $warehouseProduct->warehouse->main_warehouse ? 'bg-success-light' : 'bg-light-lighter' }}">
                                <div class="panel-heading text-dark">
                                    <h3 class="text-default panel-title">
                                        {{ $warehouseProduct->warehouse->name }}
                                        @if($warehouseProduct->warehouse->main_warehouse)
                                            <span class="badge bg-success">{{ __t('product.main', 'Main') }}</span>
                                        @endif
                                    </h3>
                                </div>
                                <div class="list-group text-default list-group-lg list-group-borderless">
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-7 col-xs-7 text-bold">
                                                {{ __t('product.remaining_quantity', 'Remaining Quantity') }} :
                                            </div>
                                            <div class="col-md-5 col-xs-5 text-left">
                                                @if($warehouseProduct->balance <= ($product->minimum_quantity ?? 0))
                                                    <i class="icon-warning2 position-left text-warning"></i>
                                                @endif
                                                {{ number_format($warehouseProduct->balance) }} {{ $product->unit_name ?? 'pcs' }}
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-7 col-xs-7 text-bold">
                                                {{ __t('product.average_sale_price', 'Average Sale Price') }} :
                                            </div>
                                            <div class="col-md-5 col-xs-5 text-left">
                                                {{ currency($warehouseProduct->avr_sale_price) }}
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-7 col-xs-7 text-bold">
                                                {{ __t('product.average_buy_price', 'Average Buy Price') }} :
                                            </div>
                                            <div class="col-md-5 col-xs-5 text-left">
                                                {{ currency($warehouseProduct->avr_buy_price) }}
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-7 col-xs-7 text-bold">
                                                {{ __t('product.total_value', 'Total Value') }} :
                                            </div>
                                            <div class="col-md-5 col-xs-5 text-left">
                                                {{ currency($warehouseProduct->balance * $warehouseProduct->avr_remain_price) }}
                                                <!-- Debug: Balance={{ $warehouseProduct->balance }}, Price={{ $warehouseProduct->avr_remain_price }} -->
                                            </div>
                                        </span>
                                    </div>
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-12 col-xs-12 text-center">
                                                <button class="btn btn-primary btn-sm" wire:click="openStockModal({{ $warehouseProduct->warehouse_id }}, '{{ $warehouseProduct->warehouse->name }}')">
                                                    <i class="icon-plus2"></i> {{ __t('product.adjust_stock', 'Adjust Stock') }}
                                                </button>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-md-8">
                        <div class="panel panel-flat bg-warning-light">
                            <div class="panel-body text-center">
                                <i class="icon-warning2 icon-2x text-warning"></i>
                                <h4 class="text-warning">{{ __t('product.no_warehouse_data_found', 'No Warehouse Data Found') }}</h4>
                                <p>{{ __t('product.no_inventory_data_message', 'This product has no inventory data in any warehouse') }}</p>
                                <div class="m-t-10">
                                    <button class="btn btn-primary btn-sm" wire:click="openStockInModal">
                                        <i class="icon-plus2"></i> {{ __t('product.stock_in_operation', 'Stock in operation') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
                
                @if($warehouseProducts->count() > 2)
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h4 class="panel-title">{{ __t('product.other_warehouses', 'Other Warehouses') }} ({{ $warehouseProducts->count() - 2 }} {{ __t('product.warehouses', 'warehouses') }})</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    @foreach($warehouseProducts->skip(2) as $warehouseProduct)
                                        <div class="col-md-4">
                                            <div class="panel panel-flat bg-light-light">
                                                <div class="panel-heading text-dark">
                                                    <h5 class="text-default panel-title">
                                                        {{ $warehouseProduct->warehouse->name }}
                                                        @if($warehouseProduct->warehouse->main_warehouse)
                                                            <span class="badge bg-success">{{ __t('product.main', 'Main') }}</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="list-group text-default list-group-lg list-group-borderless">
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                {{ __t('product.remaining_quantity', 'Remaining Quantity') }} :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                @if($warehouseProduct->balance <= ($product->minimum_quantity ?? 0))
                                                                    <i class="icon-warning2 position-left text-warning"></i>
                                                                @endif
                                                                {{ number_format($warehouseProduct->balance) }} {{ $product->unit_name ?? 'pcs' }}
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                {{ __t('product.average_sale_price', 'Average Sale Price') }} :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                {{ currency($warehouseProduct->avr_sale_price) }}
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                {{ __t('product.average_buy_price', 'Average Buy Price') }} :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                {{ currency($warehouseProduct->avr_buy_price) }}
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                {{ __t('product.total_value', 'Total Value') }} :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                {{ currency($warehouseProduct->balance * $warehouseProduct->avr_remain_price) }}
                                                                <!-- Debug: Balance={{ $warehouseProduct->balance }}, Price={{ $warehouseProduct->avr_remain_price }} -->
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-12 col-xs-12 text-center">
                                                                <button class="btn btn-primary btn-sm" wire:click="openStockModal({{ $warehouseProduct->warehouse_id }}, '{{ $warehouseProduct->warehouse->name }}')">
                                                                    <i class="icon-plus2"></i> {{ __t('product.adjust_stock', 'Adjust Stock') }}
                                                                </button>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>



    <div class="row col-md-12 col-xs-12">
        <div class="panel-heading no-padding-bottom">
            <h4 class="panel-title">{{ __t('product.main_product_unit', 'Main Product Unit') }} : {{ $product->unit_name ?? 'pcs' }}</h4>
            <button class="btn btn-primary btn-sm pull-right" wire:click="openAddSubUnitModal">
                <i class="icon-plus2"></i> {{ __t('product.add_sub_unit', 'Add Sub-Unit') }}
            </button>
        </div>

        {{-- Main Product Unit Price Display --}}
        <div class="col-md-4">
            <div class="panel panel-flat bg-primary-light">
                <div class="panel-heading text-dark">
                    <h3 class="text-default panel-title">{{ $product->unit_name ?? 'pcs' }}</h3>
                </div>
                <div class="list-group text-default list-group-lg list-group-borderless">
                    <div class='row'>
                        <span href="#" class="list-group-item p-l-20">
                            <div class="col-md-7 col-xs-7 text-bold">
                                {{ __t('product.sale_price', 'Sale Price') }} :
                            </div>
                            <div class="col-md-5 col-xs-5 text-left">
                                {{ currency($product->sale_price) }}
                            </div>
                        </span>
                    </div>
                    <div class='row'>
                        <span href="#" class="list-group-item p-l-20">
                            <div class="col-md-7 col-xs-7 text-bold">
                                {{ __t('product.buy_price', 'Buy Price') }} :
                            </div>
                            <div class="col-md-5 col-xs-5 text-left">
                                {{ currency($product->buy_price) }}
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($product->subUnits as $index => $subUnit)
                @if($index < 2) {{-- Show only first 2 sub-units to fit the layout --}}
                    <div class="col-md-4">
                        <div class="panel panel-flat {{ $index == 0 ? 'bg-slate-lighter' : 'bg-teal-light' }}">
                            <div class="panel-heading text-dark">
                                <h3 class="text-default panel-title">{{ $subUnit->name }}</h3>
                                <div class="heading-elements">
                                    <button class="btn btn-sm btn-primary" wire:click="openEditSubUnitModal({{ $subUnit->id }})" title="{{ __t('common.edit', 'Edit') }}">
                                        <i class="icon-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="confirmDeleteSubUnit({{ $subUnit->id }})" 
                                            title="{{ __t('common.delete', 'Delete') }}">
                                        <i class="icon-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="list-group text-default list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-7 col-xs-7 text-bold">
                                            {{ __t('product.sale_price', 'Sale Price') }} :
                                        </div>
                                        <div class="col-md-5 col-xs-5 text-left">
                                            {{ currency($subUnit->sale_price ?? $product->sale_price) }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-7 col-xs-7 text-bold">
                                            {{ __t('product.buy_price', 'Buy Price') }} :
                                        </div>
                                        <div class="col-md-5 col-xs-5 text-left">
                                            {{ currency($subUnit->buy_price ?? $product->buy_price) }}
                                        </div>
                                    </span>
                                </div>
                                @if($subUnit->barcode)
                                    <div class='row'>
                                        <span href="#" class="list-group-item p-l-20">
                                            <div class="col-md-12 col-xs-12 text-bold">
                                                {{ __t('product.barcode', 'Barcode') }} :
                                            </div>
                                            <div class="col-md-12 col-xs-12 text-left">
                                                {{ $subUnit->barcode }}
                                            </div>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
            
            @if($product->subUnits->count() > 2)
                <div class="col-md-12">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __t('product.other_sub_units', 'Other Sub-Units') }} ({{ $product->subUnits->count() - 2 }} {{ __t('product.units', 'units') }})</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                @foreach($product->subUnits->skip(2) as $subUnit)
                                    <div class="col-md-4">
                                        <div class="panel panel-flat bg-light-light">
                                            <div class="panel-heading text-dark">
                                                <h5 class="text-default panel-title">{{ $subUnit->name }}</h5>
                                                <div class="heading-elements">
                                                    <button class="btn btn-xs btn-primary" wire:click="openEditSubUnitModal({{ $subUnit->id }})" title="{{ __t('common.edit', 'Edit') }}">
                                                        <i class="icon-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-danger" wire:click="confirmDeleteSubUnit({{ $subUnit->id }})" 
                                                            title="{{ __t('common.delete', 'Delete') }}">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="list-group text-default list-group-lg list-group-borderless">
                                                <div class='row'>
                                                    <span href="#" class="list-group-item p-l-20">
                                                        <div class="col-md-7 col-xs-7 text-bold">
                                                            {{ __t('product.sale_price', 'Sale Price') }} :
                                                        </div>
                                                        <div class="col-md-5 col-xs-5 text-left">
                                                            {{ currency($subUnit->sale_price ?? $product->sale_price) }}
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class='row'>
                                                    <span href="#" class="list-group-item p-l-20">
                                                        <div class="col-md-7 col-xs-7 text-bold">
                                                            {{ __t('product.buy_price', 'Buy Price') }} :
                                                        </div>
                                                        <div class="col-md-5 col-xs-5 text-left">
                                                            {{ currency($subUnit->buy_price ?? $product->buy_price) }}
                                                        </div>
                                                    </span>
                                                </div>
                                                @if($subUnit->barcode)
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-12 col-xs-12 text-bold">
                                                                {{ __t('product.barcode', 'Barcode') }} :
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 text-left">
                                                                {{ $subUnit->barcode }}
                                                            </div>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div wire:ignore.self class="modal fade" id="stockAdjustmentModal" tabindex="-1" role="dialog" aria-labelledby="stockAdjustmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __t('common.close', 'Close') }}" wire:click="closeStockModal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="stockAdjustmentModalLabel">
                    @if($isStockInModal)
                        {{ __t('product.stock_in_operation', 'Stock in operation') }} - {{ $product->name ?? 'N/A' }}
                    @else
                        {{ __t('product.adjust_stock', 'Adjust Stock') }} - {{ $selectedWarehouseName ?? '' }}
                    @endif
                </h4>
            </div>
            <div class="modal-body" style="padding-top: 10px;">
                <!-- Product Information Section -->
                <div class="row" style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                    <div class="col-md-3">
                        <div class="text-center">
                            <img src="{{ asset('assets/images/default_product.png') }}" alt="{{ __t('product.product_image', 'Product Image') }}" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h5 class="text-primary" style="margin-top: 0;">{{ $product->name ?? 'N/A' }}</h5>
                        <p class="text-muted" style="margin-bottom: 5px;">
                            <strong>{{ __t('product.sku', 'SKU') }}:</strong> {{ $product->sku_number ?? 'N/A' }}
                        </p>
                        <p class="text-muted" style="margin-bottom: 5px;">
                            <strong>{{ __t('product.type', 'Type') }}:</strong> {{ $product->type->name ?? 'N/A' }}
                        </p>
                        <p class="text-muted" style="margin-bottom: 0;">
                            <strong>{{ __t('product.unit', 'Unit') }}:</strong> {{ $product->unit_name ?? 'pcs' }}
                        </p>
                    </div>
                </div>

                <!-- Warehouse Selection Section (only for stock-in modal) -->
                @if($isStockInModal)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="selectedWarehouseId">{{ __t('product.select_warehouse', 'Select Warehouse') }}:</label>
                                <select wire:model.live="selectedWarehouseId" class="form-control" id="selectedWarehouseId">
                                    <option value="">{{ __t('product.select_warehouse', 'Select Warehouse') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }} @if($warehouse->branch) ({{ $warehouse->branch->name_en }}) @endif</option>
                                    @endforeach
                                </select>
                                @error('selectedWarehouseId') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Operation Form Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="operationType">{{ __t('product.operation_type', 'Operation Type') }}:</label>
                            @if($isStockInModal)
                                <input type="text" class="form-control" value="{{ __t('product.stock_in', 'Stock In') }}" readonly>
                                <input type="hidden" wire:model="operationType" value="stock_in">
                            @else
                                <select wire:model.live="operationType" class="form-control" id="operationType">
                                    <option value="">{{ __t('product.select_operation', 'Select Operation') }}</option>
                                    <option value="stock_in">{{ __t('product.stock_in', 'Stock In') }}</option>
                                    <option value="stock_out">{{ __t('product.stock_out', 'Stock Out') }}</option>
                                    <option value="adjustment">{{ __t('product.stock_adjustment', 'Stock Adjustment') }}</option>
                                </select>
                            @endif
                            @error('operationType') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                @if($operationType && (!$isStockInModal || $selectedWarehouseId))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">{{ __t('product.quantity', 'Quantity') }}:</label>
                                <input type="number" wire:model.defer="quantity" class="form-control" id="quantity"
                                       min="0" step="0.01" placeholder="{{ __t('product.enter_quantity', 'Enter quantity') }}">
                                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unitName">{{ __t('product.unit', 'Unit') }}:</label>
                                <input type="text" class="form-control" id="unitName"
                                       value="{{ $product->unit_name ?? 'pcs' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unitPrice">{{ __t('product.unit_price', 'Unit Price') }} ({{ currency_symbol() }}):</label>
                                <input type="number" wire:model.defer="unitPrice" class="form-control" id="unitPrice"
                                       min="0" step="0.01" placeholder="{{ __t('product.enter_unit_price', 'Enter unit price') }}">
                                @error('unitPrice') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salePrice">{{ __t('product.sale_price', 'Sale Price') }} ({{ currency_symbol() }}):</label>
                                <input type="number" wire:model.defer="salePrice" class="form-control" id="salePrice"
                                       min="0" step="0.01" placeholder="{{ __t('product.enter_sale_price', 'Enter sale price') }}">
                                @error('salePrice') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="detail">{{ __t('product.detail_reason', 'Detail/Reason') }}:</label>
                                <textarea wire:model.defer="detail" class="form-control" id="detail" rows="3"
                                          placeholder="{{ __t('product.enter_reason', 'Enter reason for this operation') }}"></textarea>
                                @error('detail') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                @endif

                <!-- Current Stock Information Section -->
                <div class="row" style="margin-top: 20px; padding: 15px; background-color: #e8f4fd; border-radius: 5px; border-left: 4px solid #2196F3;">
                    <div class="col-md-6">
                        <h6 class="text-primary" style="margin-top: 0; margin-bottom: 10px;">
                            <i class="icon-info22"></i> {{ __t('product.current_stock_information', 'Current Stock Information') }}
                        </h6>
                        <p class="text-muted" style="margin-bottom: 5px;">
                            <strong>{{ __t('product.warehouse', 'Warehouse') }}:</strong> 
                            @if($isStockInModal && $selectedWarehouseId)
                                @php
                                    $selectedWarehouse = $warehouses->firstWhere('id', $selectedWarehouseId);
                                @endphp
                                {{ $selectedWarehouse ? $selectedWarehouse->name : 'N/A' }}
                            @else
                                {{ $selectedWarehouseName ?? 'N/A' }}
                            @endif
                        </p>
                        <p class="text-muted" style="margin-bottom: 5px;">
                            <strong>{{ __t('product.current_remaining', 'Current Remaining') }}:</strong> 
                            <span class="text-primary" style="font-weight: bold; font-size: 16px;">
                                {{ number_format($currentStock ?? 0) }} {{ $product->unit_name ?? 'pcs' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary" style="margin-top: 0; margin-bottom: 10px;">
                            <i class="icon-calendar"></i> {{ __t('product.operation_date', 'Operation Date') }}
                        </h6>
                        <p class="text-muted" style="margin-bottom: 5px;">
                            <strong>{{ __t('product.date', 'Date') }}:</strong> {{ now()->format('d/m/Y') }}
                        </p>
                        <p class="text-muted" style="margin-bottom: 0;">
                            <strong>{{ __t('product.time', 'Time') }}:</strong> {{ now()->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" wire:click="closeStockModal">{{ __t('common.cancel', 'Cancel') }}</button>
                @if($operationType && (!$isStockInModal || $selectedWarehouseId))
                    <button type="button" class="btn btn-primary" wire:click="processStockOperation"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="processStockOperation">
                            <i class="icon-checkmark"></i> {{ __t('product.process', 'Process') }} {{ ucfirst(str_replace('_', ' ', $operationType)) }}
                        </span>
                        <span wire:loading wire:target="processStockOperation">
                            <i class="icon-spinner2 spinner"></i> {{ __t('product.processing', 'Processing') }}...
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Sub-Unit Modal --}}
<div wire:ignore.self class="modal fade" id="subUnitModal" tabindex="-1" role="dialog" aria-labelledby="subUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 650px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="subUnitModalLabel">
                    @if($subUnitId)
                        {{ __t('product.edit_sub_unit', 'Edit Sub-Unit') }}
                    @else
                        {{ __t('product.add_sub_unit', 'Add Sub-Unit') }}
                    @endif
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeSubUnitModal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="saveSubUnit">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subUnitName">{{ __t('product.sub_unit_name', 'Sub-Unit Name') }} <span class="text-danger">*</span></label>
                                <input type="text" wire:model="subUnitName" class="form-control" id="subUnitName" placeholder="{{ __t('product.enter_sub_unit_name', 'Enter sub-unit name') }}">
                                @error('subUnitName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subUnitQuantity">{{ __t('product.quantity_of_minimum_unit', 'Quantity of Minimum Unit') }} <span class="text-danger">*</span></label>
                                <input type="number" wire:model="subUnitQuantity" class="form-control" id="subUnitQuantity" min="1" placeholder="1">
                                @error('subUnitQuantity') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subUnitBuyPrice">{{ __t('product.buy_price', 'Buy Price') }} <span class="text-danger">*</span></label>
                                <input type="number" wire:model="subUnitBuyPrice" class="form-control" id="subUnitBuyPrice" step="0.01" min="0" placeholder="0.00">
                                @error('subUnitBuyPrice') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subUnitSalePrice">{{ __t('product.sale_price', 'Sale Price') }} <span class="text-danger">*</span></label>
                                <input type="number" wire:model="subUnitSalePrice" class="form-control" id="subUnitSalePrice" step="0.01" min="0" placeholder="0.00">
                                @error('subUnitSalePrice') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" wire:click="closeSubUnitModal">{{ __t('common.cancel', 'Cancel') }}</button>
                <button type="button" class="btn btn-primary" wire:click="saveSubUnit" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveSubUnit">
                        <i class="icon-checkmark"></i> {{ __t('common.save', 'Save') }}
                    </span>
                    <span wire:loading wire:target="saveSubUnit">
                        <i class="icon-spinner2 spinner"></i> {{ __t('common.saving', 'Saving') }}...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

