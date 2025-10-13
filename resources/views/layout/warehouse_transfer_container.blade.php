	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-shuffle position-left"></i> {{ __t('menu.transfer_product', 'Transfer Product') }}
            </div>
            @livewire('warehouse.warehouse-add-new-transfer')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: calc(100vh - 120px); overflow-y: auto;">
                    @livewire('warehouse.warehouse-transfer-list')
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-8">
                @livewire('warehouse.warehouse-transfer-detail')
            </div>
                                                        </div>
                                                    </div>
                                                    
@push('styles')
<style>
    .transfer-status-indicator {
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
    
    .transfer-row.selected .selection-indicator {
        display: flex;
    }
    
    .transfer-row.selected .status-circle {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .transfer-row:hover .status-circle {
        transform: scale(1.05);
    }
    
    .transfer-row:hover .selection-indicator {
        display: flex;
        opacity: 0.7;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .transfer-row {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .transfer-row:hover {
        border-left-color: #007bff;
        background-color: #f8f9fa !important;
    }
    
    .transfer-row.selected {
        border-left-color: #28a745;
        background-color: #e8f5e8 !important;
    }
    
    /* Compact list styling */
    .transfer-row td {
        padding: 4px 6px;
    }
    
    .media-body {
        line-height: 1.2;
    }
    
    .media-heading {
        margin-bottom: 2px;
    }
    
    .text-size-large {
        margin-bottom: 1px;
    }
    
    /* Reduce spacing in list items */
    .lease-order-row {
        border-bottom: 1px solid #f0f0f0;
        min-height: 60px;
    }
    
    .lease-order-row:hover {
        background-color: #f8f9fa !important;
    }
    
    /* Optimize sidebar content */
    .sidebar-content {
        padding: 0;
    }
    
    /* Compact table styling */
    .table-condensed > thead > tr > th,
    .table-condensed > tbody > tr > td {
        padding: 4px 6px;
    }
    
    /* Sticky header for better scrolling */
    .table thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        border-bottom: 2px solid #ddd;
    }
    
    /* Modal styling for transfer form */
    .modal.show {
        display: block !important;
    }
    
    .modal-xl {
        max-width: 95%;
    }
    
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Typeahead dropdown styles */
    .tt-menu {
        position: absolute !important;
        top: auto !important;
        bottom: calc(100% + 6px) !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 99999 !important;
        width: 100% !important;
        max-height: 260px !important;
        overflow-y: auto !important;
        background: #ffffff !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.08) !important;
    }

    .tt-menu::-webkit-scrollbar {
        width: 8px !important;
    }

    .tt-menu::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 4px !important;
    }

    .tt-menu::-webkit-scrollbar-thumb {
        background: #888 !important;
        border-radius: 4px !important;
    }

    .tt-menu::-webkit-scrollbar-thumb:hover {
        background: #555 !important;
    }

    .tt-suggestion {
        padding: 8px 12px !important;
        cursor: pointer !important;
        color: #333 !important;
        line-height: 1.4 !important;
        border-bottom: 1px solid #f0f0f0 !important;
        background: #fff !important;
    }

    .tt-suggestion:last-child {
        border-bottom: none !important;
    }

    .tt-suggestion:hover,
    .tt-suggestion.tt-cursor {
        background-color: #f5f5f5 !important;
    }

    .tt-highlight {
        font-weight: 600 !important;
        color: #2c3e50 !important;
    }

    .product-typeahead.tt-input {
        background-color: #fff !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    .panel-body {
        overflow: visible !important;
    }
</style>
@endpush
    
@push('scripts')

<script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        $('.dataTables_filter input[type=search]').attr('placeholder','{{ __t('common.type_to_search', 'Type to search...') }}');

        // Enable Select2 select for the length option
        $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
        });

        // Handle transfer row selection
        $(document).on('click', '.transfer-row', function() {
            // Remove selected class from all rows
            $('.transfer-row').removeClass('selected');
            
            // Add selected class to clicked row
            $(this).addClass('selected');
            
            // Get transfer ID
            var transferId = $(this).data('transfer-id');
            console.log('Selected transfer ID:', transferId);
        });

        // Listen for Livewire events
        window.addEventListener('transferSlipSelected', event => {
            console.log('Transfer slip selected:', event.detail);
            
            // Remove selected class from all rows
            $('.transfer-row').removeClass('selected');
            
            // Add selected class to the row with matching transfer ID
            var transferId = event.detail.id;
            $('.transfer-row[data-transfer-id="' + transferId + '"]').addClass('selected');
        });

        // Listen for show add transfer form event
        window.addEventListener('showAddNewTransferForm', event => {
            console.log('showAddNewTransferForm->warehouse-transfer-detail');
            Livewire.dispatch('showAddForm', {}, 'warehouse.warehouse-transfer-detail');
        });

        // Listen for show add transfer form with preselection event
        window.addEventListener('showAddFormWithPreselection', event => {
            console.log('showAddFormWithPreselection->warehouse-transfer-detail', event.detail);
            Livewire.dispatch('showAddFormWithPreselection', event.detail, 'warehouse.warehouse-transfer-detail');
        });

        // Listen for success message
        window.addEventListener('showSuccessMessage', event => {
            window.showSuccessAlert('{{ __t('common.success', 'Success!') }}', event.detail);
        });
        

</script>

@endpush    
    
</section>
<!--/Page Container-->