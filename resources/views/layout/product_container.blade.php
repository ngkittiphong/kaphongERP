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
                                            <div class=" text-size-large text-danger">
                                                130 ใบ เกินคลัง
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
                                            <div class=" text-size-large text-primary">
                                                70 ใบ คลังปกติ
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

