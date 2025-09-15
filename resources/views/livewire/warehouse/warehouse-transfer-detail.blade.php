<div>
    @if($transferSlip)
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
                                        <h4 class="col-md-12 col-xs-12 col-lg-12">
                                            <i class="icon-file-text position-left text-warning"></i>
                                            {{ $transferSlip->transfer_slip_number }} 
                                            <span class="text-primary">
                                                <i class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }} position-left"></i>
                                                ({{ $transferSlip->status->name ?? 'N/A' }})
                                            </span>
                                        </h4>
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
                                                        <span class="text-primary">{{ $transferSlip->warehouse_origin_name ?? $transferSlip->warehouseOrigin->name ?? 'N/A' }}</span> สินค้าออก
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            วันที่จัดของ :
                                                        </div>
                                                        <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            ผู้จัดสินค้า :
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
                                                        <span class="text-primary">{{ $transferSlip->warehouse_destination_name ?? $transferSlip->warehouseDestination->name ?? 'N/A' }}</span> รับสินค้า
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            วันที่รับของ :
                                                        </div>
                                                        <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->date_receive ? $transferSlip->date_receive->format('d M Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            ผู้รับสินค้า :
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
                                                        <th>รหัสสินค้า</th>
                                                        <th>ชื่อสินค้า</th>
                                                        <th>จำนวนนับ</th>
                                                        <th>หน่วย</th>
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
</div>
