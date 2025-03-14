	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-user position-left"></i> Product
            </div>
            @livewire('product.product-add-product-btn')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    
                    
                    
 <!------------- Start Product List ---->    
                    
                    <div class="table-responsive">
                        <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
                            <thead>
                                <tr>
                                    <th scope="col"><?= __('Products') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // foreach ($users as $l_user): ?>
                                <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                                    wire:click="$dispatch('ProfileSelected', { userId: {{ 1 }} })">
                                    <td>
                                        <div class="row col-md-12">
                                        <div class="col-md-3 col-sm-3">
                                            <div class="thumb">
                                                    <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                            <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="">
                                                            <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                    </a>
                                            </div>
                                        </div>
                                            
                                            <div class="col-md-8 col-sm-8">
                                        <div class="media-body">
                                            
                                            <div class="media-heading text-size-extralarge text-dark">

                                                product_nameeeeeeeeeeeeeee
                                            </div>

                                            <div class=" text-size-large text-dark">

                                                serial_number 
                                            </div>
                                            <div class=" text-size-large text-dark">

                                                สินค้านับสต๊อก, กระเป๋าถือ
                                            </div>
                                        </div>
                                            </div>
                                            
                                            <div class="col-md-1 col-sm-1">
                                                <div class="media-right media-middle">
                                                    <span class="status-mark bg-{{ 'green' }}" placeholder=""></span>
                                                </div>
                                            </div>
                                            </div>
                                    </td>
                                </tr>
                                <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                                    wire:click="$dispatch('ProfileSelected', { userId: {{ 1 }} })">
                                    <td>
                                        <div class="row col-md-12">
                                            <div class="col-md-3 col-sm-3">
                                                <div class="thumb">
                                                        <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="">
                                                                <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                        </a>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-8 col-sm-8">
                                        <div class="media-body">
                                            
                                            <div class="media-heading text-size-extralarge text-dark">
                                                กระเป๋าสตางค์
                                            </div>

                                            <div class=" text-size-large text-dark">

                                                WL330092004 
                                            </div>
                                            <div class=" text-size-large text-dark">

                                                สินค้านับสต๊อก, กระเป๋าหนังจระเข้
                                            </div>
                                        </div>
                                            </div>
                                            
                                            <div class="col-md-1 col-sm-1">
                                                <div class="media-right media-middle">
                                                    <span class="status-mark bg-{{ 'success-light' }}" placeholder=""></span>
                                                </div>
                                            </div>
                                            </div>
                                    </td>
                                </tr>
                                <?php // endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    
                    
