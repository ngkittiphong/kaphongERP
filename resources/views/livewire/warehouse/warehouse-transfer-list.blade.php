<div>
    <!------------- Start Warehouse Transfer List ---->

    <!-- Filter Buttons -->
    <div class="panel-body" style="padding: 8px 12px;">
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-default' }}" 
                    wire:click="setFilter('all')">
                {{ __t('common.all', 'All') }}
            </button>
            <button type="button" class="btn {{ $filter === 'pending' ? 'btn-warning' : 'btn-default' }}" 
                    wire:click="setFilter('pending')">
                {{ __t('transfer_status.pending', 'Pending') }}
            </button>
            <button type="button" class="btn {{ $filter === 'in_transit' ? 'btn-info' : 'btn-default' }}" 
                    wire:click="setFilter('in_transit')">
                {{ __t('transfer_status.in_transit', 'In Transit') }}
            </button>
            <button type="button" class="btn {{ $filter === 'completed' ? 'btn-success' : 'btn-default' }}" 
                    wire:click="setFilter('completed')">
                {{ __t('common.done', 'Done') }}
            </button>
            <button type="button" class="btn {{ $filter === 'cancelled' ? 'btn-danger' : 'btn-default' }}" 
                    wire:click="setFilter('cancelled')">
                {{ __t('transfer_status.cancelled', 'Cancel') }}
            </button>
        </div>
    </div>

    <div class="table-responsive" style="max-height: calc(100vh - 200px); overflow-y: auto;">
        <table class="table table-hover table-condensed" style="margin-bottom: 0;">
            <thead style="position: sticky; top: 0; background: white; z-index: 10;">
                <tr>
                    <th scope="col" style="padding: 8px 12px;">{{ __t('warehouse.work_list', 'Work list') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $transferSlip)
                    <tr class="lease-order-row transfer-row"
                        wire:click="selectTransferSlip({{ $transferSlip->id }})"
                        onclick="console.log('Transfer Slip clicked: {{ $transferSlip->id }}')"
                        data-transfer-id="{{ $transferSlip->id }}"
                        style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 6px 8px;">
                            <div class="row" style="margin: 0;">
                                <div class="col-xs-10 col-sm-10" style="padding: 0 4px;">
                                    <div class="media-body">
                                        <div class="media-heading text-dark" style="font-size: 13px; margin-bottom: 2px; line-height: 1.2;">
                                            <i class="icon-warehouse position-left text-primary" style="font-size: 11px;"></i>
                                            {{ Str::limit($transferSlip->warehouse_origin_name ?? $transferSlip->warehouseOrigin->name ?? 'N/A', 15) }} 
                                            <i class="icon-arrow-right13 position-left text-muted" style="font-size: 10px;"></i> 
                                            <i class="icon-warehouse position-left text-success" style="font-size: 11px;"></i>
                                            {{ Str::limit($transferSlip->warehouse_destination_name ?? $transferSlip->warehouseDestination->name ?? 'N/A', 15) }}
                                        </div>

                                        <div class="text-dark" style="font-size: 11px; margin-bottom: 1px;">
                                            <i class="icon-user position-left text-info" style="font-size: 10px;"></i>
                                            {{ $transferSlip->user_request_name ?? $transferSlip->userRequest->username ?? 'N/A' }}
                                        </div>
                                        
                                        <div class="text-dark" style="font-size: 11px; margin-bottom: 1px;">
                                            <i class="icon-file-text position-left text-warning" style="font-size: 10px;"></i>
                                            {{ $transferSlip->transfer_slip_number }}
                                        </div>
                                        
                                        <div class="text-dark" style="font-size: 11px; margin-bottom: 1px;">
                                            <i class="icon-calendar position-left text-muted" style="font-size: 10px;"></i>
                                            {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : 'N/A' }}
                                        </div>
                                        
                                        <div class="text-bold {{ $this->getStatusTextColor($transferSlip->status->name ?? '') }}" style="font-size: 11px;">
                                            <i class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }} position-left" style="font-size: 10px;"></i>
                                            {{ $this->getTranslatedStatusName($transferSlip->status->name ?? '') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-2 col-sm-2" style="padding: 0 4px; text-align: right;">
                                    <div class="transfer-status-indicator">
                                        <div class="status-circle bg-{{ $this->getStatusBadgeColor($transferSlip->status->name ?? '') }}" style="width: 20px; height: 20px; font-size: 10px;">
                                            <i class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }}"></i>
                                        </div>
                                        <div class="selection-indicator" style="width: 10px; height: 10px; font-size: 6px;">
                                            <i class="icon-checkmark3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="1" class="text-center text-muted" style="padding: 20px;">
                            <div>
                                <i class="icon-inbox text-muted" style="font-size: 1.5rem;"></i>
                                <p class="mt-2" style="margin: 8px 0;">No transfer slips found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-datatable-scripts listUpdatedEvent="transferSlipListUpdated" />
</div>
