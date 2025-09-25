@push('scripts')
    <script>
        // Global DataTable manager for extensibility
        window.DataTableManager = {
            tables: {},
            configs: {},
            
            // Default configuration
            defaultConfig: {
                autoWidth: true,
                colReorder: true,
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                lengthMenu: [10, 25, 50],
                language: {
                    search: '_INPUT_',
                    lengthMenu: '_MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': '&rarr;',
                        'previous': '&larr;'
                    }
                }
            },

            // Initialize a DataTable with custom configuration
            initTable: function(selector, config = {}) {
                try {
                    // Extract table ID from selector (remove # or .)
                    const tableId = selector.replace(/[#.]/g, '');
                    console.log(`Initializing DataTable with selector: ${selector}, tableId: ${tableId}`);
                    
                    // Check if element exists
                    if ($(selector).length === 0) {
                        console.error(`Element not found: ${selector}`);
                        return null;
                    }

                    // Check structure basics
                    const $table = $(selector);
                    if (!$table.find('thead').length || !$table.find('tbody').length) {
                        console.warn(`Table ${selector} missing thead or tbody, skipping initialization`);
                        return null;
                    }

                    // Check if table cells have proper structure
                    const firstRow = $table.find('tbody tr:first');
                    if (firstRow.length && firstRow.find('td').length === 0) {
                        console.warn(`Table ${selector} has no data cells, skipping initialization`);
                        return null;
                    }
                    
                    // Check if table is already initialized and return existing instance
                    if ($.fn.DataTable.isDataTable(selector)) {
                        console.log(`DataTable already exists for ${selector}, returning existing instance`);
                        this.tables[tableId] = $(selector).DataTable();
                        return this.tables[tableId];
                    }

                    // Merge with default config
                    const finalConfig = $.extend(true, {}, this.defaultConfig, config);
                    
                    // Initialize the table
                    this.tables[tableId] = $table.DataTable(finalConfig);
                    console.log(`DataTable initialized for ${tableId}:`, this.tables[tableId]);
                    
                    // Store configuration for later use
                    this.configs[tableId] = finalConfig;
                    
                    return this.tables[tableId];
                } catch (error) {
                    console.error(`Error initializing DataTable for ${selector}:`, error);
                    return null;
                }
            },

            // Setup external search for a table
            setupExternalSearch: function(tableId, searchInputId, clearButtonId = null) {
                let table = this.tables[tableId];
                // Lazy-init if not found
                if (!table) {
                    const selector = `#${tableId}`;
                    table = this.initTable(selector, { language: { search: '' } });
                    if (!table) {
                        console.error(`DataTable not found for tableId: ${tableId} and lazy init failed`);
                        return;
                    }
                }

                // Check if search input exists
                if ($(`#${searchInputId}`).length === 0) {
                    console.error(`Search input not found: #${searchInputId}`);
                    return;
                }

                console.log(`Setting up search for tableId: ${tableId}, searchInputId: ${searchInputId}`);

                // Setup search input with case-insensitive search
                $(`#${searchInputId}`).off('keyup.search').on('keyup.search', function() {
                    console.log(`Search triggered with value: "${this.value}"`);
                    table.search(this.value, false, false).draw(); // false = regex, false = smart search
                });

                // Setup clear button if provided
                if (clearButtonId && $(`#${clearButtonId}`).length > 0) {
                    $(`#${clearButtonId}`).off('click.clear').on('click.clear', function() {
                        console.log('Clear button clicked');
                        $(`#${searchInputId}`).val('');
                        table.search('').draw();
                    });
                }
            },

            // Setup date range filtering
            setupDateRangeFilter: function(tableId, fromDateId, toDateId, dateColumnIndex = 1) {
                const table = this.tables[tableId];
                if (!table) return;

                const filterName = `dateRange_${tableId}`;
                
                // Remove existing filter with same name
                $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(func) {
                    return func.name !== filterName;
                });

                // Add new date range filter
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        if (settings.nTable.id !== tableId) return true;
                        
                        const fromDate = $(`#${fromDateId}`).val();
                        const toDate = $(`#${toDateId}`).val();
                        const rowDate = new Date(data[dateColumnIndex]);
                        
                        if (fromDate && rowDate < new Date(fromDate)) return false;
                        if (toDate && rowDate > new Date(toDate)) return false;
                        
                        return true;
                    }
                );

                // Setup date change handlers
                $(`#${fromDateId}, #${toDateId}`).off('change.dateFilter').on('change.dateFilter', function() {
                    table.draw();
                });
            },

            // Clear all filters for a table
            clearFilters: function(tableId, searchInputId = null, dateInputs = []) {
                const table = this.tables[tableId];
                if (!table) return;

                // Clear search
                if (searchInputId) {
                    $(`#${searchInputId}`).val('');
                    table.search('').draw();
                }

                // Clear date inputs
                dateInputs.forEach(inputId => {
                    $(`#${inputId}`).val('');
                });

                // Redraw table to apply cleared filters
                table.draw();
            },

            // Get table instance
            getTable: function(tableId) {
                return this.tables[tableId];
            },

            // Destroy all tables
            destroyAll: function() {
                Object.keys(this.tables).forEach(tableId => {
                    if (this.tables[tableId]) {
                        this.tables[tableId].destroy();
                    }
                });
                this.tables = {};
                this.configs = {};
            }
        };

        function initDataTable() {
            console.log('Initializing DataTables...');
            
            // Check if DOM is ready and tables are properly rendered
            if (document.readyState !== 'complete') {
                console.warn('DOM not fully loaded, skipping DataTable initialization');
                return false;
            }
            
            // Initialize standard tables with reorder functionality
            if ($('.datatable-reorder-state-saving').length > 0 && !$.fn.DataTable.isDataTable('.datatable-reorder-state-saving')) {
                $('.datatable-reorder-state-saving').DataTable({
                    ...window.DataTableManager.defaultConfig,
                    stateSave: true,
                    fixedColumns: true,
                    scrollResize: true,
                    scrollX: true,
                    scrollCollapse: true
                });
            }

            // Check if inventory table exists and initialize if not already done
            if ($('#inventoryTable').length > 0) {
                console.log('Found inventory table, initializing...');
                const inventoryTable = window.DataTableManager.initTable('#inventoryTable', {
                    language: { search: '' } // Hide default search box
                });
                
                if (inventoryTable) {
                    window.DataTableManager.setupExternalSearch('inventoryTable', 'inventorySearchInput', 'clearInventorySearch');
                }
            } else {
                console.log('Inventory table not found');
            }

            // Check if movements table exists and initialize if not already done
            if ($('#movementsTable').length > 0) {
                console.log('Found movements table, initializing...');
                const movementsTable = window.DataTableManager.initTable('#movementsTable', {
                    language: { search: '' } // Hide default search box
                });
                
                if (movementsTable) {
                    window.DataTableManager.setupExternalSearch('movementsTable', 'movementSearchInput', 'clearMovementFilters');
                    window.DataTableManager.setupDateRangeFilter('movementsTable', 'movementDateFrom', 'movementDateTo', 1);
                }
            } else {
                console.log('Movements table not found');
            }

            // Add placeholder to default search inputs
            $('.dataTables_filter input[type=search]').attr('placeholder', '{{ __("Find") }}');

            // Enable Select2 for length options
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            // Setup row selection for lease orders
            $(".lease-order-row").on("click", function() {
                $(".lease-order-row").removeClass('active');
                $(this).addClass(' active');
            });
        }

        // Global functions for backward compatibility
        function clearInventorySearch() {
            window.DataTableManager.clearFilters('inventoryTable', 'inventorySearchInput');
        }

        function clearMovementFilters() {
            window.DataTableManager.clearFilters('movementsTable', 'movementSearchInput', ['movementDateFrom', 'movementDateTo']);
        }

        // Test function - you can call this from console to test search
        function testInventorySearch(searchTerm) {
            const table = window.DataTableManager.getTable('inventoryTable');
            if (table) {
                console.log(`Testing search with term: "${searchTerm}"`);
                table.search(searchTerm).draw();
            } else {
                console.error('Inventory table not found');
            }
        }

        // Initialize on page load with delay and retry mechanism
        let initAttempts = 0;
        const maxInitAttempts = 3;
        
        function tryInitDataTable() {
            initAttempts++;
            console.log(`DataTable initialization attempt ${initAttempts}/${maxInitAttempts}`);
            
            try {
                initDataTable();
            } catch (error) {
                console.warn(`DataTable initialization attempt ${initAttempts} failed:`, error);
                if (initAttempts < maxInitAttempts) {
                    setTimeout(() => {
                        tryInitDataTable();
                    }, 1000 * initAttempts); // Exponential backoff
                } else {
                    console.error('DataTable initialization failed after all attempts');
                }
            }
        }
        
        setTimeout(() => {
            tryInitDataTable();
        }, 1000);

        // Reinitialize on Livewire updates (only for specific events)
        document.addEventListener('livewire:initialized', () => {
            @this.on('{{ $listUpdatedEvent ?? 'userListUpdated' }}', () => {
                setTimeout(() => {
                    initDataTable();
                }, 500);
            });
        });

        // Only reinitialize on Livewire updates if warehouse data changes
        document.addEventListener('livewire:updated', (event) => {
            // Only reinitialize if the update is related to warehouse data
            if (event.detail && event.detail.component && event.detail.component.includes('warehouse')) {
                setTimeout(() => {
                    tryInitDataTable();
                }, 500);
            }
        });

        // Listen for Livewire content updates
        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                tryInitDataTable();
            }, 1000);
        });
    </script>
@endpush
