	<!--Page Container-->
    <section class="main-container">					
		
    
        <!--Page Header-->
        <div class="header no-margin-bottom">
            <div class="header-content">
                <div class="page-title">
                    <i class="icon-office position-left"></i> Company Setup
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
                                                    <h3 class="panel-title">Detail</h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-tax-setup" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Tax Setup </h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-patment-setup" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Payment Setup </h3>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="">
                                            <a href="#tab-payment" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Payment </h3>
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
                                                <h4 class="panel-title"><?= __('Branch details') ?></h4>
                                                <div class="elements">
                                                    <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                                    <button class="btn bg-amber-darkest"
                                                        wire:click="$dispatch('showEditProfileForm')">Edit Branch</button>
                                                    <button class="btn btn-danger" onclick="confirmDelete({{ 1}})">Delete Branch</button>
                                                </div>
                                                <a class="elements-toggle"><i class="icon-more"></i></a>
                                            </div>
                                            <div class="list-group list-group-lg list-group-borderless">
                                                <div class='row'>
                                                    <span href="#" class="list-group-item p-l-20">
                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                            ชื่อสาขา :
                                                        </div>
                                                        <div class="col-md-8 col-xs-8 text-left">
                                                            {{ 'branch_name_th' }} ({{'branch_name_en'}})
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class='row'>
                                                    <span href="#" class="list-group-item p-l-20">
                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                            เลขที่สาขา :
                                                        </div>
                                                        <div class="col-md-8 col-xs-8 text-left">
                                                            {{ 'branch_no'}} (สาขาหลัก)
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class='row'>
                                                    <span href="#" class="list-group-item p-l-20">
                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                            ที่อยู่ :
                                                        </div>
                                                        <div class="col-md-4 col-xs-4 text-left">
                                                            158/9 หมู่ 1 ถ. ท่าตะโก - นครสวรรค์ ต.นครสวรรค์ออก อ.เมือง รหัสไปรษณีย์ 60000
                                                        </div>
                                                        <div class="col-md-4 col-xs-4 text-left">
                                                            address_en 85/255, Village No. 5,
                                                                        Na Kluea Sub-district,
                                                                        Bang Lamung District, 
                                                                        Chonburi Province, 
                                                                        Postal Code 20150
                                                        </div>
                                                    </span>
                                                </div>
                                                <div class='row'>
                                                    <span href="#" class="list-group-item p-l-20">
                                                        <div class="col-md-3 col-xs-3 text-bold">
                                                            ที่อยู่ออกใบเสร็จ :
                                                        </div>
                                                        <div class="col-md-4 col-xs-4 text-left">
                                                            เหมือนที่อยู่สาขา
                                                        </div>
                                                        <div class="col-md-4 col-xs-4 text-left">
                                                            bill_address_en 85/255, Village No. 5,
                                                                        Na Kluea Sub-district,
                                                                        Bang Lamung District, 
                                                                        Chonburi Province, 
                                                                        Postal Code 20150
                                                        </div>
                                                    </span>
                                                </div>


                                                <div class='row'>
                                                    <div class="col-md-4 col-xs-4 text-bold">

                                                        <span href="#" class="list-group-item p-l-20">
                                                            <i class="icon-phone2"></i>093-443-9949
                                                        </span>
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-bold">

                                                        <span href="#" class="list-group-item p-l-20">
                                                        <i class="icon-mobile"></i>093-443-9949
                                                    </span>
                                                    </div>



                                                </div>
                                                <div class='row'>
                                                    <div class="col-md-4 col-xs-4 text-bold">

                                                        <span href="#" class="list-group-item p-l-20">
                                                            <i class="icon-printer"></i>02-443-7482
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4 text-bold">
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <i class="icon-envelop3"></i>maxpower2@gmaol.co
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4 col-xs-4 text-bold">

                                                        <span href="#" class="list-group-item p-l-20">
                                                            <i class="icon-earth"></i>www.aaaa.com
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                        <!--</div>-->

                                    </div>




                                </div>


                <!----------------------  End Company Detail  ------------------------->


                
                
                <!----------------------  Start Tax Detail  ------------------------->
                                <div class="tab-pane" id="tab-tax-setup">


                                    <div class="row col-md-12 col-xs-12">
                                        
                                        <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title"><?= __('Tax setup') ?></h4>
                                            <div class="elements">
                                                <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                                <button class="btn bg-amber-darkest"
                                                    wire:click="$dispatch('showEditProfileForm')">Edit</button>
                                                <button class="btn btn-danger" onclick="confirmDelete({{ 1}})">Delete</button>
                                            </div>
                                            <a class="elements-toggle"><i class="icon-more"></i></a>
                                        </div>
                                        
                                        
                                            <span href="#" class="list-group-item p-l-20">
                                                <div class="col-md-12 col-xs-12 text-left">
                                                    <div class="col-md-3 col-sm-3">
                                                        <label class="text-bold">ภาษีมูลค่าเพิ่ม (% VAT) </label>
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
                                                        <label class="text-bold">ภาษีหัก ณ ที่จ่าย  </label>
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
                                                        <label class="text-bold">สกุลเงิน  </label>
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
                                                        <label class="text-bold">ภาษาหลัก  </label>
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
                                            <h4 class="panel-title"><?= __('บัญชีธนาคาร') ?></h4>
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
                                            <h4 class="panel-title"><?= __('เงินสด') ?></h4>
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