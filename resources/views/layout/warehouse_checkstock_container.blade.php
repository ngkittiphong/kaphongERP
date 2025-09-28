	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-file-check position-left"></i> {{ __t('warehouse.check_stock_work_list', 'Check stock work list') }}
            </div>
            @livewire('warehouse.warehouse-add-check-stock-btn')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    @livewire('warehouse.warehouse-check-stock-list')
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-8">
                @livewire('warehouse.warehouse-check-stock-detail')
                        </div>
                    </div>
                </div>                

    @push('styles')
    <style>
        .checkstock-status-indicator {
            position: relative;
            display: inline-block;
        }
        
        .status-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .selection-indicator {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 14px;
            height: 14px;
            background: #007bff;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 8px;
            box-shadow: 0 2px 4px rgba(0,123,255,0.3);
            animation: pulse 2s infinite;
        }
        
        .checkstock-row.selected .selection-indicator {
            display: flex;
        }
        
        .checkstock-row.selected .status-circle {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .checkstock-row:hover .status-circle {
            transform: scale(1.05);
        }
        
        .checkstock-row:hover .selection-indicator {
            display: flex;
            opacity: 0.7;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .checkstock-row {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .checkstock-row:hover {
            border-left-color: #007bff;
            background-color: #f8f9fa !important;
        }
        
        .checkstock-row.selected {
            border-left-color: #28a745;
            background-color: #e8f5e8 !important;
        }
        
        /* Compact list styling */
        .checkstock-row td {
            padding: 8px 12px;
        }
        
        .media-body {
            line-height: 1.3;
        }
        
        .media-heading {
            margin-bottom: 4px;
        }
        
        .text-size-large {
            margin-bottom: 2px;
        }
        
        /* Reduce spacing in list items */
        .lease-order-row {
            border-bottom: 1px solid #f0f0f0;
        }
        
        .lease-order-row:hover {
            background-color: #f8f9fa !important;
        }
    </style>
    @endpush
    
@push('scripts')
<script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
<script>
        $.extend( $.fn.dataTable.defaults, {
                autoWidth: true,
                dom: '<"datatable-header"fl B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                        search: '_INPUT_',
                        lengthMenu: ' _MENU_',
                        paginate: { 'first': '{{ __t('common.first', 'First') }}', 'last': '{{ __t('common.last', 'Last') }}', 'next': '&rarr;', 'previous': '&larr;' }
                }
        });

        // Basic initialization
        $('.datatable-check-stock-detail').DataTable({
            ordering: false,
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
        $('.dataTables_filter input[type=search]').attr('placeholder','{{ __t('common.type_to_search', 'Type to search...') }}');

        // Enable Select2 select for the length option
        $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
        });

        // Handle checkstock row selection
        $(document).on('click', '.checkstock-row', function() {
            // Remove selected class from all rows
            $('.checkstock-row').removeClass('selected');
            
            // Add selected class to clicked row
            $(this).addClass('selected');
            
            // Get checkstock ID
            var checkstockId = $(this).data('checkstock-id');
            console.log('Selected checkstock ID:', checkstockId);
        });

        // Listen for Livewire events
        window.addEventListener('checkStockReportSelected', event => {
            console.log('Check stock report selected:', event.detail);
            
            // Remove selected class from all rows
            $('.checkstock-row').removeClass('selected');
            
            // Add selected class to the row with matching checkstock ID
            var checkstockId = event.detail.id;
            $('.checkstock-row[data-checkstock-id="' + checkstockId + '"]').addClass('selected');
        });

        // Listen for show add checkstock form event
        window.addEventListener('showAddNewCheckStockForm', event => {
            console.log('Show add checkstock form');
            Livewire.dispatch('showAddForm', {}, 'warehouse.warehouse-checkstock-detail');
        });

        // Listen for success message
        window.addEventListener('showSuccessMessage', event => {
            alert(event.detail);
        });
</script>
@endpush
</section>
<!--/Page Container-->