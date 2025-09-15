<div>
    @if($showAddForm)
        {{-- @livewire('warehouse.warehouse-add-checkstock-form') --}}
        <div class="panel-body">
            <div class="text-center text-muted py-5">
                <i class="icon-plus text-muted" style="font-size: 3rem;"></i>
                <h4 class="mt-3">Add New Check Stock Report</h4>
                <p>Add new check stock form will be implemented here</p>
            </div>
        </div>
    @elseif($checkStockReport)
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
                                    <div class="panel-body">
                                        <h4 class="col-md-12 col-xs-12 col-lg-12 text-bold">
                                            {{ $checkStockReport->warehouse->name ?? 'N/A' }}
                                        </h4>
                                        <h5 class="col-md-12 col-xs-12 col-lg-12">
                                            #WCT{{ $checkStockReport->id }}
                                        </h5>
                                        <row>
                                            <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                ผู้ดำเนินการ :
                                            </div>
                                            <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge">
                                                {{ $checkStockReport->userCreate->username ?? 'N/A' }}
                                            </div>
                                        </row>
                                        <row>
                                            <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                duration :
                                            </div>
                                            <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge">
                                                {{ $this->getDurationText($checkStockReport->datetime_create) }}
                                            </div>
                                        </row>
                                        <row>
                                            <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                สถานะงาน :
                                            </div>
                                            <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge {{ $this->getStatusTextColor($checkStockReport->closed, $checkStockReport->datetime_create) }}">
                                                {{ $this->getStatusText($checkStockReport->closed, $checkStockReport->datetime_create) }}
                                            </div>
                                        </row>
                                        <row>
                                            <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                ตรวจนับครั้งล่าสุด :
                                            </div>
                                            <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge">
                                                {{ $this->getLastCountDate($checkStockReport) }}
                                            </div>
                                        </row>
                                        
                                        @if(!$checkStockReport->closed)
                                            <row>
                                                <div class="col-md-12 col-xs-12 col-lg-12 text-right">
                                                    <button type="button" class="btn btn-success" wire:click="closeReport">
                                                        <i class="icon-checkmark position-left"></i>
                                                        Close Report
                                                    </button>
                                                </div>
                                            </row>
                                        @else
                                            <row>
                                                <div class="col-md-12 col-xs-12 col-lg-12 text-right">
                                                    <button type="button" class="btn btn-warning" wire:click="openReport">
                                                        <i class="icon-play position-left"></i>
                                                        Reopen Report
                                                    </button>
                                                </div>
                                            </row>
                                        @endif
                                    </div>

                                    @if($checkStockReport->checkStockDetails && $checkStockReport->checkStockDetails->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table datatable-check-stock-detail table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>รหัสสินค้า</th>
                                                        <th>ชื่อสินค้า</th>
                                                        <th>จำนวนนับ/ ตามระบบ</th>
                                                        <th>หน่วย</th>
                                                        <th>ผลการนับ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($checkStockReport->checkStockDetails as $index => $detail)
                                                        <tr class="text-default">
                                                            <td class="col-md-1">{{ $index + 1 }}.</td>
                                                            <td class="col-md-1">
                                                                <a href="#">{{ $detail->product->sku_number ?? 'N/A' }}</a>
                                                            </td>
                                                            <td class="col-md-5">{{ $detail->product->name ?? 'N/A' }}</td>
                                                            <td class="col-md-3">{{ $detail->product_scan_num }}/{{ $detail->product->quantity ?? 0 }}</td>
                                                            <td>{{ $detail->product->unit_name ?? 'N/A' }}</td>
                                                            <td class="col-md-2">
                                                                <div class="{{ $this->getCountResultClass($detail->product_scan_num, $detail->product->quantity ?? 0) }}">
                                                                    {{ $this->getCountResult($detail->product_scan_num, $detail->product->quantity ?? 0) }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="icon-inbox text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No check stock details found</p>
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
                        <h4 class="mt-3">No Check Stock Report Selected</h4>
                        <p>Please select a check stock report from the list to view details</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
