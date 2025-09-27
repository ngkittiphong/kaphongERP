<!-- resources/views/livewire/warehouse-detail.blade.php -->
<div class="row p-l-10 p-r-10">
    <!-- 1) Show Loading Spinner (centered) when busy -->
    <div wire:loading.flex class="flex items-center justify-center w-full"
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
        @if ($showAddWarehouseForm)
            @include('livewire.warehouse.warehouse-detail_addwarehouse')
        @elseif($showEditWarehouseForm && $warehouse)
            @include('livewire.warehouse.warehouse-detail_edit')
        @elseif($warehouse && $showEditWarehouseForm == false)
            @include('livewire.warehouse.warehouse-detail_tab')
        @elseif($warehouse == null)
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h4>Select a warehouse to view details</h4>
                            <p class="text-muted">Choose a warehouse from the list to see its information</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        function confirmDelete(warehouseId) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will deactivate the warehouse. You can reactivate it later if needed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, deactivate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteWarehouse', {
                        warehouseId: warehouseId
                    });
                }
            });
        }

        function confirmReactivate(warehouseId) {
            Swal.fire({
                title: "Reactivate Warehouse?",
                text: "This will reactivate the warehouse and make it available for use.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, reactivate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('reactivateWarehouse', {
                        warehouseId: warehouseId
                    });
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        Livewire.on('warehouseCreated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Create Success',
                text: data.message || 'Warehouse created successfully!',
            });
        });

        Livewire.on('warehouseUpdated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Update Success',
                text: data.message || 'Warehouse updated successfully!',
            });
        });

        Livewire.on('warehouseDeleted', data => {
            Swal.fire({
                icon: 'success',
                title: 'Deactivation Success',
                text: data.message || 'Warehouse deactivated successfully!',
            });
        });

        Livewire.on('warehouseReactivated', data => {
            Swal.fire({
                icon: 'success',
                title: 'Reactivation Success',
                text: data.message || 'Warehouse reactivated successfully!',
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('WarehouseDetail livewire:initialized');
            
            @this.on('addWarehouse', () => {
                console.log('addWarehouse');
                setTimeout(() => {
                    // Initialize any add warehouse form functionality here
                }, 100);
            });
        });
    </script>

    <!-- DataTable Scripts for Warehouse Detail Tabs -->
    <script>
        console.log('🚀 WAREHOUSE DETAIL SCRIPT LOADED - ' + new Date().toISOString());
        
        // Immediate check
        setTimeout(function() {
            console.log('🔍 IMMEDIATE CHECK:');
            console.log('  - inventoryTable exists:', document.getElementById('inventoryTable') ? '✅' : '❌');
            console.log('  - Current tab:', $('.tab-pane.active').attr('id') || 'none');
            console.log('  - All tabs:', $('.tab-pane').map(function() { return this.id; }).get());
        }, 500);
        
        (function() {
            console.log('🔧 IIFE Started - Warehouse Detail DataTables');
            var inventoryDT = null;
            var movementsDT = null;

            function initInventoryTable() {
                console.log('🔍 initInventoryTable called');
                
                if (!$.fn.DataTable) { 
                    console.error('❌ DataTables plugin missing'); 
                    return; 
                }
                console.log('✅ DataTables plugin found');
                
                var $table = $('#inventoryTable');
                console.log('🔍 Looking for #inventoryTable, found:', $table.length);
                
                if ($table.length === 0) { 
                    console.warn('⚠️ #inventoryTable not found');
                    return; 
                }

                console.log('📊 Table structure check:');
                console.log('  - thead:', $table.find('thead').length);
                console.log('  - tbody:', $table.find('tbody').length);
                console.log('  - rows:', $table.find('tbody tr').length);

                if ($.fn.DataTable.isDataTable($table)) {
                    console.log('🔄 Destroying existing DataTable');
                    $table.DataTable().destroy();
                }

                console.log('🚀 Initializing DataTable...');
                try {
                    inventoryDT = $table.DataTable({
                        autoWidth: true,
                        colReorder: true,
                        dom: '<"datatable-header"lf><"datatable-scroll"t><"datatable-footer"ip>', // Added 'f' back to show search
                        lengthMenu: [10, 25, 50],
                        searching: true,
                        language: { search: '', lengthMenu: '_MENU_', paginate: { first: 'First', last: 'Last', next: '→', previous: '←' } }
                    });
                    console.log('✅ DataTable initialized successfully:', inventoryDT);
                } catch (error) {
                    console.error('❌ DataTable initialization failed:', error);
                    return;
                }

                // DataTable search is now handled by default search box
                
                console.log('✅ Inventory table setup complete');
            }

            function initMovementsTable() {
                if (!$.fn.DataTable) { console.error('DataTables plugin missing'); return; }
                var $table = $('#movementsTable');
                if ($table.length === 0) { return; }

                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }

                movementsDT = $table.DataTable({
                    autoWidth: true,
                    colReorder: true,
                    dom: '<"datatable-header"lf><"datatable-scroll"t><"datatable-footer"ip>', // Added 'f' back to show search
                    lengthMenu: [10, 25, 50],
                    searching: true,
                    language: { search: '', lengthMenu: '_MENU_', paginate: { first: 'First', last: 'Last', next: '→', previous: '←' } }
                });

                // DataTable search is now handled by default search box

                // Date range filter scoped to movements table
                var filterName = 'warehouseMovementsDateFilter';
                $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) { return fn.name !== filterName; });
                function warehouseMovementsDateFilter(settings, data) {
                    if (settings.nTable.id !== 'movementsTable') { return true; }
                    var from = $('#movementDateFrom').val();
                    var to = $('#movementDateTo').val();
                    var date = new Date(data[1]);
                    if (from && date < new Date(from)) { return false; }
                    if (to && date > new Date(to)) { return false; }
                    return true;
                }
                $.fn.dataTable.ext.search.push(warehouseMovementsDateFilter);

                $('#movementDateFrom, #movementDateTo').off('change.mov').on('change.mov', function(){
                    if (movementsDT) { movementsDT.draw(); }
                });

                window.clearMovementFilters = function() {
                    $('#movementDateFrom').val('');
                    $('#movementDateTo').val('');
                    if (movementsDT) {
                        movementsDT.draw();
                    }
                };
            }

            function initTables() {
                console.log('🔧 initTables called');
                initInventoryTable();
                initMovementsTable();

                // Placeholder text
                $('.dataTables_filter input[type=search]').attr('placeholder', '{{ __("Find") }}');
                // Length select
                $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity, width: 'auto' });
                console.log('🔧 initTables complete');
            }

            // Test function to manually trigger search
            window.testInventorySearch = function(searchTerm) {
                console.log('🧪 Testing inventory search with term:', searchTerm);
                if (inventoryDT) {
                    inventoryDT.search(searchTerm).draw();
                    console.log('✅ Test search applied');
                } else {
                    console.error('❌ inventoryDT not available for testing');
                }
            };

            // Force initialization function for testing
            window.forceInitInventory = function() {
                console.log('🔨 Force initializing inventory table...');
                initInventoryTable();
            };

            // Check dependencies
            console.log('🔍 Checking dependencies...');
            console.log('  - jQuery:', typeof $ !== 'undefined' ? '✅ Available' : '❌ Missing');
            console.log('  - DataTables:', typeof $.fn.DataTable !== 'undefined' ? '✅ Available' : '❌ Missing');
            console.log('  - Current URL:', window.location.pathname);
            console.log('  - #inventoryTable exists:', $('#inventoryTable').length > 0 ? '✅ Found' : '❌ Not found');

            // Initialize after Livewire is fully loaded
            document.addEventListener('livewire:initialized', () => {
                console.log('⚡ Livewire initialized - setting up DataTable events');
                
                // Tab switch events
                $('a[href="#tab-inventory"]').off('shown.bs.tab.inventory').on('shown.bs.tab.inventory', function(){
                    console.log('📋 Inventory tab shown');
                    setTimeout(function() {
                        console.log('⏰ Delayed inventory table init after tab switch');
                        initInventoryTable();
                    }, 200);
                });
                
                $('a[href="#tab-movements"]').off('shown.bs.tab.movements').on('shown.bs.tab.movements', function(){
                    console.log('📋 Movements tab shown');
                    setTimeout(initMovementsTable, 200);
                });

                // Check if we're already on inventory tab after Livewire loads
                setTimeout(() => {
                    if ($('#tab-inventory').hasClass('active')) {
                        console.log('🔍 Already on inventory tab after Livewire init, initializing');
                        initTables();
                    } else {
                        console.log('⏳ Not on inventory tab, waiting for tab switch');
                    }
                }, 300);

                // Listen for warehouse loaded event
                @this.on('warehouseLoaded', (data) => {
                    console.log('🏢 Warehouse loaded:', data);
                    console.log('🔍 Debugging tab state:');
                    console.log('  - #tab-inventory exists:', $('#tab-inventory').length);
                    console.log('  - #tab-inventory has active class:', $('#tab-inventory').hasClass('active'));
                    console.log('  - Active tab:', $('.tab-pane.active').attr('id'));
                    console.log('  - #inventoryTable exists:', $('#inventoryTable').length);
                    console.log('  - #movementsTable exists:', $('#movementsTable').length);
                    
                    setTimeout(() => {
                        // More flexible check - initialize if inventory tab exists and is active OR if we're on detail tab
                        const inventoryTabActive = $('#tab-inventory').hasClass('active');
                        const inventoryTableExists = $('#inventoryTable').length > 0;
                        const movementsTabActive = $('#tab-movements').hasClass('active');
                        const movementsTableExists = $('#movementsTable').length > 0;
                        
                        console.log('📋 After timeout check:');
                        console.log('  - inventoryTabActive:', inventoryTabActive);
                        console.log('  - inventoryTableExists:', inventoryTableExists);
                        console.log('  - movementsTabActive:', movementsTabActive);
                        console.log('  - movementsTableExists:', movementsTableExists);
                        
                        if (inventoryTabActive && inventoryTableExists) {
                            console.log('📋 Warehouse loaded, initializing inventory table');
                            initInventoryTable();
                        } else if (inventoryTableExists) {
                            // Initialize even if not active, for when user switches to tab later
                            console.log('📋 Inventory table exists, pre-initializing for later use');
                            initInventoryTable();
                        }
                        
                        if (movementsTabActive && movementsTableExists) {
                            console.log('📋 Warehouse loaded, initializing movements table');
                            initMovementsTable();
                        } else if (movementsTableExists) {
                            console.log('📋 Movements table exists, pre-initializing for later use');
                            initMovementsTable();
                        }
                    }, 500); // Increased delay to ensure DOM is ready
                });

                // Listen for warehouse updated event
                @this.on('warehouseUpdated', (data) => {
                    console.log('⚡ Warehouse updated:', data);
                    setTimeout(() => {
                        // Check if inventory tab is active and reinitialize if needed
                        if ($('#tab-inventory').hasClass('active') && $('#inventoryTable').length > 0) {
                            console.log('🏢 Warehouse updated, re-initializing inventory table');
                            initInventoryTable();
                        }
                        if ($('#tab-movements').hasClass('active') && $('#movementsTable').length > 0) {
                            console.log('🏢 Warehouse updated, re-initializing movements table');
                            initMovementsTable();
                        }
                    }, 300);
                });
            });

            // Handle URL hash for tab switching
            function handleUrlHash() {
                const hash = window.location.hash;
                if (hash === '#tab-inventory') {
                    console.log('🔍 URL hash detected: switching to inventory tab');
                    // Switch to inventory tab
                    $('a[href="#tab-inventory"]').tab('show');
                    // Initialize inventory table after tab switch
                    setTimeout(function() {
                        initInventoryTable();
                    }, 200);
                } else if (hash === '#tab-movements') {
                    console.log('🔍 URL hash detected: switching to movements tab');
                    // Switch to movements tab
                    $('a[href="#tab-movements"]').tab('show');
                    // Initialize movements table after tab switch
                    setTimeout(function() {
                        initMovementsTable();
                    }, 200);
                }
            }

            // Check for hash on page load
            $(document).ready(function() {
                handleUrlHash();
            });

            // Listen for hash changes
            $(window).on('hashchange', function() {
                handleUrlHash();
            });

        })();
    </script>
@endpush
