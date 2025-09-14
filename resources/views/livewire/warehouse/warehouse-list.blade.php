<div>
    <!------------- Start Warehouse List ---->

    <!-- Filter Buttons -->
    <div class="panel-body">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-default' }}" 
                    wire:click="setFilter('all')">
                All Warehouses
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'active' ? 'btn-success' : 'btn-default' }}" 
                    wire:click="setFilter('active')">
                Active Only
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'inactive' ? 'btn-danger' : 'btn-default' }}" 
                    wire:click="setFilter('inactive')">
                Deactivated Only
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
            <thead>
                <tr>
                    <th scope="col"><?= __('Warehouse') ?></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $warehouse)
                    <tr class="lease-order-row hover:bg-gray-100 cursor-pointer {{ !$warehouse->is_active ? 'opacity-60' : '' }}"
                        wire:click="$dispatch('warehouseSelected', { warehouseId: {{ $warehouse->id }} })">
                        <td>
                            <div class="row col-md-12">
                                <div class="col-md-11 col-sm-11">
                                    <div class="media-body">
                                        <div class="media-heading text-size-extralarge {{ $warehouse->is_active ? 'text-dark' : 'text-muted' }}">
                                            {{ $warehouse->name_th }}
                                            @if(!$warehouse->is_active)
                                                <span class="badge bg-danger ml-2">Deactivated</span>
                                            @endif
                                        </div>
                                        <div class=" text-size-large {{ $warehouse->is_active ? 'text-dark' : 'text-muted' }}">
                                            {{ $warehouse->warehouse_code }}
                                        </div>
                                        <div class=" text-size-large {{ $warehouse->is_active ? 'text-dark' : 'text-muted' }}">
                                            {{ $warehouse->branch->name_th ?? 'N/A' }}
                                        </div>
                                        <div class=" text-size-large {{ $warehouse->is_active ? 'text-dark' : 'text-muted' }}">
                                            {{ $warehouse->address_th ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-1">
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-{{ $warehouse->is_active ? 'success' : 'danger' }}" placeholder=""></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-datatable-scripts listUpdatedEvent="warehouseListUpdated" />
</div>
