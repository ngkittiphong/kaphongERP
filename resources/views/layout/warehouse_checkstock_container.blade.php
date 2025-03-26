	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-file-check position-left"></i> Check stock work list
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

                                                        warehouse_name_th
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        work date: 2 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        expire date: 10 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-success">

                                                        complete
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

                                                        คลังสมุย
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        work date: 2 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        expire date: 10 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-warning">

                                                        work expire
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

                                                        คลังสมุย
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        work date: 2 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        expire date: 10 มี.ค. 2568 
                                                    </div>
                                                    <div class=" text-size-large text-bold text-success">

                                                        complete
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

                                                        คลังหลัก
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        work date: 1 พ.ย. 2567 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        expire date: 10 พ.ย. 2567
                                                    </div>
                                                    <div class=" text-size-large text-bold  text-info">

                                                        in process
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

                                                        คลังภูเก็ต
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        work date: 12 ก.ย. 2567 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        expire date: 18 ก.ย. 2567 
                                                    </div>
                                                    <div class=" text-size-large text-warning text-bold">

                                                        work expire
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
                                        
                                        <div class="panel-body">
                                            <h4 class="col-md-12 col-xs-12 col-lg-12 text-bold">
                                                คลังหลัก
                                            </h4>
                                            <h5 class="col-md-12 col-xs-12 col-lg-12">
                                                #WCT20250204001
                                            </h5>
                                            <row>
                                                <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                    ผู้ดำเนินการ :
                                                </div>
                                                <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge">
                                                    วิโรจน์ แมวเรื้อน
                                                </div>
                                            </row>
                                            <row>
                                                <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                    duration :
                                                </div>
                                                <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge">
                                                    2 มี.ค.2568 - 10 มี.ค.2568
                                                </div>
                                            </row>
                                            <row>
                                                <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                    สถานะงาน :
                                                </div>
                                                <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge text-info">
                                                    กำลังตรวจสอบ
                                                </div>
                                            </row>
                                            <row>
                                                <div class="col-md-2 col-xs-2 col-lg-2 text-left text-size-extralarge">
                                                    ตรวจนับครั้งล่าสุด :
                                                </div>
                                                <div class="col-md-10 col-xs-10 col-lg-10 text-left text-size-extralarge">
                                                    5 มี.ค.2568
                                                </div>
                                            </row>
                                            
                                            
                                            
                                            
                                        </div>

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
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">1.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL209940032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีชมพู</td>
                                                                <td  class="col-md-3">11/11</td>
                                                                <td>ชิ้น</td>
                                                                <td  class="col-md-2">
                                                                    ครบถ้วน
                                                                </td>

                                                        </tr>

                                                        <tr class="text-default">
                                                                <td   class="col-md-1">2.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL2957873032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีฟ้า</td>
                                                                <td  class="col-md-3">8/11</td>
                                                                <td>ชิ้น</td>
                                                                <td  class="col-md-2">
                                                                    <div class="text-warning">ขาด</div>
                                                                </td>

                                                        </tr>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">3.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">MM28324789232</a>
                                                                </td>
                                                                <td  class="col-md-5">ผ้าพันคอ ขนมิ๊งค์ สีเทา</td>
                                                                <td  class="col-md-3">12/11</td>
                                                                <td>ผืน</td>
                                                                <td  class="col-md-2">
                                                                    <div class="text-warning">เกิน</div>
                                                                </td>

                                                        </tr>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">4.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL2957873032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีฟ้า</td>
                                                                <td  class="col-md-3">8/11</td>
                                                                <td>ชิ้น</td>
                                                                <td  class="col-md-2">
                                                                    <div class="text-warning">ขาด</div>
                                                                </td>

                                                        </tr>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">5.</td>
                                                                <td  class="col-md-1">
                                                                    <a href="#">WL2957873032</a>
                                                                </td>
                                                                <td  class="col-md-5">กระเป๋าเงินหนังจระเข้ สีฟ้า</td>
                                                                <td  class="col-md-3">8/11</td>
                                                                <td>ชิ้น</td>
                                                                <td  class="col-md-2">
                                                                    <div class="text-warning">ขาด</div>
                                                                </td>

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

        $('.datatable-check-stock-detail').DataTable({
//                ordering: false,
                colReorder: true,
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