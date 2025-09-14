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
            <div class="col-lg-3 col-md-3 col-sm-3 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    @livewire('branch.branch-list')             
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9">
                @livewire('branch.branch-detail')
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

