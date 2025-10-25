<!-----------------------------  Start Branch Detail    -------------------------->
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
                                        <h3 class="panel-title">{{ __t('common.detail', 'Detail') }}</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-warehouse" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('menu.warehouse', 'Warehouse') }}</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-user" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('menu.users', 'User') }}</h3>
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab-payment" data-toggle="tab" aria-expanded="false">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ __t('setting.payment', 'Payment') }}</h3>
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
                                <h4 class="panel-title">{{ __t('branch.branch_details', 'Branch details') }}</h4>
                                <div class="elements">
                                    <button class="btn bg-amber-darkest"
                                        wire:click="$dispatch('showEditBranchForm')">
                                        <i class="icon-pencil6"></i> {{ __t('branch.edit_branch', 'Edit Branch') }}
                                    </button>
                                    <button class="btn btn-danger" onclick="confirmDelete({{ $branch->id ?? 0 }})">
                                        <i class="icon-trash"></i> {{ __t('branch.delete_branch', 'Delete Branch') }}
                                    </button>
                                </div>
                                <a class="elements-toggle"><i class="icon-more"></i></a>
                            </div>
                            <div class="list-group list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ __t('branch.branch_name', 'ชื่อสาขา') }} :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $branch->name_th ?? 'N/A' }} ({{ $branch->name_en ?? 'N/A' }})
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ __t('branch.branch_code', 'Branch Code') }} :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $branch->branch_code ?? 'N/A' }} 
                                            @if($branch && $branch->is_head_office)
                                                ({{ __t('branch.head_office', 'สาขาหลัก') }})
                                            @endif
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ __t('branch.company', 'บริษัท') }} :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            {{ $branch->company->company_name_th ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ __t('branch.address', 'ที่อยู่') }} :
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-left">
                                            {{ $branch->address_th ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-left">
                                            {{ $branch->address_en ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ __t('branch.billing_address', 'ที่อยู่ออกใบเสร็จ') }} :
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-left">
                                            {{ $branch->bill_address_th ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-4 col-xs-4 text-left">
                                            {{ $branch->bill_address_en ?? 'N/A' }}
                                        </div>
                                    </span>
                                </div>
                                <div class='row'>
                                    <div class="col-md-4 col-xs-4 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-phone2"></i>{{ $branch->phone_number ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-md-8 col-xs-8 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-mobile"></i>{{ $branch->contact_mobile ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-md-4 col-xs-4 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-printer"></i>{{ $branch->fax ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-envelop3"></i>{{ $branch->email ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 text-bold">
                                        <span href="#" class="list-group-item p-l-20">
                                            <i class="icon-earth"></i>{{ $branch->website ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-3 col-xs-3 text-bold">
                                            {{ __t('common.status', 'สถานะ') }} :
                                        </div>
                                        <div class="col-md-8 col-xs-8 text-left">
                                            <span class="badge bg-{{ ($branch->branch_status_id ?? 0) == 1 ? 'success' : 'danger' }}">
                                                {{ ($branch->branch_status_id ?? 0) == 1 ? __t('common.active', 'Active') : __t('common.inactive', 'Inactive') }}
                                            </span>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-warehouse">
                        <div class="row col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table datatable-warehouse-list table-striped" 
                                       data-has-data="{{ count($warehouses) > 0 ? '1' : '0' }}"
                                       data-branch-name="{{ $branch->name_en ?? 'Unknown' }}"
                                       data-company-name="{{ $branch->company->company_name_th ?? 'Unknown' }}">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __t('warehouse.warehouse_name', 'Warehouse Name') }}</th>
                                            <th>{{ __t('warehouse.average_remaining_price', 'Average Remaining Price') }}</th>
                                            <th>{{ __t('common.status', 'Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouses as $index => $warehouse)
                                            <tr class="text-default">
                                                <td class="col-md-1">{{ $index + 1 }}.</td>
                                                <td class="col-md-5">{{ $warehouse->name }}</td>
                                                <td class="col-md-3">{{ number_format($warehouse->avr_remain_price ?? 0, 2) }} {{ __t('common.baht', 'บาท') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $warehouse->status->name === 'Active' ? 'success' : 'danger' }}">
                                                        {{ $warehouse->status->name }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">{{ __t('warehouse.no_warehouses_found_for_branch', 'No warehouses found for this branch') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-user">
                        <div class="row col-md-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table datatable-stock-card table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __t('user.avatar', 'Avatar') }}</th>
                                            <th>{{ __t('user.name', 'Name') }}</th>
                                            <th>{{ __t('user.username', 'Username') }}</th>
                                            <th>{{ __t('common.status', 'Status') }}</th>
                                            <th>{{ __t('common.action', 'Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __t('user.user_management_coming_soon', 'User management for this branch - Coming soon') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-payment">
                        <div class="row col-md-12 col-xs-12">
                            <div class="panel-heading no-padding-bottom">
                                <h4 class="panel-title">{{ __t('setting.bank_account', 'บัญชีธนาคาร') }}</h4>
                            </div>
                            <div class="list-group list-group-lg list-group-borderless">
                                <div class='row'>
                                    <span href="#" class="list-group-item p-l-20">
                                        <div class="col-md-12 col-xs-12 text-center">
                                            <p>{{ __t('setting.payment_information_coming_soon', 'Payment information for this branch - Coming soon') }}</p>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------  End Branch Detail ------------------------->
