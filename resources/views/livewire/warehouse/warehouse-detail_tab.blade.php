<!-----------------------------  Start Warehouse Detail    -------------------------->
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
                            <li class="">
                                <a href="#tab-inventory" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Inventory</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-movements" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Movements</h3>
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
                                <h4 class="panel-title"><?= __('Warehouse details') ?></h4>
                                <div class="elements">
                                    @if($warehouse->is_active)
                                        <button class="btn bg-amber-darkest"
                                            wire:click="$dispatch('showEditWarehouseForm')">
                                            <i class="icon-pencil6"></i> Edit Warehouse
                                        </button>
                                        <button class="btn btn-danger" onclick="confirmDelete({{ $warehouse->id ?? 0 }})">
                                            <i class="icon-trash"></i> Deactivate Warehouse
                                        </button>
                                    @else
                                        <button class="btn btn-success" onclick="confirmReactivate({{ $warehouse->id ?? 0 }})">
                                            <i class="icon-checkmark"></i> Reactivate Warehouse
                                        </button>
                                    @endif
                                </div>
                                <a class="elements-toggle"><i class="icon-more"></i></a>
                            </div>
                            <div class="list-group list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            ชื่อคลังสินค้า :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->name_th ?? 'N/A' }} ({{ $warehouse->name_en ?? 'N/A' }})
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            รหัสคลังสินค้า :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->warehouse_code ?? 'N/A' }} 
                                            @if($warehouse && $warehouse->is_main_warehouse)
                                                (คลังหลัก)
                                            @endif
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            สาขา :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->branch->name_th ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            ที่อยู่ :
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-left">
                                            {{ $warehouse->address_th ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-left">
                                            {{ $warehouse->address_en ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            คำอธิบาย :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $warehouse->description ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <div class="col-md-4 col-xs-4 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-phone2"></i>{{ $warehouse->phone_number ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-md-8 col-xs-8 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-mobile"></i>{{ $warehouse->contact_mobile ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-md-6 col-xs-6 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-envelop3"></i>{{ $warehouse->email ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-md-6 col-xs-6 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-user"></i>{{ $warehouse->contact_name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            สถานะ :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            <span class="badge bg-{{ $warehouse->is_active ? 'success' : 'danger' }}">
                                                {{ $warehouse->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-inventory">
                        <div class="row col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table datatable-inventory table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouse->inventories ?? [] as $index => $inventory)
                                            <tr class="text-default">
                                                <td class="col-md-1">{{ $index + 1 }}.</td>
                                                <td class="col-md-3">{{ $inventory->product->name_th ?? 'N/A' }}</td>
                                                <td class="col-md-2">{{ $inventory->product->product_code ?? 'N/A' }}</td>
                                                <td class="col-md-2">{{ $inventory->quantity ?? 0 }}</td>
                                                <td class="col-md-2">{{ $inventory->product->unit ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $inventory->quantity > 0 ? 'success' : 'danger' }}">
                                                        {{ $inventory->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                                    </span>
                                                </td>
                                                <td class="col-md-2">
                                                    <ul class="icons-list">
                                                        <li><a href="#" data-toggle="modal"><i class="icon-eye2"></i></a></li>
                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No inventory found for this warehouse</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-movements">
                        <div class="row col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table datatable-movements table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Reference</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7" class="text-center">Movement history for this warehouse - Coming soon</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------  End Warehouse Detail ------------------------->
