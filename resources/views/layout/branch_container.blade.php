	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-home position-left"></i> Branch
            </div>
            @livewire('branch.branch-add-branch-btn')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    @livewire('branch.branch-list')
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                   
                
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
                                            <a href="#tab-warehouse" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Warehouse</h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-user" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">User </h3>
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





                                <div class="tab-pane" id="tab-warehouse">


                                    <div class="row col-md-12 col-xs-12">

                                        <div class="table-responsive">
                                            <table class="table datatable-warehouse table-striped">
                                                    <thead>
                                                            <tr>
                                                                    <th>#</th>
                                                                    <th>Warehouse Name</th>
                                                                    <th>Average Remain Price</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr class="text-default">
                                                                <td   class="col-md-1">1.</td>
                                                                <td  class="col-md-5">คลังสาขาภูเก็ค</td>
                                                                <td  class="col-md-3">756,000 บาท</td>
                                                                <td>Active</td>
                                                                <td  class="col-md-2">
                                                                    <ul class="icons-list">
                                                                        <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                    </ul>
                                                                </td>

                                                        </tr>

                                                        <tr class="">
                                                            <td   class="col-md-1">2.</td>
                                                            <td  class="col-md-5">คลังสาขาภูเก็ค</td>
                                                            <td  class="col-md-3">756,000 บาท</td>
                                                            <td>Active</td>
                                                            <td  class="col-md-2">
                                                                <ul class="icons-list">
                                                                    <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                    <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                    <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                </ul>
                                                            </td>

                                                        </tr>
                                                        <tr class="text-default" >
                                                                <td   class="col-md-1">3.</td>
                                                                <td  class="col-md-5">คลังสาขาภูเก็ค</td>
                                                                <td  class="col-md-3">756,000 บาท</td>
                                                                <td>Active</td>
                                                                <td  class="col-md-2">
                                                                    <ul class="icons-list">
                                                                        <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                    </ul>
                                                                </td>

                                                        </tr>
                                                    </tbody>
                                            </table>
                                        </div>




                                    </div>



                                </div>

                                <div class="tab-pane" id="tab-user">

                                    <div class="row col-md-12 col-xs-12">

            <!--                            <div class="panel-heading">
                                            <h4 class="panel-title">Users</h4>						
                                        </div>-->
            <!--                            <div class="panel-body no-padding-bottom">
                                            <h3 class="panel-title">Users</h3>
                                        </div>-->

                                        <div class="table-responsive">
                                            <table class="table datatable-stock-card table-striped">
                                                    <thead>
                                                            <tr>
                                                                    <th>#</th>
                                                                    <th>Avatar</th>
                                                                    <th>Name</th>
                                                                    <th>Username</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                            </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr class="text-default" >
                                                                <td   class="col-md-1">1.</td>
                                                                <td  class="col-md-1">
                                                                    <div class="thumb media-middle">
                                                                        <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                                <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                                <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                                <td  class="col-md-3">somkid01</td>
                                                                <td>Active</td>
                                                                <td  class="col-md-2">
                                                                    <ul class="icons-list">
                                                                        <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                    </ul>
                                                                </td>

                                                        </tr>

                                                        <tr class="">
                                                                    <td   class="col-md-1">2.</td>
                                                                    <td  class="col-md-1">
                                                                        <div class="thumb media-middle">
                                                                            <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                                    <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                                    <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                    <td  class="col-md-5">วิโรจน์ แมวเรื้อน (ไอขี้ตา)</td>
                                                                    <td  class="col-md-3">somkid02</td>
                                                                    <td>Active</td>
                                                                    <td  class="col-md-2">
                                                                        <ul class="icons-list">
                                                                            <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                        </ul>
                                                                    </td>

                                                            </tr>
                                                        <tr class="text-default" >
                                                                <td   class="col-md-1">3.</td>
                                                                <td  class="col-md-1">
                                                                    <div class="thumb media-middle">
                                                                        <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                                <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                                <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                                <td  class="col-md-3">somkid01</td>
                                                                <td>Active</td>
                                                                <td  class="col-md-2">
                                                                    <ul class="icons-list">
                                                                        <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                            <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                            <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                    </ul>
                                                                </td>

                                                        </tr>
                                                        <tr class="">
                                                                <td   class="col-md-1">4.</td>
                                                                <td  class="col-md-1">
                                                                    <div class="thumb media-middle">
                                                                        <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                                <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                                <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                                <td  class="col-md-3">somkid01</td>
                                                                <td>Deactive</td>
                                                                <td  class="col-md-2">
                                                                    <ul class="icons-list">
                                                                        <li><a href="#" data-toggle="modal"><i class="icon-eye2"></i></a></li>
                                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                    </ul>
                                                                </td>

                                                        </tr>
                                                        <tr class="text-default" >
                                                                <td   class="col-md-1">5.</td>
                                                                <td  class="col-md-1">
                                                                    <div class="thumb media-middle">
                                                                        <a href="{{ asset('assets/images/faces/face_default.png') }}" class="venobox">
                                                                                <img src="{{ asset('assets/images/faces/face_default.png') }}" alt="" class="img-sm img-circle">
                                                                                <span class="zoom-image"><i class="icon-plus2"></i></span>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td  class="col-md-5">สมคิด แมวเรื้อน (ไอขี้มูก)</td>
                                                                <td  class="col-md-3">somkid01</td>
                                                                <td>user.status.name</td>
                                                                <td  class="col-md-3">
                                                                    <ul class="icons-list">
                                                                        <li><a href="#" data-toggle="modal" ><i class="icon-eye2"></i></a></li>
                                                                        <li><a href="#"><i class="icon-pencil6"></i></a></li>
                                                                        <li><a href="#"><i class="icon-trash text-danger"></i></a></li>
                                                                    </ul>
                                                                </td>

                                                        </tr>

                                                    </tbody>
                                            </table>
                                        </div>




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
    
 @push('scripts')
<script src="{{ asset('js/forms/picker.js') }}"></script>
<script src="{{ asset('js/forms/picker.date.js') }}"></script>
<script src="{{ asset('js/pages/pickers.js') }}"></script>
<script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
<script>

        $.extend( $.fn.dataTable.defaults, {
                autoWidth: true,
                dom: '<"datatable-header"l B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                        search: '_INPUT_',
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
        $('.datatable-warehouse').DataTable({
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