<!------------- End Product List ---->                    
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                    
                
                
 
                
                
                
<!-----------------------------  Start Product Detail    -------------------------->
                
                
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
                                        <a href="#tab-stock-card" data-toggle="tab" aria-expanded="false">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Stock Card</h3>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab-trading" data-toggle="tab" aria-expanded="false">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Trading Detail </h3>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-detail">

                                <div class="row col-md-12 col-xs-12">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="text-center">
                                                    <div class="thumb">
                                                            <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="">
                                                                    <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                            </a>
                                                    </div>

                                            <h4 class="no-margin-bottom m-t-10"><i class=""
                                                    alt="{{ 'defefewfw' }}"></i>กระเป๋าสตางค์ Product name</h4>
                                            <div>WL330092004 serial number</div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xs-12">
                                        <!--<div class="panel panel-flat">-->
                                        <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title"><?= __('Product details') ?></h4>
                                            <div class="elements">
                                                <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                                <button class="btn bg-amber-darkest"
                                                    wire:click="$dispatch('showEditProfileForm')">Edit Product</button>
                                                <button class="btn btn-danger" onclick="confirmDelete({{ 1}})">Delete User</button>
                                            </div>
                                            <a class="elements-toggle"><i class="icon-more"></i></a>
                                        </div>
                                        <div class="list-group list-group-lg list-group-borderless">
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        หน่วยนับ :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product_unit' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        ประเภทสินค้า :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.type.name' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        กลุ่มสินค้า :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.group.name' }}
                                                    </div>
                                                </span>
                                            </div>
                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        หน่วยสินค้า :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        {{ 'product.unit.name' }}
                                                    </div>
                                                </span>
                                            </div>

                                            <div class='row'>
                                                <span href="#" class="list-group-item p-l-20">
                                                    <div class="col-md-3 col-xs-3 text-bold">
                                                        จำนวนคงเหลือ :
                                                    </div>
                                                    <div class="col-md-8 col-xs-8 text-left">
                                                        <div class="progress progress-xs m-b-10">
                                                            <div class="progress-bar progress-bar-warning  progress-bar-striped" style="width: 20%">
                                                                <span class="sr-only">20% Complete</span>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class='row col-md-12 col-xs-12'>
                                                            <div class="col-md-4 col-xs-4 text-left text-extrabold">
                                                                20
                                                            </div>
                                                            <div class="col-md-4 col-xs-4 text-center">
                                                                30
                                                            </div>
                                                            <div class="col-md-4 col-xs-4 text-right">
                                                                100
                                                            </div>

                                                        </div>
                                                    </div>
                                                </span>
                                                
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                            
                                </div>
                                
                                
                                
                                
                                
                                
                                <div class="row col-md-12 col-xs-12">
                                    <div class="row col-md-12 col-xs-12">
                                        <div class="panel-heading no-padding-bottom">
                                            <!--<h4 class="panel-title"><?= __('Wharehouse') ?></h4>-->
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                    <div class="panel panel-flat bg-light-light">
                                                            <div class="panel-heading text-dark">
                                                                    <h4 class="text-default panel-title">จำนวนรวมทุกคลัง</h4>
                                                            </div>
                                                            <div>
                                                                <div class="list-group text-default list-group-lg list-group-borderless">
                                                                    <div class='row'>
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                                จำนวนคงเหลือ :
                                                                            </div>
                                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                                1050 ชิ้น
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                    <div class='row'>
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                                ราคาขายเฉลี่ย :
                                                                            </div>
                                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                                150 บาท
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                    <div class='row'>
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                                ราคาซื้อเฉลี่ย :
                                                                            </div>
                                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                                130 บาท
                                                                            </div>
                                                                        </span>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                    </div>
                                            </div>

                                            <div class="col-md-4">
                                                    <div class="panel panel-flat bg-light-lighter">
                                                            <div class="panel-heading text-dark">
                                                                    <h3 class="text-default panel-title">คลังสินค้าหลัก</h3>
                                                            </div>
                                                            <div class="list-group text-default list-group-lg list-group-borderless">
                                                                    <div class='row'>
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                                จำนวนคงเหลือ :
                                                                            </div>
                                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                                400 ชิ้น
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                    <div class='row'>
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                                ราคาขายเฉลี่ย :
                                                                            </div>
                                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                                150 บาท
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                    <div class='row'>
                                                                        <span href="#" class="list-group-item p-l-20">
                                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                                ราคาซื้อเฉลี่ย :
                                                                            </div>
                                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                                120 บาท
                                                                            </div>
                                                                        </span>
                                                                    </div>

                                                                </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="panel panel-flat bg-light-lighter">
                                                        <div class="panel-heading text-dark">
                                                                <h3 class="text-default panel-title">Warehouse.name</h3>
                                                        </div>
                                                        <div class="list-group text-default list-group-lg list-group-borderless">
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-7 col-xs-7 text-bold">
                                                                            จำนวนคงเหลือ :
                                                                        </div>
                                                                        <div class="col-md-5 col-xs-5 text-left">
                                                                            <i class="icon-warning2 position-left text-warning"></i>
                                                                            650 ชิ้น
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-7 col-xs-7 text-bold">
                                                                            ราคาขายเฉลี่ย :
                                                                        </div>
                                                                        <div class="col-md-5 col-xs-5 text-left">
                                                                            150 บาท
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                <div class='row'>
                                                                    <span href="#" class="list-group-item p-l-20">
                                                                        <div class="col-md-7 col-xs-7 text-bold">
                                                                            ราคาซื้อเฉลี่ย :
                                                                        </div>
                                                                        <div class="col-md-5 col-xs-5 text-left">
                                                                            130 บาท
                                                                        </div>
                                                                    </span>
                                                                </div>

                                                            </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                
                                
                                <div class="row col-md-12 col-xs-12">
                                    <div class="panel-heading no-padding-bottom">
                                        <h4 class="panel-title">หน่วยสินค้าหลัก : {{ 'product.unit_name' }}</h4>
                                    </div>
                                    
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="panel panel-flat bg-slate-lighter">
                                                <div class="panel-heading text-dark">
                                                        <h3 class="text-default panel-title">โหล = 12 กล่อง</h3>
                                                </div>
                                                <div class="list-group text-default list-group-lg list-group-borderless">

                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                ราคาขาย :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                150 บาท
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                ราคาซื้อ :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                130 บาท
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-12 col-xs-12 text-bold">
                                                                บาร์โค๊ด :
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 text-left">
                                                                WL9920993-0003-33
                                                            </div>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="panel panel-flat bg-teal-light">
                                                <div class="panel-heading text-dark">
                                                        <h3 class="text-default panel-title">แพค = 120 กล่อง</h3>
                                                </div>
                                                <div class="list-group text-default list-group-lg list-group-borderless">

                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                ราคาขาย :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                150 บาท
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-7 col-xs-7 text-bold">
                                                                ราคาซื้อ :
                                                            </div>
                                                            <div class="col-md-5 col-xs-5 text-left">
                                                                130 บาท
                                                            </div>
                                                        </span>
                                                    </div>
                                                    <div class='row'>
                                                        <span href="#" class="list-group-item p-l-20">
                                                            <div class="col-md-12 col-xs-12 text-bold">
                                                                บาร์โค๊ด :
                                                            </div>
                                                            <div class="col-md-12 col-xs-12 text-left">
                                                                WL9920993-0003-33
                                                            </div>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            
                            
                                </div>
                                
                                
                                
                                
                                
                            </div>

        
                            
                    
                    
                    <div class="tab-pane" id="tab-stock-card">
                        {{-- Stoccard --}}
                        stock card detail statement
                        
                        @push('scripts')
                        <script src="{{ asset('js/forms/picker.js') }}"></script>
                        <script src="{{ asset('js/forms/picker.date.js') }}"></script>
                        <script src="{{ asset('js/pages/pickers.js') }}"></script>
                        <script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
                        @endpush
                        
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                    <h4 class="panel-title">
                                        Condition
                                    </h4>				
                            </div>
                            <div class="panel-body">
                                <div class="col-md-4 col-xs-4">
                                    <label>date start</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                        <input type="text" class="form-control pickadate" placeholder="Select">
                                    </div>

                                </div>

                                <div class="col-md-4 col-xs-4">
                                    <label>date end</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                        <input type="text" class="form-control pickadate" placeholder="Select">
                                    </div>

                                </div>

                                <div class="col-md-4 col-xs-4">

                                    <div class="form-group">
                                            <label class="display-block">เลือกคลัง</label>
                                            <select class="form-control">
                                                    <optgroup label="Mountain Time Zone">
                                                            <option value="AZ">Arizona</option>
                                                            <option value="CO">Colorado</option>
                                                            <option value="ID">Idaho</option>
                                                            <option value="WY">Wyoming</option>
                                                    </optgroup>
                                                    <optgroup label="Central Time Zone">
                                                            <option value="AL">Alabama</option>
                                                            <option value="AR">Arkansas</option>
                                                            <option value="KS">Kansas</option>
                                                            <option value="KY">Kentucky</option>
                                                    </optgroup>
                                                    <optgroup label="Eastern Time Zone">
                                                            <option value="CT">Connecticut</option>
                                                            <option value="DE">Delaware</option>
                                                            <option value="FL">Florida</option>
                                                            <option value="WV">West Virginia</option>
                                                    </optgroup>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12 text-right">
                                    <!--<div class="elements">-->
                                            <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                                            <button class="btn bg-info">view stockcard</button>
                                        <!--</div>-->

                                </div>
                            </div>
                            
                            
                            
                            
                            
                            <div class="panel panel-flat">
					<div class="panel-body">

                                            <div class="row">

                                                <div class="col-md-4">
                                                        <div class="panel panel-flat">
                                                                <div class="panel-body p-b-10">
                                                                        <div class="row">
                                                                                <div class="col-md-8 col-xs-8">
                                                                                        <h1  class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">200 ชิ้น</h2>
                                                                                </div>
                                                                                <div class="col-md-4 col-xs-4">
                                                                                        <i class="icon-cube2 icon-4x text-blue-lighter"></i>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="panel-footer bg-blue-lighter">							
                                                                        <div class="elements">
                                                                                <span class="text-size-extralarge">สินค้าคงเหลือ</span>
                                                                                <!--<a href="#" class="pull-right no-padding-right text-white">View all <i class="icon-arrow-right6 position-right"></i></a>-->
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                        <div class="panel panel-flat">
                                                                <div class="panel-body p-b-10">
                                                                        <div class="row">
                                                                                <div class="col-md-8 col-xs-8">
                                                                                        <h1  class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">540 ชิ้น</h2>
                                                                                        <!--<span class="">ชิ้น</span>-->
                                                                                </div>
                                                                                <div class="col-md-4 col-xs-4">
                                                                                        <i class="icon-download4 icon-4x" style="color:#D0F1CF"></i>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="panel-footer"  style="background-color:#D0F1CF">							
                                                                        <div class="elements">
                                                                                <span class="text-size-extralarge">จำนวนสินค้าเข้า</span>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                        <div class="panel panel-flat">
                                                                <div class="panel-body p-b-10">
                                                                        <div class="row">
                                                                                <div class="col-md-8 col-xs-8">
                                                                                        <h1  class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">340 ชิ้น</h2>
                                                                                </div>
                                                                                <div class="col-md-4 col-xs-4">
                                                                                        <i class="icon-upload4 icon-4x icon-normal" style="color:#F1CFCF"></i>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="panel-footer"  style="background-color:#F1CFCF">							
                                                                        <div class="elements">
                                                                                <span class="text-size-extralarge">จำนวนสินค้าออก</span>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        
                                        
                                        </div>
					
					<div class="table-responsive">
						<table class="table datatable-stock-card">
							<thead>
								<tr>
									<th>Move</th>
                                                                        <th>วันที่</th>
									<th>Document No.</th>
									<th>Detail</th>
									<th>Warehouse</th>
									<th>จำนวนเข้า</th>
									<th>จำนวนออก</th>
									<th>หน่วย</th>
								</tr>
							</thead>
							
							<tbody>
                                                            <tr class="" style="background-color:#D0F1CF">
									<td>เข้า</td>
                                                                        <td>3/12/2024</td>
                                                                        <td><a href="#">TF092904022</a></td>
									<td>ซื้อสินค้าเข้า</td>
                                                                        <td>คลังหลัก</td>
									<td>120</td>
                                                                        <td> - </td>
									<td>แพค</td>
									
								</tr>
								<tr style="background-color:#F1CFCF">
									<td>ออก</td>
                                                                        <td>5/12/2024</td>
									<td><a href="#">TF092904023</a></td>
									<td>ซื้อสินค้าออก</td>
                                                                        <td>คลังภูเก็ต</td>
									<td> - </td>
                                                                        <td> 12 </td>
									<td>กล่อง</td>
									
								</tr>
								<tr style="background-color:#D0F1CF">
									<td>เข้า</td>
                                                                        <td>20/12/2024</td>
									<td><a href="#">TF092904032</a></td>
									<td>ซื้อสินค้าเข้า</td>
                                                                        <td>คลังหลัก</td>
									<td>500</td>
                                                                        <td> - </td>
									<td> ชิ้น </td>
									
								</tr>
								<tr style="background-color:#D0F1CF">
									<td>เข้า</td>
                                                                        <td>3/12/2024</td>
									<td><a href="#">TF092904122</a></td>
									<td>ซื้อสินค้าเข้า</td>
                                                                        <td>คลังหลัก</td>
									<td>120</td>
                                                                        <td> - </td>
									<td>แพค</td>
									
								</tr>
								<tr style="background-color:#F1CFCF">
									<td>ออก</td>
                                                                        <td>3/12/2024</td>
									<td><a href="#">TF092904222</a></td>
									<td>เบิกขาย</td>
                                                                        <td>คลังหลัก</td>
									<td> - </td>
                                                                        <td> 35 </td>
									<td>แพค</td>
									
								</tr>
								<tr style="background-color:#D0F1CF">
									<td>เข้า</td>
                                                                        <td>3/12/2024</td>
									<td><a href="#">TF092904022</a></td>
									<td>ซื้อสินค้าเข้า</td>
                                                                        <td>คลังหลัก</td>
									<td>12</td>
                                                                        <td> - </td>
									<td>แพค</td>
									
								</tr>
                                                                
                                                                
                                                                
                                                                <tr>
									<td></td>
                                                                        <td>จำนวนรวม</td>
									<td></td>
									<td></td>
                                                                        <td></td>
									<td>540</td>
                                                                        <td>340</td>
									<td>ชิ้น</td>
									
                                                            </tr>
                                                            <tr>
									<td></td>
                                                                        
									<td>คงเหลือ</td>
									<td>200</td>
                                                                        <td></td>
                                                                        <td></td>
									<td></td>
                                                                        <td></td>
									<td>ชิ้น</td>
									
                                                            </tr>
							</tbody>
						</table>
					</div>
				</div>
                            
                            
                            
                        </div>
                        
                        
