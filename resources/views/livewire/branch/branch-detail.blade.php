<!-- resources/views/livewire/branch-detail.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="d-flex align-items-center justify-content-center w-100"
        style="position: fixed; top: 50%; left: 65%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="panel-body">
            <div class="loader">
                <div class="loader-inner ball-beat">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2) Hide the Form While Loading -->
    <div wire:loading.remove>
        <!-- Error Messages Display -->
        @if ($errors->has('general'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> {{ __t('common.error', 'Error') }}!</h4>
                {{ $errors->first('general') }}
            </div>
        @endif

        @if ($showAddBranchForm)
            @include('livewire.branch.branch-detail_addbranch')
        @elseif($showEditBranchForm && $branch)
            @include('livewire.branch.branch-detail_edit')
        @elseif($branch && $showEditBranchForm == false)
            @include('livewire.branch.branch-detail_tab')
        @elseif($branch == null)
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h4>{{ __t('branch.select_branch_to_view_details', 'Select a branch to view details') }}</h4>
                            <p class="text-muted">{{ __t('branch.choose_branch_from_list', 'Choose a branch from the list to see its information') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
    <style>
        /* Bootstrap-based export button styling */
        .dt-buttons {
            margin-bottom: 1rem;
        }
        
        .dt-buttons .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }
        
        .dt-buttons .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        /* DataTable responsive improvements using Bootstrap classes */
        .datatable-warehouse-list {
            font-size: 0.875rem;
        }
        
        .datatable-warehouse-list th {
            background-color: var(--bs-gray-100);
            font-weight: 600;
            border-bottom: 2px solid var(--bs-gray-300);
        }
        
        .datatable-warehouse-list td {
            vertical-align: middle;
        }
        
        /* Status badge styling */
        .datatable-warehouse-list .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
        
        /* Table header styling */
        .datatable-header {
            margin-bottom: 1rem;
        }
        
        .datatable-header .dataTables_length,
        .datatable-header .dataTables_filter {
            margin-bottom: 0.5rem;
        }
        
        /* Search input styling */
        .dataTables_filter input[type="search"] {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .dataTables_filter input[type="search"]:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* Length select styling */
        .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        /* Pagination styling */
        .dataTables_paginate .paginate_button {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            margin: 0 0.125rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .dataTables_paginate .paginate_button:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }
        
        .dataTables_paginate .paginate_button.current {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        /* Info text styling */
        .dataTables_info {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <!-- DataTable Export Libraries -->
    <script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
    
    <script>
        function confirmDelete(branchId) {
            Swal.fire({
                title: "{{ __t('common.are_you_sure', 'Are you sure?') }}",
                text: "{{ __t('common.action_cannot_be_undone', 'This action cannot be undone!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ __t('common.yes_delete_it', 'Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteBranch', {
                        branchId: branchId
                    });
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('branchCreated', data => {
            Swal.fire({
                icon: 'success',
                title: '{{ __t('common.create_success', 'Create Success') }}',
                text: data.message,
            });
        });

        Livewire.on('branchUpdated', data => {
            Swal.fire({
                icon: 'success',
                title: '{{ __t('common.update_success', 'Update Success') }}',
                text: data.message,
            });
        });

        Livewire.on('branchDeleted', data => {
            Swal.fire({
                icon: 'success',
                title: '{{ __t('common.delete_success', 'Delete Success') }}',
                text: data.message,
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('BranchDetail livewire:initialized');
            
            @this.on('branchSelected', () => {
                console.log('branchSelected');
                setTimeout(() => {
                    // Initialize any branch-specific functionality here
                    $('.venobox').venobox();
                }, 100);
            });

            @this.on('addBranch', () => {
                console.log('addBranch');
                setTimeout(() => {
                    // Initialize any add branch form functionality here
                }, 100);
            });
        });
    </script>

    <!-- Warehouse DataTable Scripts -->
    <script>
        console.log('🚀 WAREHOUSE DATATABLE SCRIPT LOADED - ' + new Date().toISOString());
        
        // Immediate check for existing table
        setTimeout(function() {
            console.log('🔍 IMMEDIATE CHECK:');
            console.log('  - warehouse table exists:', document.querySelector('.datatable-warehouse-list') ? '✅' : '❌');
            console.log('  - Current tab:', $('.tab-pane.active').attr('id') || 'none');
            console.log('  - All tabs:', $('.tab-pane').map(function() { return this.id; }).get());
            
            // If table exists and we're on warehouse tab, initialize immediately
            if (document.querySelector('.datatable-warehouse-list') && $('.tab-pane.active').attr('id') === 'tab-warehouse') {
                console.log('🚀 Table found and warehouse tab active, initializing immediately...');
                setTimeout(function() {
                    initWarehouseDataTable();
                }, 200);
            }
        }, 1000);

        // Helper functions for export customization
        // function getCurrentDateTimeFormatted() {
        //     var now = new Date();
        //     var year = now.getFullYear();
        //     var month = (now.getMonth() + 1).toString();
        //     var day = now.getDate().toString();
        //     var hours = now.getHours().toString();
        //     var minutes = now.getMinutes().toString();
        //     var seconds = now.getSeconds().toString();
            
        //     // Manual padding to avoid padStart issues
        //     month = month.length < 2 ? '0' + month : month;
        //     day = day.length < 2 ? '0' + day : day;
        //     hours = hours.length < 2 ? '0' + hours : hours;
        //     minutes = minutes.length < 2 ? '0' + minutes : minutes;
        //     seconds = seconds.length < 2 ? '0' + seconds : seconds;
            
        //     return year + month + day + '_' + hours + minutes + seconds;
        // }

        // function getBranchName() {
        //     try {
        //         var branchName = 'Unknown_Branch';
        //         var $table = $('.datatable-warehouse-list');
        //         if ($table.length) {
        //             var dataBranchName = $table.data('branch-name');
        //             if (dataBranchName && dataBranchName !== 'Unknown') {
        //                 branchName = dataBranchName;
        //             }
        //         }
        //         return branchName.replace(/[^a-zA-Z0-9]/g, '_').substring(0, 20);
        //     } catch (error) {
        //         console.error('Error getting branch name:', error);
        //         return 'Unknown_Branch';
        //     }
        // }

        // function getCompanyName() {
        //     try {
        //         var companyName = 'Unknown_Company';
        //         var $table = $('.datatable-warehouse-list');
        //         if ($table.length) {
        //             var dataCompanyName = $table.data('company-name');
        //             if (dataCompanyName && dataCompanyName !== 'Unknown') {
        //                 companyName = dataCompanyName;
        //             }
        //         }
        //         return companyName.replace(/[^a-zA-Z0-9]/g, '_').substring(0, 20);
        //     } catch (error) {
        //         console.error('Error getting company name:', error);
        //         return 'Unknown_Company';
        //     }
        // }

        // Destroy Warehouse DataTable safely
        function destroyWarehouseDataTable() {
            console.log('Destroying Warehouse DataTable...');
            
            var $table = $('.datatable-warehouse-list');
            
            if ($table.length === 0) {
                return;
            }

            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable($table)) {
                try {
                    console.log('Destroying existing DataTable...');
                    $table.DataTable().destroy();
                } catch (error) {
                    console.log('Error destroying DataTable:', error);
                }
            } else {
                console.log('Destroy skipped: warehouse table is not an initialized DataTable.');
            }

            // Clean up Select2 instances safely
            if ($.fn.select2) {
                try {
                    var $wrapper = $table.closest('.dataTables_wrapper');
                    if ($wrapper.length) {
                        var $lengthSelect = $wrapper.find('.dataTables_length select');
                        if ($lengthSelect.length) {
                            $lengthSelect.each(function() {
                                var $select = $(this);
                                if ($select.hasClass('select2-hidden-accessible')) {
                                    try {
                                        $select.select2('destroy');
                                    } catch (destroyError) {
                                        console.log('Select2 destroy error (cleanup):', destroyError);
                                    }
                                }
                            });
                        }
                    }
                } catch (select2Error) {
                    console.log('Error destroying Select2:', select2Error);
                }
            }

            // Clean up any remaining DataTable classes and attributes
            $table.removeClass('dataTable no-footer');
            $table.removeAttr('id');
            $table.find('thead th').removeAttr('tabindex');
            $table.find('tbody td').removeAttr('tabindex');

            // Remove any DataTable wrapper elements
            var $wrapper = $table.closest('.dataTables_wrapper');
            if ($wrapper.length) {
                // Move table back to original position
                $wrapper.before($table);
                $wrapper.remove();
            }

            // Clear any DataTable data attributes
            $table.removeData();
        }

        // Flag to prevent multiple initializations
        var isWarehouseDataTableInitializing = false;

        // Initialize Warehouse DataTable with export functionality
        function initWarehouseDataTable() {
            console.log('Initializing Warehouse DataTable...');

            // Prevent multiple simultaneous initializations
            if (isWarehouseDataTableInitializing) {
                console.log('DataTable initialization already in progress, skipping...');
                return;
            }

            isWarehouseDataTableInitializing = true;

            destroyWarehouseDataTable();

            var $table = $('.datatable-warehouse-list');

            if ($table.length === 0) {
                console.log('No warehouse table available to initialize. Skipping DataTable setup.');
                isWarehouseDataTableInitializing = false;
                return;
            }

            console.log('Found warehouse table, initializing with export buttons...');
            console.log('Table data attributes:', {
                hasData: $table.data('has-data'),
                branchName: $table.data('branch-name'),
                companyName: $table.data('company-name')
            });

            var hasDataAttr = $table.data('has-data');
            var hasData = hasDataAttr === 1 || hasDataAttr === '1' || hasDataAttr === true;

            // Always initialize DataTable even if no data (for empty state)
            console.log('Proceeding with DataTable initialization...');

            try {
                var dataTable = $table.DataTable({
                    ordering: false,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    dom: '<"datatable-header"fl B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                    language: {
                        search: '_INPUT_',
                        lengthMenu: ' _MENU_',
                        paginate: { 'first': '{{ __t('common.first', 'First') }}', 'last': '{{ __t('common.last', 'Last') }}', 'next': '&rarr;', 'previous': '&larr;' }
                    },
                    buttons: {
                        dom: {
                            button: {
                                className: 'btn btn-sm me-2 mb-2'
                            },
                            container: {
                                className: 'd-flex flex-wrap gap-2 mb-3'
                            }
                        },
                        buttons: [
                            {
                                extend: 'copy',
                                className: 'btn btn-primary btn-sm',
                                text: '<i class="icon-copy me-1"></i>Copy',
                                title: 'Warehouse List'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-success btn-sm',
                                text: '<i class="icon-file-text2 me-1"></i>CSV',
                                title: 'Warehouse List',
                                filename: 'Warehouse_List'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-info btn-sm',
                                text: '<i class="icon-file-excel me-1"></i>Excel',
                                title: 'Warehouse List',
                                filename: 'Warehouse_List'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-danger btn-sm',
                                text: '<i class="icon-file-pdf me-1"></i>PDF',
                                title: 'Warehouse List',
                                filename: 'Warehouse_List',
                                orientation: 'landscape',
                                pageSize: 'A4',
                                // customize: function(doc) {
                                //     // Ensure styles object and defaults exist before assignment
                                //     doc.styles = doc.styles || {};
                                //     doc.defaultStyle = doc.defaultStyle || {};

                                //     // Prepare style buckets the export relies on
                                //     doc.styles.tableHeader = doc.styles.tableHeader || {};
                                //     doc.styles.tableBodyEven = doc.styles.tableBodyEven || {};
                                //     doc.styles.tableBodyOdd = doc.styles.tableBodyOdd || {};

                                //     // Configure PDF to avoid font loading issues
                                //     doc.defaultStyle.fontSize = 8;
                                //     doc.defaultStyle.font = 'Roboto';

                                //     doc.styles.tableHeader.fontSize = 8;
                                //     doc.styles.tableHeader.font = 'Roboto';

                                //     doc.styles.tableBodyEven.fontSize = 8;
                                //     doc.styles.tableBodyEven.font = 'Roboto';

                                //     doc.styles.tableBodyOdd.fontSize = 8;
                                //     doc.styles.tableBodyOdd.font = 'Roboto';
                                    
                                //     // Set page margins
                                //     doc.pageMargins = [40, 60, 40, 60];
                                    
                                //     // Add header
                                //     doc.content.splice(0, 0, {
                                //         text: 'Warehouse List',
                                //         style: 'header',
                                //         alignment: 'center',
                                //         margin: [0, 0, 0, 20]
                                //     });
                                    
                                //     // Define header style
                                //     doc.styles.header = {
                                //         fontSize: 16,
                                //         bold: true,
                                //         alignment: 'center'
                                //     };
                                // }
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-warning btn-sm',
                                text: '<i class="icon-printer me-1"></i>Print',
                                title: 'Warehouse List'
                            }
                        ]
                    }
                });

                console.log('Warehouse DataTable initialized successfully with export buttons');

                var $filterInput = $('.dataTables_filter input[type=search]');
                if ($filterInput.length) {
                    $filterInput.attr('placeholder','{{ __t('common.type_to_search', 'Type to search...') }}');
                }

                var $lengthSelect = $('.dataTables_length select');
                if ($lengthSelect.length && $.fn.select2) {
                    $lengthSelect.each(function() {
                        var $select = $(this);

                        if ($select.hasClass('select2-hidden-accessible')) {
                            try {
                                $select.select2('destroy');
                            } catch (destroyError) {
                                console.log('Select2 destroy error (init):', destroyError);
                            }
                        }

                        try {
                            $select.select2({
                                minimumResultsForSearch: Infinity,
                                width: 'auto'
                            });
                        } catch (initError) {
                            console.log('Select2 init error:', initError);
                        }
                    });
                }

                // Simple export without custom filename handlers
                console.log('Export buttons initialized successfully');
            } catch (error) {
                console.error('DataTable initialization failed:', error);
                isWarehouseDataTableInitializing = false;
                return;
            }
            
            // Reset the flag when initialization is complete
            isWarehouseDataTableInitializing = false;
        }

        function scheduleWarehouseInit(reason, delay) {
            var actualDelay = typeof delay === 'number' ? delay : 300;
            console.log('Scheduling warehouse DataTable init (' + reason + ') in ' + actualDelay + 'ms');
            setTimeout(function() {
                initWarehouseDataTable();
            }, actualDelay);
        }

        // Manual trigger function for debugging/testing
        function forceWarehouseDataTableInit() {
            console.log('🔧 FORCE INITIALIZING WAREHOUSE DATATABLE');
            destroyWarehouseDataTable();
            setTimeout(function() {
                initWarehouseDataTable();
            }, 100);
        }

        // Make function globally available for debugging
        window.forceWarehouseDataTableInit = forceWarehouseDataTableInit;

        const resolveBoolean = (value, fallback = false) => {
            if (value === undefined || value === null) {
                return fallback;
            }

            if (typeof value === 'boolean') {
                return value;
            }

            if (typeof value === 'number') {
                return value !== 0;
            }

            if (typeof value === 'string') {
                const normalized = value.trim().toLowerCase();
                if (['false', '0', 'no', 'off', 'n'].includes(normalized)) {
                    return false;
                }
                if (['true', '1', 'yes', 'on', 'y'].includes(normalized)) {
                    return true;
                }
            }

            return Boolean(value);
        };

        if (typeof document !== 'undefined') {
            document.addEventListener('DOMContentLoaded', function() {
                scheduleWarehouseInit('DOMContentLoaded', 500);
            });

            document.addEventListener('livewire:load', function() {

                if (typeof Livewire !== 'undefined' && Livewire.hook) {
                    Livewire.hook('message.sent', function() {
                        destroyWarehouseDataTable();
                    });

                    Livewire.hook('message.processed', function(message, component) {
                        try {
                            var componentName = component?.fingerprint?.name || 'unknown';
                            var hasTableInComponent = false;

                            if (component && component.el && typeof component.el.querySelector === 'function') {
                                hasTableInComponent = !!component.el.querySelector('.datatable-warehouse-list');
                            }

                            var hasTableInDocument = !!document.querySelector('.datatable-warehouse-list');

                            if (hasTableInComponent || hasTableInDocument) {
                                scheduleWarehouseInit('Livewire message.processed (' + componentName + ')', 350);
                            }
                        } catch (hookError) {
                            console.error('Error in Livewire message.processed hook for warehouse init:', hookError);
                        }
                    });
                } else {
                    console.warn('Livewire.hook not available; falling back to livewire:updated event for warehouse init.');
                    document.addEventListener('livewire:updated', function() {
                        scheduleWarehouseInit('fallback livewire:updated', 350);
                    });
                }
            });

            // Listen for tab activation to initialize DataTable when warehouse tab is shown
            $(document).on('shown.bs.tab', 'a[href="#tab-warehouse"]', function() {
                console.log('Warehouse tab activated, initializing DataTable...');
                scheduleWarehouseInit('tab activation', 200);
            });

            // Listen for branch selection events
            document.addEventListener('livewire:initialized', () => {
                @this.on('branchSelected', () => {
                    console.log('Branch selected, scheduling warehouse DataTable init...');
                    scheduleWarehouseInit('branch selected', 500);
                });

                @this.on('refreshComponent', () => {
                    console.log('Component refresh requested, scheduling warehouse DataTable init...');
                    scheduleWarehouseInit('component refresh', 500);
                });
            });
        }

    </script>
@endpush
