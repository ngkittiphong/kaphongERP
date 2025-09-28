<!-- resources/views/livewire/warehouse/warehouse-detail.blade.php -->
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
                            <h4>{{ __t('warehouse.select_warehouse_to_view_details', 'Select a warehouse to view details') }}</h4>
                            <p class="text-muted">{{ __t('warehouse.choose_warehouse_from_list', 'Choose a warehouse from the list to see its information') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Hidden Stock Adjustment Modal (Same as Product Detail) - Outside wire:loading.remove -->
    <div wire:ignore.self class="modal fade" id="stockAdjustmentModal" tabindex="-1" role="dialog" aria-labelledby="stockAdjustmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeStockModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="stockAdjustmentModalLabel">Adjust Stock - {{ $selectedWarehouseName ?? '' }}</h4>
                </div>
                <div class="modal-body" style="padding-top: 10px;">
                    <div class="row" style="margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                        <div class="col-md-3">
                            <div class="text-center">
                                <img src="{{ asset('assets/images/default_product.png') }}" alt="Product Image"
                                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="text-primary" style="margin-top: 0;">{{ $selectedProduct->name ?? 'N/A' }}</h5>
                            <p class="text-muted" style="margin-bottom: 5px;">
                                <strong>SKU:</strong> {{ $selectedProduct->sku_number ?? 'N/A' }}
                            </p>
                            <p class="text-muted" style="margin-bottom: 5px;">
                                <strong>Type:</strong> {{ optional(optional($selectedProduct)->type)->name ?? 'N/A' }}
                            </p>
                            <p class="text-muted" style="margin-bottom: 0;">
                                <strong>Unit:</strong> {{ optional($selectedProduct)->unit_name ?? 'pcs' }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="operationType">Operation Type:</label>
                                <select wire:model.live="operationType" class="form-control" id="operationType">
                                    <option value="">Select Operation</option>
                                    <option value="stock_in">Stock In</option>
                                    <option value="stock_out">Stock Out</option>
                                    <option value="adjustment">Stock Adjustment</option>
                                </select>
                                @error('operationType') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    @if($operationType)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity:</label>
                                    <input type="number" wire:model.defer="quantity" class="form-control" id="quantity"
                                           min="0" step="0.01" placeholder="Enter quantity">
                                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unitName">Unit:</label>
                                    <input type="text" class="form-control" id="unitName"
                                           value="{{ $unitName ?? (optional($selectedProduct)->unit_name ?? 'pcs') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unitPrice">Unit Price:</label>
                                    <input type="number" wire:model.defer="unitPrice" class="form-control" id="unitPrice"
                                           min="0" step="0.01" placeholder="Unit price">
                                    @error('unitPrice') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="salePrice">Sale Price:</label>
                                    <input type="number" wire:model.defer="salePrice" class="form-control" id="salePrice"
                                           min="0" step="0.01" placeholder="Sale price">
                                    @error('salePrice') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="detail">Detail/Reason:</label>
                                    <textarea wire:model.defer="detail" class="form-control" id="detail" rows="3"
                                              placeholder="Enter detail or reason for this operation"></textarea>
                                    @error('detail') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 20px; padding: 15px; background-color: #e8f4fd; border-radius: 5px; border-left: 4px solid #2196F3;">
                            <div class="col-md-6">
                                <h6 class="text-primary" style="margin-top: 0; margin-bottom: 10px;">
                                    <i class="icon-info22"></i> Current Stock Information
                                </h6>
                                <p class="text-muted" style="margin-bottom: 5px;">
                                    <strong>Warehouse:</strong> {{ $selectedWarehouseName ?? 'N/A' }}
                                </p>
                                <p class="text-muted" style="margin-bottom: 5px;">
                                    <strong>Current Remaining:</strong>
                                    <span class="text-primary" style="font-weight: bold; font-size: 16px;">
                                        {{ number_format($currentStock ?? 0) }} {{ $unitName ?? (optional($selectedProduct)->unit_name ?? 'pcs') }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary" style="margin-top: 0; margin-bottom: 10px;">
                                    <i class="icon-calendar"></i> Operation Date
                                </h6>
                                <p class="text-muted" style="margin-bottom: 5px;">
                                    <strong>Date:</strong> {{ now()->format('d/m/Y') }}
                                </p>
                                <p class="text-muted" style="margin-bottom: 0;">
                                    <strong>Time:</strong> {{ now()->format('H:i:s') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" wire:click="closeStockModal">Cancel</button>
                    @if($operationType)
                        <button type="button" class="btn btn-primary"
                                wire:click="processProductStockOperation"
                                wire:loading.attr="disabled"
                                wire:target="processProductStockOperation">
                            <span wire:loading.remove wire:target="processProductStockOperation">
                                <i class="icon-checkmark"></i> Process {{ ucfirst(str_replace('_', ' ', $operationType)) }}
                            </span>
                            <span wire:loading wire:target="processProductStockOperation">
                                <i class="icon-spinner2 spinner"></i> Processing...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
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
            console.log('  - Current tab:', $('.panel-flat .tab-pane.active').attr('id') || 'none');
            console.log('  - All tabs:', $('.panel-flat .tab-pane').map(function() { return this.id; }).get());
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
                $('.dataTables_filter input[type=search]').attr('placeholder', 'Search...');
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
                    console.log('  - Active tab:', $('.panel-flat .tab-pane.active').attr('id'));
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

            // Set up event listeners after Livewire is initialized (like product detail)
            document.addEventListener('livewire:initialized', () => {
                console.log('🚀 [JS] Livewire initialized, setting up event listeners');
                
                // Tab switching events
                @this.on('tabSwitched', (data) => {
                    console.log('🚀 [JS] Tab switched to:', data.tab);
                    // Update Bootstrap tab display - only target warehouse detail tabs, not sidebar tabs
                    $('.panel-flat .nav-tabs li').removeClass('active');
                    $('.panel-flat .tab-pane').removeClass('active');
                    
                    $(`a[href="#tab-${data.tab}"]`).parent().addClass('active');
                    $(`#tab-${data.tab}`).addClass('active');
                    
                    // Initialize DataTable if switching to inventory or movements
                    if (data.tab === 'inventory') {
                        setTimeout(() => {
                            initInventoryTable();
                        }, 200);
                    } else if (data.tab === 'movements') {
                        setTimeout(() => {
                            initMovementsTable();
                        }, 200);
                    }
                });
                
                // Stock modal events
                @this.on('showStockModal', () => {
                    console.log('🚀 [JS] showStockModal event received!');
                    console.log('🚀 [JS] Modal element exists:', $('#stockAdjustmentModal').length > 0);
                    console.log('🚀 [JS] Modal element:', $('#stockAdjustmentModal')[0]);
                    console.log('🚀 [JS] About to show modal...');
                    // Add a small delay to ensure Livewire state is updated
                    setTimeout(() => {
                        console.log('🚀 [JS] Executing modal show after timeout');
                        $('#stockAdjustmentModal').modal('show');
                        console.log('🚀 [JS] Modal show command executed');
                        console.log('🚀 [JS] Modal is visible:', $('#stockAdjustmentModal').is(':visible'));
                    }, 100);
                });

                @this.on('hideStockModal', () => {
                    console.log('🚀 [JS] hideStockModal event received');
                    $('#stockAdjustmentModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');
                });
            

                @this.on('confirmStockOperation', (data) => {
                    console.log('🚀 [JS] confirmStockOperation event received:', data);

                    $('#stockAdjustmentModal').modal('hide');
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open');

                    if (typeof Swal === 'undefined') {
                        console.warn('⚠️ [JS] SweetAlert not available, using fallback confirmation.');
                        const confirmed = window.confirm(`Confirm stock operation?\nCurrent: ${data.currentStock}\nNew: ${data.newStock}`);
                        if (confirmed) {
                            @this.call('processProductStockOperation', true);
                        } else {
                            setTimeout(() => {
                                $('#stockAdjustmentModal').modal('show');
                            }, 200);
                        }
                        return;
                    }

                    const operationLabel = data.operationType ? data.operationType.replace('_', ' ') : 'stock operation';

                    Swal.fire({
                        title: 'Confirm Stock Operation',
                        html: `
                            <div style="text-align: left;">
                                <div style="display: flex; align-items: center; margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
                                    <div style="margin-right: 15px;">
                                        <img src="${data.productImage || '{{ asset('assets/images/default_product.png') }}'}"
                                             alt="Product Image"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                    </div>
                                    <div>
                                        <h6 style="margin: 0 0 5px 0; color: #007bff; font-weight: bold;">${data.productName || 'N/A'}</h6>
                                        <p style="margin: 0; color: #6c757d; font-size: 14px;"><strong>SKU:</strong> ${data.productSku || 'N/A'}</p>
                                        <p style="margin: 0; color: #6c757d; font-size: 14px;"><strong>Warehouse:</strong> ${data.warehouseName || 'N/A'}</p>
                                    </div>
                                </div>

                                <div style="margin-bottom: 15px;">
                                    <h6 style="color: #495057; margin-bottom: 10px; font-weight: bold;">
                                        <i class="icon-cog" style="margin-right: 5px;"></i>Operation Details
                                    </h6>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                        <div>
                                            <p style="margin: 5px 0;"><strong>Operation:</strong> <span style="color: #dc3545; font-weight: bold;">${operationLabel.toUpperCase()}</span></p>
                                            <p style="margin: 5px 0;"><strong>Current Stock:</strong> <span style="color: #007bff; font-weight: bold;">${data.currentStock ?? 0} ${data.unitName || 'pcs'}</span></p>
                                            <p style="margin: 5px 0;"><strong>New Stock:</strong> <span style="color: #28a745; font-weight: bold;">${data.newStock ?? 0} ${data.unitName || 'pcs'}</span></p>
                                        </div>
                                        <div>
                                            <p style="margin: 5px 0;"><strong>Document No:</strong> <span style="color: #6c757d;">${data.documentNumber || 'N/A'}</span></p>
                                            <p style="margin: 5px 0;"><strong>Date:</strong> <span style="color: #6c757d;">${data.operationDate || 'N/A'}</span></p>
                                            <p style="margin: 5px 0;"><strong>Time:</strong> <span style="color: #6c757d;">${data.operationTime || 'N/A'}</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div style="background-color: #e8f4fd; padding: 10px; border-radius: 6px; text-align: center;">
                                    <p style="margin: 0; font-weight: bold; color: #495057;">Quantity Change:
                                        <span style="color: ${(data.newStock - data.currentStock) >= 0 ? '#28a745' : '#dc3545'};">
                                            ${(data.newStock - data.currentStock) >= 0 ? '+' : ''}${(data.newStock - data.currentStock)}
                                            ${data.unitName || 'pcs'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm Operation',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#007bff',
                        cancelButtonColor: '#6c757d',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        width: '600px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('🚀 [JS] User confirmed operation');
                            @this.call('processProductStockOperation', true);
                        } else {
                            console.log('🚀 [JS] User cancelled operation, reopening modal');
                            setTimeout(() => {
                                $('#stockAdjustmentModal').modal('show');
                            }, 200);
                        }
                    });
                });

                @this.on('showSuccessMessage', (data) => {
                    console.log('🚀 [JS] Success message received:', data);
                    if (typeof Swal !== 'undefined') {
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message || 'Operation completed successfully!',
                                confirmButtonText: 'OK'
                            });
                        }, 300);
                    } else {
                        alert('Success: ' + (data.message || 'Operation completed successfully!'));
                    }
                });

                @this.on('showErrorMessage', (data) => {
                    console.log('🚀 [JS] Error message received:', data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        setTimeout(() => {
                            $('#stockAdjustmentModal').modal('show');
                        }, 200);
                    });
                });
            });

        })();

        // Test functions (outside IIFE so they're globally accessible)
        window.testShowModal = function() {
            console.log('🧪 Testing modal display...');
            console.log('Modal element exists:', $('#stockAdjustmentModal').length > 0);
            if ($('#stockAdjustmentModal').length > 0) {
                $('#stockAdjustmentModal').modal('show');
                console.log('✅ Modal should be visible now');
            } else {
                console.error('❌ Modal element not found');
            }
        };

        window.testTriggerEvent = function() {
            console.log('🧪 Testing event trigger...');
            @this.dispatch('showStockModal');
        };


        window.testEventListeners = function() {
            console.log('🧪 Testing event listeners...');
            console.log('Livewire component:', @this);
            console.log('Event listeners registered:', typeof @this.on === 'function');
        };
    </script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

