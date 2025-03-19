	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-shuffle position-left"></i> Transfer Product
            </div>
            @livewire('warehouse.warehouse-add-warehouse-btn')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    {{-- @livewire('user-list') --}}
                    
                    
                    
                    <div class="table-responsive">
                        <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
                            <thead>
                                <tr>
                                    <th scope="col"><?= __('Work list') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // foreach ($users as $l_user): ?>
                                <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                                    wire:click="$dispatch('ProfileSelected', { userId: {{ 1 }} })">
                                    <td>
                                        <div class="row col-md-12">
                                        
                                            
                                            <div class="col-md-11 col-sm-11">
                                                <div class="media-body">

                                                    <div class="media-heading text-size-extralarge text-dark">

                                                        warehouse_origin_name <i class="icon-arrow-right13 position-left"></i> warehouse_destination_name
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        user_request_name 
                                                    </div>
                                                    
                                                    <div class=" text-size-large text-dark">

                                                        transfer_slip_number
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        date_request 
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-success">

                                                        transfer_slip_status.name
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
                                        
                                            
                                            <div class="col-md-11 col-sm-11">
                                                <div class="media-body">

                                                    <div class="media-heading text-size-extralarge text-dark">

                                                        คลังหลัก <i class="icon-arrow-right13 position-left"></i> คลัภูเก็ต
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        #TF20250318001
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        requester :สมคิด 
                                                    </div>
                                                    
                                                    
                                                    <div class=" text-size-large text-dark">

                                                        18 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-info">

                                                        สินค้าพร้อมส่ง
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
                                        
                                            
                                            <div class="col-md-11 col-sm-11">
                                                <div class="media-body">

                                                    <div class="media-heading text-size-extralarge text-dark">

                                                        คลังหลัก <i class="icon-arrow-right13 position-left"></i> คลังสมุย
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        #TF20250322001
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        requester :สมคิด 
                                                    </div>
                                                    
                                                    
                                                    <div class=" text-size-large text-dark">

                                                        22 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-warning">

                                                        รอจัดสินค้า
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
                                            
                                            
                                            <div class="col-md-11 col-sm-11">
                                                <div class="media-body">

                                                    <div class="media-heading text-size-extralarge text-dark">

                                                        คลังหลัก <i class="icon-arrow-right13 position-left"></i> คลังสมุย
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        #TF20250322001
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        requester :สมคิด 
                                                    </div>
                                                    
                                                    
                                                    <div class=" text-size-large text-dark">

                                                        22 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-grey">

                                                        ยกเลิกคำร้อง
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
                                
                                
                                
                                <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                                    wire:click="$dispatch('ProfileSelected', { userId: {{ 1 }} })">
                                    <td>
                                        <div class="row col-md-12">
                                            
                                            
                                            <div class="col-md-11 col-sm-11">
                                                <div class="media-body">

                                                    <div class="media-heading text-size-extralarge text-dark">

                                                        คลังภูเก็ต <i class="icon-arrow-right13 position-left"></i> คลังสมุย
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        #TF20250322005
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        requester :สมคิด 
                                                    </div>
                                                    
                                                    
                                                    <div class=" text-size-large text-dark">

                                                        01 ธ.ค. 2568
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-success">

                                                        รับสินค้าเรียบร้อย
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
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-8">
                {{-- @livewire('user-profile') --}}
<!-----------------------------  End Check Stock List Detail    -------------------------->
                

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
<!--                                            
                                            <li class="">
                                                <a href="#tab-location" data-toggle="tab" aria-expanded="false">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">Location </h3>
                                                    </div>
                                                </a>
                                            </li>
