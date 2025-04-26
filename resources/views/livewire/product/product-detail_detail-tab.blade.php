<div class="tab-pane active" id="tab-detail">

    <div class="row col-md-12 col-xs-12">
        <div class="col-md-4 col-xs-12">
            <div class="text-center">
                <div class="thumb">
                    <a href="{{ asset('assets/images/default_product.png') }}" class="venobox">
                        <img src="{{ asset('assets/images/default_product.png') }}" alt="">
                        <span class="zoom-image"><i class="icon-plus2"></i></span>
                    </a>
                </div>

                <h4 class="no-margin-bottom m-t-10">
                    {{ $product->name }}
                </h4>
                <div>{{ $product->sku_number }}</div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <!--<div class="panel panel-flat">-->
            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title"><?= __('Product details') ?></h4>
                <div class="elements">
                    <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                    <button class="btn bg-amber-darkest" wire:click="$dispatch('showEditProductForm')">Edit
                        Product</button>
                    <button class="btn btn-danger" onclick="confirmDelete({{ 1 }})">Delete Product</button>
                </div>
                <a class="elements-toggle"><i class="icon-more"></i></a>
            </div>
            <div class="list-group list-group-lg list-group-borderless">
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            Count :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{-- {{ 'product_unit' }} --}}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            Product Type :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->type->name }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            Product Group :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->group->name }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            Product Unit :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->unit_name }}
                        </div>
                    </span>
                </div>

                {{-- <div class='row'>
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
                    
                    
                </div> --}}

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
