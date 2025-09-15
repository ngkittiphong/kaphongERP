<div>
    <!------------- Start Warehouse Transfer List ---->

    <!-- Filter Buttons -->
    <div class="panel-body">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-default' }}" 
                    wire:click="setFilter('all')">
                All Transfers
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'pending' ? 'btn-warning' : 'btn-default' }}" 
                    wire:click="setFilter('pending')">
                Pending
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'completed' ? 'btn-success' : 'btn-default' }}" 
                    wire:click="setFilter('completed')">
                Completed
            </button>
            <button type="button" class="btn btn-sm {{ $filter === 'cancelled' ? 'btn-danger' : 'btn-default' }}" 
                    wire:click="setFilter('cancelled')">
                Cancelled
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
            <thead>
                <tr>
                    <th scope="col"><?= __('Work list') ?></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $transferSlip)
                    <tr class="lease-order-row hover:bg-gray-100 cursor-pointer transfer-row"
                        wire:click="selectTransferSlip({{ $transferSlip->id }})"
                        onclick="console.log('Transfer Slip clicked: {{ $transferSlip->id }}')"
                        data-transfer-id="{{ $transferSlip->id }}">
                        <td>
                            <div class="row col-md-12">
                                <div class="col-md-11 col-sm-11">
                                    <div class="media-body">
                                        <div class="media-heading text-size-extralarge text-dark">
                                            <i class="icon-warehouse position-left text-primary" style="font-size: 14px;"></i>
                                            {{ $transferSlip->warehouse_origin_name ?? $transferSlip->warehouseOrigin->name ?? 'N/A' }} 
                                            <i class="icon-arrow-right13 position-left text-muted" style="font-size: 12px;"></i> 
                                            <i class="icon-warehouse position-left text-success" style="font-size: 14px;"></i>
                                            {{ $transferSlip->warehouse_destination_name ?? $transferSlip->warehouseDestination->name ?? 'N/A' }}
                                        </div>

                                        <div class="text-size-large text-dark">
                                            <i class="icon-user position-left text-info" style="font-size: 12px;"></i>
                                            {{ $transferSlip->user_request_name ?? $transferSlip->userRequest->username ?? 'N/A' }}
                                        </div>
                                        
                                        <div class="text-size-large text-dark">
                                            <i class="icon-file-text position-left text-warning" style="font-size: 12px;"></i>
                                            {{ $transferSlip->transfer_slip_number }}
                                        </div>
                                        
                                        <div class="text-size-large text-dark">
                                            <i class="icon-calendar position-left text-muted" style="font-size: 12px;"></i>
                                            {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : 'N/A' }}
                                        </div>
                                        
                                        <div class="text-size-large text-bold {{ $this->getStatusTextColor($transferSlip->status->name ?? '') }}">
                                            <i class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }} position-left" style="font-size: 12px;"></i>
                                            {{ $transferSlip->status->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-1 col-sm-1">
                                    <div class="media-right media-middle">
                                        <div class="transfer-status-indicator">
                                            <div class="status-circle bg-{{ $this->getStatusBadgeColor($transferSlip->status->name ?? '') }}">
                                                <i class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }}"></i>
                                            </div>
                                            <div class="selection-indicator">
                                                <i class="icon-checkmark3"></i>
                                            </div>
                                        </div>
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
                                <p class="mt-2">No transfer slips found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-datatable-scripts listUpdatedEvent="transferSlipListUpdated" />
</div>
