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
                    <tr class="lease-order-row hover:bg-gray-100 cursor-pointer {{ $warehouse->status->name !== 'Active' ? 'opacity-60' : '' }}"
                        wire:click="selectWarehouse({{ $warehouse->id }})"
                        onclick="console.log('Warehouse clicked: {{ $warehouse->id }}')">
                        <td>
                            <div class="row col-md-12">
                                <div class="col-md-11 col-sm-11">
                                    <div class="media-body">
                                        <div class="media-heading text-size-extralarge {{ $warehouse->status->name === 'Active' ? 'text-dark' : 'text-muted' }}">
                                            {{ $warehouse->name }}
                                            @if($warehouse->status->name !== 'Active')
                                                <span class="badge bg-danger ml-2">{{ $warehouse->status->name }}</span>
                                            @endif
                                            @if($warehouse->main_warehouse)
                                                <span class="badge bg-primary ml-2">Main Warehouse</span>
                                            @endif
                                        </div>
                                        <div class=" text-size-large {{ $warehouse->status->name === 'Active' ? 'text-dark' : 'text-muted' }}">
                                            Branch: {{ $warehouse->branch->name_en ?? 'N/A' }}
                                        </div>
                                        <div class=" text-size-large {{ $warehouse->status->name === 'Active' ? 'text-dark' : 'text-muted' }}">
                                            Created: {{ $warehouse->date_create ? $warehouse->date_create->format('Y-m-d H:i') : 'N/A' }}
                                        </div>
                                        <div class=" text-size-large {{ $warehouse->status->name === 'Active' ? 'text-dark' : 'text-muted' }}">
                                            Creator: {{ $warehouse->userCreate->username ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-1">
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-{{ $warehouse->status->name === 'Active' ? 'success' : 'danger' }}" placeholder=""></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
</div>
