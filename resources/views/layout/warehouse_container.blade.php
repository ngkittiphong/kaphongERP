	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-home5 position-left"></i> Warehouse
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
                    
<!------------- Start Warehouse List ---->    
                    
                    <div class="table-responsive">
                        <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
                            <thead>
                                <tr>
                                    <th scope="col"><?= __('Warehouse') ?></th>
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

                                                        branch_name 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        avr_remain_price
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

                                                        คลังหลัก
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        สาขาภูเก็ต 
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        มูลค่า 745,000 บาท
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

                                                        คลังเล็ก
                                                    </div>

                                                    <div class=" text-size-large text-dark">

                                                        สาขาภูเก็ต
                                                    </div>
                                                    <div class=" text-size-large text-dark">

                                                        มูลค่า 430,000 บาท
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
                    
                    
<!------------- End Warehouse List ----> 
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                {{-- @livewire('user-profile') --}}
                
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
                                                <a href="#tab-location" data-toggle="tab" aria-expanded="false">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">Location </h3>
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

                              

                                    <div class="tab-pane" id="tab-location">


                                        {{--For Location not use --}}
                                        Statement Location


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