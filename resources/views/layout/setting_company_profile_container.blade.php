	<!--Page Container-->
    <section class="main-container">					
		
    
        <!--Page Header-->
        <div class="header no-margin-bottom">
            <div class="header-content">
                <div class="page-title">
                    <i class="icon-office position-left"></i> {{ __t('setting.company_setup', 'Company Setup') }}
                </div>
                {{-- @livewire('warehouse.warehouse-add-warehouse-btn') --}}
                
            </div>
        </div>		
        <!--/Page Header-->
    
        <div class="container-fluid page-people">
            <div class="row">
<!--                <div class="col-lg-3 col-md-4 col-sm-4 secondary-sidebar">
                    <div class="sidebar-content" style="height: 100vh">
                        {{-- @livewire('user-list') --}}
                    </div>
                </div>-->
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {{-- @livewire('user-profile') --}}
                    
                    
<!-----------------------------  Start Main container Detail    -------------------------->
                

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
                                                    <h3 class="panel-title">{{ __t('setting.detail', 'Detail') }}</h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-tax-setup" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ __t('setting.tax_setup', 'Tax Setup') }} </h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-patment-setup" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ __t('setting.payment_setup', 'Payment Setup') }} </h3>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="">
                                            <a href="#tab-payment" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ __t('setting.payment', 'Payment') }} </h3>
                                                </div>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                
                <!----------------------  Start Company Detail  ------------------------->                
                        
                                <div class="tab-pane active" id="tab-detail">

                                    <div class="row col-md-12 col-xs-12">

                                        <!--<div class="col-md-8 col-xs-12">-->
                                            <!--<div class="panel panel-flat">-->
                                            <div class="panel-heading no-padding-bottom">
                                                <h4 class="panel-title">{{ __t('setting.branch_details', 'Branch details') }}</h4>
                                                <div class="elements">
                                                    <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                                    {{-- <button class="btn bg-amber-darkest"
                                                        wire:click="$dispatch('showEditProfileForm')">Edit Branch</button>
                                                    <button class="btn btn-danger" onclick="confirmDelete({{ 1}})">Delete Branch</button> --}}
                                                </div>
                                                <a class="elements-toggle"><i class="icon-more"></i></a>
                                            </div>
                                            <div class="list-group list-group-lg list-group-borderless">
                                                <!-- Head Office Section -->
                                                @if($headOffice)
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">{{ __t('setting.head_office', 'Head Office') }}</h4>
                                                        <div class="elements">
                                                            <button class="btn bg-amber-darkest edit-branch-btn" data-branch-id="{{ $headOffice->id }}">{{ __t('common.edit', 'Edit') }}</button>
                                                            <button class="btn btn-danger delete-branch-btn" data-branch-id="{{ $headOffice->id }}">{{ __t('common.delete', 'Delete') }}</button>
                                                        </div>
                                                    </div>
                                                    <div class="branch-details" id="branch-details-{{ $headOffice->id }}">
                                                        <div class='row'>
                                                            <span href="#" class="list-group-item p-l-20">
                                                                <div class="col-md-3 col-xs-3 text-bold">
                                                                    {{ __t('setting.branch_name', 'Branch Name') }} :
                                                                </div>
                                                                <div class="col-md-8 col-xs-8 text-left">
                                                                    {{ $headOffice->name_th }} ({{ $headOffice->name_en }})
                                                                </div>
                                                            </span>
                                                        </div>
                                                        <div class='row'>
                                                            <span href="#" class="list-group-item p-l-20">
                                                                <div class="col-md-3 col-xs-3 text-bold">
                                                                    {{ __t('setting.branch_no', 'Branch No.') }} :
                                                                </div>
                                                                <div class="col-md-8 col-xs-8 text-left">
                                                                    {{ $headOffice->branch_code }} (สาขาหลัก)
                                                                </div>
                                                            </span>
                                                        </div>
                                                        <div class='row'>
                                                            <span href="#" class="list-group-item p-l-20">
                                                                <div class="col-md-3 col-xs-3 text-bold">
                                                                    {{ __t('setting.address', 'Address') }} :
                                                                </div>
                                                                <div class="col-md-4 col-xs-4 text-left">
                                                                    {{ $headOffice->address_th }}
                                                                </div>
                                                                <div class="col-md-4 col-xs-4 text-left">
                                                                    {{ $headOffice->address_en }}
                                                                </div>
                                                            </span>
                                                        </div>
                                                        <div class='row'>
                                                            <span href="#" class="list-group-item p-l-20">
                                                                <div class="col-md-3 col-xs-3 text-bold">
                                                                    {{ __t('setting.billing_address', 'Billing Address') }} :
                                                                </div>
                                                                <div class="col-md-4 col-xs-4 text-left">
                                                                    {{ $headOffice->bill_address_th ?? 'เหมือนที่อยู่สาขา' }}
                                                                </div>
                                                                <div class="col-md-4 col-xs-4 text-left">
                                                                    {{ $headOffice->bill_address_en }}
                                                                </div>
                                                            </span>
                                                        </div>

                                                        <div class='row'>
                                                            <div class="col-md-4 col-xs-4 text-bold">
                                                                <span href="#" class="list-group-item p-l-20">
                                                                    <i class="icon-phone2"></i>{{ $headOffice->phone }}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-8 col-xs-8 text-bold">
                                                                <span href="#" class="list-group-item p-l-20">
                                                                    <i class="icon-mobile"></i>{{ $headOffice->mobile }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class="col-md-4 col-xs-4 text-bold">
                                                                <span href="#" class="list-group-item p-l-20">
                                                                    <i class="icon-printer"></i>{{ $headOffice->fax }}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 col-xs-4 text-bold">
                                                                <span href="#" class="list-group-item p-l-20">
                                                                    <i class="icon-envelop3"></i>{{ $headOffice->email }}
                                                                </span>
                                                            </div>
                                                            <div class="col-md-4 col-xs-4 text-bold">
                                                                <span href="#" class="list-group-item p-l-20">
                                                                    <i class="icon-earth"></i>{{ $headOffice->website }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="branch-edit-form" id="branch-edit-form-{{ $headOffice->id }}" style="display: none;">
                                                        <form action="{{ route('branches.update', $headOffice->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Branch Code *</label>
                                                                        <input type="text" name="branch_code" class="form-control" value="{{ $headOffice->branch_code }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Branch Name (TH) *</label>
                                                                        <input type="text" name="name_th" class="form-control" value="{{ $headOffice->name_th }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Branch Name (EN) *</label>
                                                                        <input type="text" name="name_en" class="form-control" value="{{ $headOffice->name_en }}" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Address (TH)</label>
                                                                        <textarea name="address_th" class="form-control">{{ $headOffice->address_th }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Address (EN)</label>
                                                                        <textarea name="address_en" class="form-control">{{ $headOffice->address_en }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Phone</label>
                                                                        <input type="text" name="phone" class="form-control" value="{{ $headOffice->phone }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Mobile</label>
                                                                        <input type="text" name="mobile" class="form-control" value="{{ $headOffice->mobile }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Fax</label>
                                                                        <input type="text" name="fax" class="form-control" value="{{ $headOffice->fax }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Email</label>
                                                                        <input type="email" name="email" class="form-control" value="{{ $headOffice->email }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Website</label>
                                                                        <input type="url" name="website" class="form-control" value="{{ $headOffice->website }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group text-right">
                                                                <button type="button" class="btn btn-default cancel-edit-btn">{{ __t('common.cancel', 'Cancel') }}</button>
                                                                <button type="submit" class="btn btn-primary">{{ __t('common.save', 'Save') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning">
                                                        {{ __t('setting.no_active_head_office', 'No active head office found.') }}
                                                    </div>
                                                @endif

                                                <!-- Other Branches Section -->
                                                @if($otherBranches->count() > 0)
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">{{ __t('setting.other_branches', 'Other Branches') }}</h4>
                                                    </div>
                                                    @foreach($otherBranches as $branch)
                                                        <div class="branch-section">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">{{ $branch->name_th }}</h4>
                                                                <div class="elements">
                                                                    <button class="btn bg-amber-darkest edit-branch-btn" data-branch-id="{{ $branch->id }}">{{ __t('common.edit', 'Edit') }}</button>
                                                                    <button class="btn btn-danger delete-branch-btn" data-branch-id="{{ $branch->id }}">{{ __t('common.delete', 'Delete') }}</button>
                                                                </div>
                                                            </div>
                                                            <div class="branch-details" id="branch-details-{{ $branch->id }}">
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                                            {{ __t('setting.branch_name', 'Branch Name') }} :
                                                                        </div>
                                                                        <div class="col-md-8 col-xs-8 text-left">
                                                                            {{ $branch->name_th }} ({{ $branch->name_en }})
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                                            {{ __t('setting.branch_no', 'Branch No.') }} :
                                                                        </div>
                                                                        <div class="col-md-8 col-xs-8 text-left">
                                                                            {{ $branch->branch_code }}
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                                            {{ __t('setting.address', 'Address') }} :
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-4 text-left">
                                                                            {{ $branch->address_th }}
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-4 text-left">
                                                                            {{ $branch->address_en }}
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                                            {{ __t('setting.billing_address', 'Billing Address') }} :
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-4 text-left">
                                                                            {{ $branch->bill_address_th ?? 'เหมือนที่อยู่สาขา' }}
                                                                        </div>
                                                                        <div class="col-md-4 col-xs-4 text-left">
                                                                            {{ $branch->bill_address_en }}
                                                                        </div>
                                                                    </span>
                                                                </div>

                                                                <div class='row'>
                                                                    <div class="col-md-4 col-xs-4 text-bold">
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <i class="icon-phone2"></i>{{ $branch->phone }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-8 col-xs-8 text-bold">
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <i class="icon-mobile"></i>{{ $branch->mobile }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class='row'>
                                                                    <div class="col-md-4 col-xs-4 text-bold">
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <i class="icon-printer"></i>{{ $branch->fax }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-4 text-bold">
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <i class="icon-envelop3"></i>{{ $branch->email }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-4 text-bold">
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <i class="icon-earth"></i>{{ $branch->website }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="branch-edit-form" id="branch-edit-form-{{ $branch->id }}" style="display: none;">
                                                                <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.branch_code', 'Branch Code') }} *</label>
                                                                                <input type="text" name="branch_code" class="form-control" value="{{ $branch->branch_code }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.branch_name_th', 'Branch Name (TH)') }} *</label>
                                                                                <input type="text" name="name_th" class="form-control" value="{{ $branch->name_th }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.branch_name_en', 'Branch Name (EN)') }} *</label>
                                                                                <input type="text" name="name_en" class="form-control" value="{{ $branch->name_en }}" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.address_th', 'Address (TH)') }}</label>
                                                                                <textarea name="address_th" class="form-control">{{ $branch->address_th }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.address_en', 'Address (EN)') }}</label>
                                                                                <textarea name="address_en" class="form-control">{{ $branch->address_en }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.phone', 'Phone') }}</label>
                                                                                <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.mobile', 'Mobile') }}</label>
                                                                                <input type="text" name="mobile" class="form-control" value="{{ $branch->mobile }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.fax', 'Fax') }}</label>
                                                                                <input type="text" name="fax" class="form-control" value="{{ $branch->fax }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.email', 'Email') }}</label>
                                                                                <input type="email" name="email" class="form-control" value="{{ $branch->email }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>{{ __t('setting.website', 'Website') }}</label>
                                                                                <input type="url" name="website" class="form-control" value="{{ $branch->website }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <button type="button" class="btn btn-default cancel-edit-btn">{{ __t('common.cancel', 'Cancel') }}</button>
                                                                        <button type="submit" class="btn btn-primary">{{ __t('common.save', 'Save') }}</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>

                                        <!--</div>-->

                                    </div>




                                </div>


                <!----------------------  End Company Detail  ------------------------->


                
                
                <!----------------------  Start Tax Detail  ------------------------->
                                <div class="tab-pane" id="tab-tax-setup">


                                    <div class="row col-md-12 col-xs-12">
                                        
                                        <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title">{{ __t('setting.tax_setup', 'Tax setup') }}</h4>
                                            <div class="elements">
                                                <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                                <button class="btn bg-amber-darkest"
                                                    wire:click="$dispatch('showEditProfileForm')">{{ __t('common.edit', 'Edit') }}</button>
                                                <button class="btn btn-danger" onclick="confirmDelete({{ 1}})">{{ __t('common.delete', 'Delete') }}</button>
                                            </div>
                                            <a class="elements-toggle"><i class="icon-more"></i></a>
                                        </div>
                                        
                                        
                                            <span href="#" class="list-group-item p-l-20">
                                                <div class="col-md-12 col-xs-12 text-left">
                                                    <div class="col-md-3 col-sm-3">
                                                        <label class="text-bold">{{ __t('setting.vat_percent', 'ภาษีมูลค่าเพิ่ม (% VAT)') }} </label>
                                                        <div class="form-group">
                                                            <select class="form-control" wire:model="prefix_en">
                                                                <option value="1">5%</option>
                                                                <option value="2" selected="selected">7%</option>
                                                                <option value="3">10%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </span>
                                        
                                            <span href="#" class="list-group-item p-l-20">
                                                <div class="col-md-12 col-xs-12 text-left">
                                                    <div class="col-md-3 col-sm-3">
                                                        <label class="text-bold">{{ __t('setting.withholding_tax', 'ภาษีหัก ณ ที่จ่าย') }}  </label>
                                                        <div class="form-group">
                                                            <select class="form-control" wire:model="prefix_en">
                                                                <option value="1">1%</option>
                                                                <option value="2" selected="selected">3%</option>
                                                                <option value="3">10%</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </span>
                                        
                                        
                                        
                                            <span href="#" class="list-group-item p-l-20">
                                                <div class="col-md-12 col-xs-12 text-left">
                                                    <div class="col-md-3 col-sm-3">
                                                        <label class="text-bold">{{ __t('setting.currency', 'สกุลเงิน') }}  </label>
                                                        <div class="form-group">
                                                            <select class="form-control" wire:model="prefix_en">
                                                                <option value="1" selected="selected">Baht</option>
                                                                <option value="2" >USD</option>
                                                                <option value="3">EURO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </span>

                                            <span href="#" class="list-group-item p-l-20">
                                                <div class="col-md-12 col-xs-12 text-left">
                                                    <div class="col-md-3 col-sm-3">
                                                        <label class="text-bold">{{ __t('setting.default_language', 'ภาษาหลัก') }}  </label>
                                                        <div class="form-group">
                                                            <select class="form-control" wire:model="prefix_en">
                                                                <option value="1" selected="selected">Thai</option>
                                                                <option value="2" >Eng</option>
                                                                <option value="3">Chi</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </span>
                                    </div>



                                </div>

                
                <!----------------------  End Tax Detail  ------------------------->
                
                                <div class="tab-pane" id="tab-patment-setup">

                                    <div class="row col-md-12 col-xs-12">

                                        เงินสด,
                                        โอนเงิน, บัตรเครดิต, เช็ค
                                        
                                        ธนาคาร, เลขบัญชี, Acc

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
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        ราคาซื้อ :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.buy_price' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        {{ 'product.buy_vat.name' }} :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.buy_vat.percent' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        {{ 'product.buy_withholding.name' }} :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.buy_withholding.percent' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-12 col-xs-12 text-bold">
                                                        รายละเอียดการซื้อ :
                                                    </div>
                                                    <div class="col-md-12 col-xs-12 text-left">
                                                        {{ 'product.buy_description' }}
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row col-md-12 col-xs-12">
                                        <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title">{{ __t('setting.cash', 'เงินสด') }}</h4>
                                        </div>
                                        <div class="list-group list-group-lg list-group-borderless">
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        ราคาขาย :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.sale_price' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        {{ 'product.sale_vat.name' }}
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.sale_vat.percent' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        {{ 'product.sale_vat.name' }}
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.sale_withholding.percent' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-12 col-xs-12 text-bold">
                                                        รายละเอียดการขาย :
                                                    </div>
                                                    <div class="col-md-12 col-xs-12 text-left">
                                                        {{ 'product.sale_description' }}
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





<!------------------------------------  End Product Detail ------------------------->                    
                    
                    
                    
                    
                    
                    
                </div>
            </div> 
        </div>
    </section>
    <!--/Page Container-->

    @push('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $(document).ready(function() {
            // Edit button click handler
            $('.edit-branch-btn').click(function() {
                const branchId = $(this).data('branch-id');
                $(`#branch-details-${branchId}`).hide();
                $(`#branch-edit-form-${branchId}`).show();
            });

            // Cancel button click handler
            $('.cancel-edit-btn').click(function() {
                const branchId = $(this).closest('.branch-edit-form').attr('id').split('-').pop();
                $(`#branch-details-${branchId}`).show();
                $(`#branch-edit-form-${branchId}`).hide();
            });

            // Form submission handler
            $('.branch-edit-form form').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const branchId = form.closest('.branch-edit-form').attr('id').split('-').pop();
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the displayed data
                            const branch = response.branch;
                            $(`#branch-details-${branchId} .branch-name`).text(`${branch.name_th} (${branch.name_en})`);
                            $(`#branch-details-${branchId} .branch-address-th`).text(branch.address_th);
                            $(`#branch-details-${branchId} .branch-address-en`).text(branch.address_en);
                            $(`#branch-details-${branchId} .branch-phone`).text(branch.phone);
                            $(`#branch-details-${branchId} .branch-mobile`).text(branch.mobile);
                            $(`#branch-details-${branchId} .branch-fax`).text(branch.fax);
                            $(`#branch-details-${branchId} .branch-email`).text(branch.email);
                            $(`#branch-details-${branchId} .branch-website`).text(branch.website);

                            // Show success message
                            alert('{{ __t('setting.branch_updated_successfully', 'Branch updated successfully') }}');
                            
                            // Switch back to view mode
                            $(`#branch-details-${branchId}`).show();
                            $(`#branch-edit-form-${branchId}`).hide();
                        }
                    },
                    error: function(xhr) {
                        alert('{{ __t('setting.error_updating_branch', 'Error updating branch') }}: ' + (xhr.responseJSON?.message || '{{ __t('common.unknown_error', 'Unknown error') }}'));
                    }
                });
            });

            // Delete button click handler
            $('.delete-branch-btn').click(function() {
                const branchId = $(this).data('branch-id');
                if (confirm('{{ __t('setting.confirm_delete_branch', 'Are you sure you want to delete this branch?') }}')) {
                    $.ajax({
                        url: `/branches/${branchId}`,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove the branch section from the DOM
                                $(`#branch-details-${branchId}`).closest('.branch-section').remove();
                                alert('{{ __t('setting.branch_deleted_successfully', 'Branch deleted successfully') }}');
                            } else {
                                alert(response.message || '{{ __t('setting.error_deleting_branch', 'Error deleting branch') }}');
                            }
                        },
                        error: function(xhr) {
                            const response = xhr.responseJSON;
                            alert(response?.message || '{{ __t('setting.error_deleting_branch', 'Error deleting branch') }}');
                        }
                    });
                }
            });
        });
    </script>
    @endpush