<div class="tab-pane" id="tab-stock-card">
    {{-- Stock Card --}}
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4 class="panel-title">
                Stock Card Detail Statement
            </h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-xs-3">
                    <label>Start Date</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                        <input type="date" 
                               class="form-control" 
                               wire:model.live="startDate"
                               placeholder="Select start date">
                    </div>
                </div>

                <div class="col-md-3 col-xs-3">
                    <label>End Date</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                        <input type="date" 
                               class="form-control" 
                               wire:model.live="endDate"
                               placeholder="Select end date">
                    </div>
                </div>

                <div class="col-md-6 col-xs-6">
                    <div class="form-group">
                        <label class="display-block">Select Branch & Warehouse</label>
                        <select class="form-control" wire:model.live="selectedBranchId">
                            <option value="">All Branches & Warehouses</option>
                            @foreach($branches as $branch)
                                <optgroup label="🏢 {{ $branch->name_en }}">
                                    <option value="branch_{{ $branch->id }}" style="font-weight: bold;">
                                        Select All Warehouses in {{ $branch->name_en }}
                                    </option>
                                    @foreach($warehouses->where('branch_id', $branch->id) as $warehouse)
                                        <option value="warehouse_{{ $warehouse->id }}" style="padding-left: 20px;">
                                            📦 {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            <i class="icon-info22"></i> 
                            Select a branch to include all its warehouses, or select a specific warehouse.
                        </small>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-12 col-xs-12 text-right">
                <button class="btn bg-info" wire:click="viewStockCard">
                    <i class="icon-search4"></i> View Stock Card
                </button>
            </div> --}}
        </div>

        @if($product)
            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="row">
                        <!-- Remaining Stock Card -->
                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-body p-b-10">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-8">
                                            <h1 class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">
                                                {{ number_format($remainingStock) }} {{ $unitName }}
                                            </h1>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <i class="icon-cube2 icon-4x text-blue-lighter"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer bg-blue-lighter">
                                    <div class="elements">
                                        <span class="text-size-extralarge">Remaining Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Incoming Stock Card -->
                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-body p-b-10">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-8">
                                            <h1 class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">
                                                {{ number_format($incomingStock) }} {{ $unitName }}
                                            </h1>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <i class="icon-download4 icon-4x" style="color:#D0F1CF"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" style="background-color:#D0F1CF">
                                    <div class="elements">
                                        <span class="text-size-extralarge">Incoming Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Outgoing Stock Card -->
                        <div class="col-md-4">
                            <div class="panel panel-flat">
                                <div class="panel-body p-b-10">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-8">
                                            <h1 class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">
                                                {{ number_format($outgoingStock) }} {{ $unitName }}
                                            </h1>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <i class="icon-upload4 icon-4x icon-normal" style="color:#F1CFCF"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" style="background-color:#F1CFCF">
                                    <div class="elements">
                                        <span class="text-size-extralarge">Outgoing Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Movements Table -->
                <div class="table-responsive">
                    <table class="table datatable-stock-card">
                        <thead>
                            <tr>
                                <th>Move</th>
                                <th>Date</th>
                                <th>Document No.</th>
                                <th>Detail</th>
                                <th>Warehouse</th>
                                <th>Quantity In</th>
                                <th>Quantity Out</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockMovements as $movement)
                                <tr style="background-color:{{ $movement['color'] }}">
                                    <td>{{ $movement['type'] == 'in' ? 'In' : 'Out' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($movement['date'])->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="#" title="View Document">{{ $movement['document_no'] }}</a>
                                    </td>
                                    <td>{{ $movement['detail'] }}</td>
                                    <td>{{ $movement['warehouse'] }}</td>
                                    <td>{{ $movement['quantity_in'] > 0 ? number_format($movement['quantity_in']) : '-' }}</td>
                                    <td>{{ $movement['quantity_out'] > 0 ? number_format($movement['quantity_out']) : '-' }}</td>
                                    <td>{{ $movement['unit'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="icon-info22"></i> No stock movements found for the selected period.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if(count($stockMovements) > 0)
                                <!-- Summary Row -->
                                <tr class="bg-light">
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>{{ number_format($incomingStock) }}</strong></td>
                                    <td><strong>{{ number_format($outgoingStock) }}</strong></td>
                                    <td><strong>{{ $unitName }}</strong></td>
                                </tr>
                                
                                <!-- Remaining Stock Row -->
                                <tr class="bg-info">
                                    <td></td>
                                    <td><strong>Remaining</strong></td>
                                    <td><strong>{{ number_format($remainingStock) }}</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>{{ $unitName }}</strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="panel panel-flat">
                <div class="panel-body text-center">
                    <div class="alert alert-info">
                        <i class="icon-info22"></i> Please select a product to view stock card details.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/forms/picker.js') }}"></script>
    <script src="{{ asset('js/forms/picker.date.js') }}"></script>
    <script src="{{ asset('js/pages/pickers.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
@endpush