-->

                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-detail">

                                        
                                    <div class="row col-md-12 col-xs-12">

                                        <div class="anel-heading no-padding-bottom">
                                            
                                            <h4 class="col-md-12 col-xs-12 col-lg-12 ">
                                                #TF20250225001 <span class="text-primary">(สินค้าพร้อมส่ง)</span>
                                            </h4>
                                            
                                        </div>
                                        <div class="panel-body">
                                            <div class="row col-md-12 col-xs-12 col-lg-12">
                                                <div class="col-md-3 col-xs-12 col-lg-3 text-left text-size-extralarge">
                                                    บริษัท แม็กพาวเวอร์ จำกัด 
                                                </div>
                                                <div class="col-md-9 col-xs-12 col-lg-9 text-left text-size-extralarge">
                                                    25 ก.พ. 2568  
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-lg-12 text-left text-size-extralarge">
                                                Description ตัวอย่างหน้าสำหรับการจัดการโอนย้ายสินค้า  
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-lg-12 text-left text-size-extralarge">
                                                Remark ไม่ใช่ข้อมูลจริง
                                            </div>
<!--                                             <div class="row col-md-12 col-xs-12 col-lg-12">
                                                
                                                 
                                            </div>-->
                                            <div class="row col-md-12 col-xs-12 col-lg-12">
                                                
                                                 
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-6 col-lg-6 text-left text-size-extralarge panel panel-white">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <span class="text-primary">คลังภูเก็ต</span>สินค้าออก
                                                        </h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                                วันที่จัดของ :
                                                            </div>
                                                            <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                                25 ก.พ.2568
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                                ผู้จัดสินค้า :
                                                            </div>
                                                            <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                                สมคิด แมวโหด(เหวอ)
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                </div>
                                                
                                                
                                                
                                                <div class="col-md-6 col-xs-6 col-lg-6 text-left text-size-extralarge panel panel-white">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <span class="text-primary">คลังสมุย</span>รับสินค้า
                                                        </h4>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                                วันที่รับของ :
                                                            </div>
                                                            <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                                -
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                                ผู้รับสินค้า :
                                                            </div>
                                                            <div class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                                ปิ๊กมี่ คนป่า(พี่หม่ำ)
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                            </div>
                                            
                                        </div>

                                                
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
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">1.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL209940032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีชมพู</td>
                                                                <td  class="col-md-3">4</td>
                                                                <td>แพค</td>

                                                        </tr>

                                                        <tr class="text-default">
                                                                <td   class="col-md-1">2.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL2957873032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีฟ้า</td>
                                                                <td  class="col-md-3">15</td>
                                                                <td>ชิ้น</td>

                                                        </tr>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">3.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">MM28324789232</a>
                                                                </td>
                                                                <td  class="col-md-5">ผ้าพันคอ ขนมิ๊งค์ สีเทา</td>
                                                                <td  class="col-md-3">30</td>
                                                                <td>ผืน</td>

                                                        </tr>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">4.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL2957873032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีฟ้า</td>
                                                                <td  class="col-md-3">20</td>
                                                                <td>ชิ้น</td>

                                                        </tr>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">5.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL2957873032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีฟ้า</td>
                                                                <td  class="col-md-3">1</td>
                                                                <td>กล่อง</td>

                                                        </tr>

                                                    </tbody>
                                            </table>
                                        </div>




                                    </div>

                                    </div>

                              
<!--
                                    <div class="tab-pane" id="tab-location">


                                        {{--For Location not use --}}
                                        Statement Location


                                    </div>
-->


                                </div>
                            </div>
                        </div>
                    </div>
                </div>                





<!------------------------------------  End Check Stock List Detail ------------------------->
                
                
                
                
                
                
            </div>
        </div> 
    </div>
    
@push('scripts')

<script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
<script>

        $.extend( $.fn.dataTable.defaults, {
                autoWidth: true,
                dom: '<"datatable-header"fl B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                        search: '_INPUT_',
                        lengthMenu: ' _MENU_',
                        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                }

        });

        // Basic initialization

        $('.datatable-transfer-detail').DataTable({
                ordering: false,
//                colReorder: true,
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
        $('.dataTables_filter input[type=search]').attr('placeholder','Type to search...');

        // Enable Select2 select for the length option
        $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
        });



</script>

@endpush    
    
</section>
<!--/Page Container-->