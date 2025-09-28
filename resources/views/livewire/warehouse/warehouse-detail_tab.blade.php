<!-----------------------------  Start Warehouse Detail    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="{{ $activeTab === 'detail' ? 'active' : '' }}">
                                <a href="#tab-detail" class="panel-title" 
                                   wire:click.prevent="switchTab('detail')" aria-expanded="{{ $activeTab === 'detail' ? 'true' : 'false' }}">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('common.detail', 'Detail') }}</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="{{ $activeTab === 'inventory' ? 'active' : '' }}">
                                <a href="#tab-inventory" 
                                   wire:click.prevent="switchTab('inventory')" aria-expanded="{{ $activeTab === 'inventory' ? 'true' : 'false' }}">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('warehouse.inventory', 'Inventory') }}</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="{{ $activeTab === 'movements' ? 'active' : '' }}">
                                <a href="#tab-movements" 
                                   wire:click.prevent="switchTab('movements')" aria-expanded="{{ $activeTab === 'movements' ? 'true' : 'false' }}">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('warehouse.movements', 'Movements') }}</h3>
                                    </div>
                                </a>
                            </li>
                            {{-- <li class="">
                                <a href="#tab-stock-operations" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('warehouse.stock_operations', 'Stock Operations') }}</h3>
                                    </div>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane {{ $activeTab === 'detail' ? 'active' : '' }}" id="tab-detail">
                        <div class="row col-md-12 col-xs-12">
                            <div class="panel-heading no-padding-bottom">
                                <h4 class="panel-title">{{ __t('warehouse.warehouse_details', 'Warehouse details') }}</h4>
                                <div class="elements">
                                    @if($warehouse->status->name === 'Active')
                                        <button class="btn bg-amber-darkest"
                                            wire:click="$dispatch('showEditWarehouseForm')">
                                            <i class="icon-pencil6"></i> {{ __t('warehouse.edit_warehouse', 'Edit Warehouse') }}
                                        </button>
                                        <button class="btn btn-danger" onclick="confirmDelete({{ $warehouse->id ?? 0 }})">
                                            <i class="icon-trash"></i> {{ __t('warehouse.deactivate_warehouse', 'Deactivate Warehouse') }}
                                        </button>
                                    @else
                                        <button class="btn btn-success" onclick="confirmReactivate({{ $warehouse->id ?? 0 }})">
                                            <i class="icon-checkmark"></i> {{ __t('warehouse.reactivate_warehouse', 'Reactivate Warehouse') }}
                                        </button>
                                    @endif
                                </div>
                                <a class="elements-toggle"><i class="icon-more"></i></a>
                            </div>
                            <div class="list-group list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            Warehouse Name :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->name ?? 'N/A' }}
                                            @if($warehouse && $warehouse->main_warehouse)
                                                (Main Warehouse)
                                            @endif
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            Status :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            <span class="badge bg-{{ $warehouse->status->name === 'Active' ? 'success' : 'danger' }}">
                                                {{ $warehouse->status->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            Branch :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->branch->name_en ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            Created Date :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->date_create ? $warehouse->date_create->format('Y-m-d H:i') : 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            Created By :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->userCreate->username ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            Average Remaining Price :
                                        </div>
                            <div class="col-md-8 col-xs-8 text-left">
                                {{ number_format($this->getCalculatedAverageRemainingPrice(), 2) }} THB
                            </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane {{ $activeTab === 'inventory' ? 'active' : '' }}" id="tab-inventory">
                        <div class="row col-md-12 col-xs-12">
                            <!-- Inventory Table Header -->
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <span class="text-muted">
                                            Total Products: {{ count($warehouseInventory) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory Table -->
                            <div class="table-responsive">
                                <table class="table datatable-inventory table-striped" id="inventoryTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __t('product.product_name', 'Product Name') }}</th>
                                            <th>{{ __t('product.product_code', 'Product Code') }}</th>
                                            <th>{{ __t('warehouse.balance', 'Balance') }}</th>
                                            <th>{{ __t('product.unit', 'Unit') }}</th>
                                            <th>{{ __t('product.avg_buy_price', 'Avg Buy Price') }}</th>
                                            <th>{{ __t('product.avg_sale_price', 'Avg Sale Price') }}</th>
                                            <th>{{ __t('product.total_value', 'Total Value') }}</th>
                                            <th>{{ __t('common.status', 'Status') }}</th>
                                            <th>{{ __t('common.action', 'Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouseInventory as $index => $inventory)
                                            <tr class="text-default">
                                                <td class="col-md-1">{{ $index + 1 }}.</td>
                                                <td class="col-md-2">
                                                    <strong>{{ $inventory->product->name ?? 'N/A' }}</strong>
                                                    @if($inventory->product->sku_number)
                                                        <br><small class="text-muted">SKU: {{ $inventory->product->sku_number }}</small>
                                                    @endif
                                                </td>
                                                <td class="col-md-1">{{ $inventory->product->sku_number ?? 'N/A' }}</td>
                                                <td class="col-md-1">
                                                    <span class="badge badge-{{ $inventory->balance > 0 ? 'success' : 'danger' }}">
                                                        {{ number_format($inventory->balance) }}
                                                    </span>
                                                </td>
                                                <td class="col-md-1">{{ $inventory->product->unit ?? 'N/A' }}</td>
                                                <td class="col-md-1">{{ number_format($inventory->avr_buy_price, 2) }}</td>
                                                <td class="col-md-1">{{ number_format($inventory->avr_sale_price, 2) }}</td>
                                                <td class="col-md-1">
                                                    <strong>{{ number_format($inventory->total_value, 2) }}</strong>
                                                </td>
                                                <td class="col-md-1">
                                                    <span class="badge bg-{{ $inventory->balance > 0 ? 'success' : 'danger' }}">
                                                        {{ $inventory->balance > 0 ? 'In Stock' : 'Out of Stock' }}
                                                    </span>
                                                </td>
                                                <td class="col-md-2">
                                                    <ul class="icons-list">
                                                        <li><a href="{{ route('menu.menu_product') }}?product_id={{ $inventory->product->id }}&return_to=warehouse&warehouse_id={{ $warehouse->id }}" title="{{ __t('common.view_details', 'View Details') }}"><i class="icon-eye2"></i></a></li>
                                                        <li><a href="#" title="{{ __t('common.edit', 'Edit') }}" wire:click.prevent="openStockModal({{ $inventory->product->id }}, {{ $warehouse->id }}, '{{ $warehouse->name }}')" onclick="console.log('🚀 [CLICK] Edit button clicked for product: {{ $inventory->product->id }}, warehouse: {{ $warehouse->id }}'); console.log('🚀 [CLICK] About to call Livewire method');"><i class="icon-pencil6"></i></a></li>
                                                        <li><a href="#" title="{{ __t('warehouse.stock_movement', 'Stock Movement') }}" wire:click.prevent="openTransferForm({{ $inventory->product->id }}, {{ $warehouse->id }}, '{{ $warehouse->name }}')"><i class="icon-arrow-right8"></i></a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="text-muted">
                                                        <i class="icon-package" style="font-size: 48px; color: #ddd;"></i>
                                                        <br>No inventory found for this warehouse
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane {{ $activeTab === 'movements' ? 'active' : '' }}" id="tab-movements">
                        <div class="row col-md-12 col-xs-12">
                            <!-- Date Filter Section -->
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="date" 
                                               class="form-control" 
                                               id="movementDateFrom"
                                               placeholder="{{ __t('common.from_date', 'From Date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" 
                                               class="form-control" 
                                               id="movementDateTo"
                                               placeholder="{{ __t('common.to_date', 'To Date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-default" onclick="clearMovementFilters()">
                                            <i class="icon-cross2"></i> Clear Date Filters
                                        </button>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <span class="text-muted">
                                            Total Movements: {{ count($warehouseMovements) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Movements Table -->
                            <div class="table-responsive">
                                <table class="table datatable-movements table-striped" id="movementsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>{{ __t('product.unit', 'Unit') }}</th>
                                            <th>Reference</th>
                                            <th>From/To</th>
                                            <th>{{ __t('common.status', 'Status') }}</th>
                                            <th>{{ __t('common.action', 'Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouseMovements as $index => $movement)
                                            <tr class="text-default">
                                                <td class="col-md-1">{{ $index + 1 }}.</td>
                                                <td class="col-md-1">
                                                    {{ \Carbon\Carbon::parse($movement['date'])->format('Y-m-d H:i') }}
                                                </td>
                                                <td class="col-md-1">
                                                    <span class="badge bg-{{ $movement['type_class'] }}">
                                                        {{ $movement['type'] }}
                                                    </span>
                                                </td>
                                                <td class="col-md-2">
                                                    <strong>{{ $movement['product_name'] }}</strong>
                                                    <br><small class="text-muted">{{ $movement['product_code'] }}</small>
                                                </td>
                                                <td class="col-md-1">
                                                    <span class="badge badge-{{ $movement['quantity'] > 0 ? 'success' : 'danger' }}">
                                                        {{ $movement['quantity'] > 0 ? '+' : '' }}{{ number_format($movement['quantity']) }}
                                                    </span>
                                                </td>
                                                <td class="col-md-1">{{ $movement['unit'] }}</td>
                                                <td class="col-md-1">
                                                    <code>{{ $movement['reference'] }}</code>
                                                </td>
                                                <td class="col-md-2">
                                                    @if($movement['type'] === 'Transfer')
                                                        <small>
                                                            <strong>From:</strong> {{ $movement['warehouse_from'] }}<br>
                                                            <strong>To:</strong> {{ $movement['warehouse_to'] }}
                                                        </small>
                                                    @else
                                                        <small>{{ $movement['warehouse_from'] }}</small>
                                                    @endif
                                                </td>
                                                <td class="col-md-1">
                                                    <span class="badge bg-{{ $movement['status'] === 'Completed' ? 'success' : 'warning' }}">
                                                        {{ $movement['status'] }}
                                                    </span>
                                                </td>
                                                <td class="col-md-1">
                                                    <ul class="icons-list">
                                                        <li><a href="#" data-toggle="modal" title="{{ __t('common.view_details', 'View Details') }}"><i class="icon-eye2"></i></a></li>
                                                        @if($movement['type'] === 'Transfer')
                                                            <li><a href="#" title="{{ __t('warehouse.view_transfer', 'View Transfer') }}"><i class="icon-arrow-right8"></i></a></li>
                                                        @endif
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="text-muted">
                                                        <i class="icon-arrow-right8" style="font-size: 48px; color: #ddd;"></i>
                                                        <br>No movements found for this warehouse
                                                    </div>
                                                </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stock Operations Tab -->
                    <div class="tab-pane" id="tab-stock-operations">
                        <div class="row col-md-12 col-xs-12">
                            <div class="panel-body">
                                @livewire('warehouse.warehouse-stock-operation', ['warehouseId' => $warehouse->id ?? null])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!------------------------------------  End Warehouse Detail ------------------------->