@push('scripts')
<script>

        $.extend( $.fn.dataTable.defaults, {
		autoWidth: true,
		dom: '<"datatable-header"l B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
		language: {
//			search: '_INPUT_',
			lengthMenu: ' _MENU_',
			paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
		}
	});

	// Basic initialization
	$('.datatable-stock-card').DataTable({
                ordering: false,
		buttons: {
			dom: {
				button: {
					className: 'btn'
				}
			},
			buttons: [
				{extend: 'copy', className: 'copyButton' },
				{extend: 'csv', className: 'csvButton' },
				{extend: 'print', className: 'printButton' }
			]
		}
	});

	// Add placeholder to the datatable filter option
//	$('.dataTables_filter input[type=search]').attr('placeholder','Type to search...');

	// Enable Select2 select for the length option
	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		width: 'auto'
	});



</script>


@endpush
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                    </div>
                            
                            
                            
                            
                            
                    <div class="tab-pane" id="tab-trading">
                        
                                <div class="row col-md-12 col-xs-12">
                                    <div class="col-md-6 col-xs-6">
                                        <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title"><?= __('Product sale details') ?></h4>
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
                                    <div class="row col-md-6 col-xs-6">
                                        
                                        <div class="panel-heading no-padding-bottom">
                                            <h4 class="panel-title"><?= __('Product buy details') ?></h4>
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

