<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon-check position-left"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon-cross position-left"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($showAddForm)
        @livewire('warehouse.warehouse-add-transfer-form')
    @elseif($transferSlip)
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row p-l-10 p-r-10 panel panel-flat">
                        <div class="panel-heading">
                            <div class="tabbable">
                                <ul class="nav nav-tabs nav-tabs-highlight">
                                    <li class="active">
                                        <a href="#tab-detail" data-toggle="tab" class="panel-title" aria-expanded="true">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Detail</h3>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-detail">
                                <div class="row col-md-12 col-xs-12">
                                    <div class="panel-heading no-padding-bottom">
                                        <div class="row">
                                            <div class="col-md-8 col-xs-8 col-lg-8">
                                                <h4>
                                                    <i class="icon-file-text position-left text-warning"></i>
                                                    {{ $transferSlip->transfer_slip_number }} 
                                                    <span class="text-primary">
                                                        <i class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }} position-left"></i>
                                                        ({{ $transferSlip->status->name ?? 'N/A' }})
                                                    </span>
                                                </h4>
                                            </div>
                                            <div class="col-md-4 col-xs-4 col-lg-4 text-right">
                                                <div class="btn-group">
                                                    @if($this->canChangeStatus())
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="icon-cog position-left"></i>
                                                            Change Status
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                                                            @foreach($this->getAllowedStatusChanges() as $status)
                                                                <a class="dropdown-item" href="#" wire:click="confirmStatusChange({{ $status->id }})" style="display: block; width: 100%; padding: 10px 16px; border-bottom: 1px solid #f0f0f0; clear: both;">
                                                                    <i class="icon-{{ $this->getStatusIcon($status->name) }} position-left text-{{ $this->getStatusColor($status->name) }}" style="margin-right: 8px;"></i>
                                                                    <span style="display: inline-block; vertical-align: middle;">{{ $status->name }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    @if($this->canCancelTransfer())
                                                        <button type="button" class="btn btn-danger" wire:click="showCancelModal">
                                                            <i class="icon-cross position-left"></i>
                                                            Cancel Transfer
                                                        </button>
                                                    @endif
                                                    
                                                    @if(!$this->canChangeStatus() && !$this->canCancelTransfer())
                                                        <div class="text-muted">
                                                            <i class="icon-lock position-left"></i>
                                                            Status Locked
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="row col-md-12 col-xs-12 col-lg-12">
                                            <div class="col-md-3 col-xs-12 col-lg-3 text-left text-size-extralarge">
                                                <i class="icon-office position-left text-primary"></i>
                                                {{ $transferSlip->company_name ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-9 col-xs-12 col-lg-9 text-left text-size-extralarge">
                                                <i class="icon-calendar position-left text-info"></i>
                                                {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : 'N/A' }}
                                            </div>
                                        </div>
                                        
                                        @if($transferSlip->description)
                                            <div class="col-md-12 col-xs-12 col-lg-12 text-left text-size-extralarge">
                                                Description: {{ $transferSlip->description }}
                                            </div>
                                        @endif
                                        
                                        @if($transferSlip->note)
                                            <div class="col-md-12 col-xs-12 col-lg-12 text-left text-size-extralarge">
                                                Remark: {{ $transferSlip->note }}
                                            </div>
                                        @endif
                                        
                                        <div class="row">
                                            <div class="col-md-6 col-xs-6 col-lg-6 text-left text-size-extralarge panel panel-white">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <span class="text-primary">{{ $transferSlip->warehouse_origin_name ?? $transferSlip->warehouseOrigin->name ?? 'N/A' }}</span> - Outbound
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            Pick Date:
                                                        </div>
                                                        <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            Picker:
                                                        </div>
                                                        <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->user_request_name ?? $transferSlip->userRequest->username ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-xs-6 col-lg-6 text-left text-size-extralarge panel panel-white">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <span class="text-primary">{{ $transferSlip->warehouse_destination_name ?? $transferSlip->warehouseDestination->name ?? 'N/A' }}</span> - Inbound
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            Receive Date:
                                                        </div>
                                                        <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->date_receive ? $transferSlip->date_receive->format('d M Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            Receiver:
                                                        </div>
                                                        <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->user_receive_name ?? $transferSlip->userReceive->username ?? '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($transferSlip->transferSlipDetails && $transferSlip->transferSlipDetails->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table datatable-transfer-detail table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Product Code</th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transferSlip->transferSlipDetails as $index => $detail)
                                                        <tr class="text-default">
                                                            <td class="col-md-1">{{ $index + 1 }}.</td>
                                                            <td class="col-md-1">
                                                                <a href="#">{{ $detail->product->sku_number ?? 'N/A' }}</a>
                                                            </td>
                                                            <td class="col-md-5">{{ $detail->product->name ?? 'N/A' }}</td>
                                                            <td class="col-md-3">{{ $detail->quantity ?? 0 }}</td>
                                                            <td>{{ $detail->product->unit_name ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="icon-inbox text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No product details found</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center text-muted py-5">
                        <i class="icon-inbox text-muted" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">No Transfer Selected</h4>
                        <p>Please select a transfer slip from the list to view details</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Change Confirmation Modal -->
    @if($showStatusChangeModal)
        <div class="modal fade in" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="icon-warning text-warning position-left"></i>
                            Confirm Status Change
                        </h5>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to change the status of transfer slip <strong>{{ $transferSlip->transfer_slip_number }}</strong> to <strong>{{ $selectedStatusName }}</strong>?</p>
                        @if($selectedStatusName === 'Delivered')
                            <div class="alert alert-info">
                                <i class="icon-info position-left"></i>
                                This will also set the receive date to today and assign you as the receiver.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" wire:click="cancelStatusChange">
                            <i class="icon-cross position-left"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="confirmStatusUpdate">
                            <i class="icon-check position-left"></i>
                            Confirm Change
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancellation Modal -->
    @if($showCancelModal)
        <div class="modal fade in" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="icon-warning text-danger position-left"></i>
                            Cancel Transfer
                        </h5>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel transfer slip <strong>{{ $transferSlip->transfer_slip_number }}</strong>?</p>
                        
                        <div class="form-group">
                            <label for="cancellationReason" class="control-label">Cancellation Reason <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control" 
                                id="cancellationReason" 
                                wire:model="cancellationReason" 
                                rows="3" 
                                placeholder="Please provide a reason for cancelling this transfer..."
                                required
                            ></textarea>
                            @error('cancellationReason') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        
                        @if($transferSlip->status->name === 'In Transit')
                            <div class="alert alert-warning">
                                <i class="icon-warning position-left"></i>
                                <strong>Note:</strong> This transfer is currently in transit. Cancelling will restore the inventory to the sender warehouse.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" wire:click="hideCancelModal">
                            <i class="icon-cross position-left"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="cancelTransfer">
                            <i class="icon-cross position-left"></i>
                            Cancel Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
