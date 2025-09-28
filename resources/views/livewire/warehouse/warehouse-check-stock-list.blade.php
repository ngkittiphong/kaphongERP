<div>
    <!------------- Start Warehouse Check Stock List ---->

    <!-- Filter Buttons -->
    <div class="panel-body">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-default' }}" 
                    wire:click="setFilter('all')">
                {{ __t('warehouse.all_reports', 'All Reports') }}
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'pending' ? 'btn-warning' : 'btn-default' }}" 
                    wire:click="setFilter('pending')">
                {{ __t('warehouse.in_process', 'In Process') }}
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'completed' ? 'btn-success' : 'btn-default' }}" 
                    wire:click="setFilter('completed')">
                {{ __t('warehouse.completed', 'Completed') }}
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'expired' ? 'btn-danger' : 'btn-default' }}" 
                    wire:click="setFilter('expired')">
                {{ __t('warehouse.expired', 'Expired') }}
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
            <thead>
                <tr>
                    <th scope="col">{{ __t('warehouse.work_list', 'Work list') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $checkStockReport)
                    <tr class="lease-order-row hover:bg-gray-100 cursor-pointer transfer-row"
                        wire:click="selectCheckStockReport({{ $checkStockReport->id }})"
                        onclick="console.log('Check Stock Report clicked: {{ $checkStockReport->id }}')"
                        data-checkstock-id="{{ $checkStockReport->id }}">
                        <td>
                            <div class="row col-md-12">
                                <div class="col-md-11 col-sm-11">
                                    <div class="media-body">
                                        <div class="media-heading text-size-extralarge text-dark">
                                            {{ $checkStockReport->warehouse->name ?? 'N/A' }}
                                        </div>

                                        <div class="text-size-large text-dark">
                                            {{ __t('warehouse.work_date', 'work date') }}: {{ $this->getWorkDate($checkStockReport->datetime_create) }}
                                        </div>
                                        
                                        <div class="text-size-large text-dark">
                                            {{ __t('warehouse.expire_date', 'expire date') }}: {{ $this->getExpireDate($checkStockReport->datetime_create) }}
                                        </div>
                                        
                                        <div class="text-size-large text-bold {{ $this->getStatusTextColor($checkStockReport->closed, $checkStockReport->datetime_create) }}">
                                            {{ $this->getStatusText($checkStockReport->closed, $checkStockReport->datetime_create) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-1 col-sm-1">
                                    <div class="media-right media-middle">
                                        <span class="status-mark bg-{{ $this->getStatusColor($checkStockReport->closed, $checkStockReport->datetime_create) }}" placeholder=""></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="1" class="text-center text-muted">
                            <div class="py-4">
                                <i class="icon-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2">{{ __t('warehouse.no_check_stock_reports_found', 'No check stock reports found') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-datatable-scripts listUpdatedEvent="checkStockReportListUpdated" />
</div>
