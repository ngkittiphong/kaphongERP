<!-- resources/views/livewire/user-profile.blade.php -->
<!-----------------------------  Start Product Detail    -------------------------->

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
        @if($showAddEditProductForm)
            @include('livewire.product.product-add-product')
        @elseif($product)
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row p-l-10 p-r-10 panel panel-flat">
                            <div class="panel-heading">
                                <!-- Back Button -->
                                @if(request()->has('return_to') && request()->get('return_to') === 'warehouse')
                                    <div class="elements">
                                        <button class="btn btn-default" onclick="goBackToWarehouse()">
                                            <i class="icon-arrow-left8"></i> {{ __t('product.back_to_warehouse_inventory', 'Back to Warehouse Inventory') }}
                                        </button>
                                    </div>
                                    <a class="elements-toggle"><i class="icon-more"></i></a>
                                @endif
                                
                                <div class="tabbable">
                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                        <li class="active">
                                            <a href="#tab-detail" data-toggle="tab" class="panel-title" aria-expanded="true">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ __t('product.detail', 'Detail') }}</h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-stock-card" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ __t('product.stock_card', 'Stock Card') }}</h3>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab-trading" data-toggle="tab" aria-expanded="false">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">{{ __t('product.trading_detail', 'Trading Detail') }} </h3>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                @include('livewire.product.product-detail_detail-tab')
                                <div class="tab-pane" id="tab-stock-card">
                                    @if($product)
                                        @livewire('product.product-stock-card', ['productId' => $product->id], key('stock-card-' . $product->id))
                                    @else
                                        <div class="panel panel-flat">
                                            <div class="panel-body text-center">
                                                <div class="alert alert-info">
                                                    <i class="icon-info22"></i> {{ __t('product.select_product_to_view_stock_card', 'Please select a product to view stock card details.') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @include('livewire.product.product-detail_trading-tab')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded p-4">
                <p class="text-muted">{{ __t('product.select_product_to_view_details', 'Select a product to view details') }}</p>
            </div>
        @endif
    </div>
</div>



<!------------------------------------  End Product Detail ------------------------->


@push('styles')
    <!-- Include Slim CSS -->
    <link rel="stylesheet" href="{{ asset('slim/css/slim.min.css') }}">
@endpush

@push('scripts')
    <script>
        // Product DataTable Initialization Function
        let isInitializingDataTable = false;
        
        function initProductDataTable() {
            // Prevent multiple simultaneous initializations
            if (isInitializingDataTable) {
                console.log('DataTable initialization already in progress, skipping...');
                return;
            }
            
            isInitializingDataTable = true;
            
            console.log('Initializing Product DataTable...');
            console.log('jQuery version:', $.fn.jquery);
            console.log('DataTable available:', typeof $.fn.DataTable);
            console.log('Table elements found:', $('.datatable-reorder-state-saving').length);
            
            // Target only the table in the product list sidebar
            const $productTable = $('.secondary-sidebar .datatable-reorder-state-saving').first();
            
            if ($productTable.length === 0) {
                console.warn('Product table not found in sidebar, skipping initialization');
                isInitializingDataTable = false;
                return;
            }
            
            console.log('Found product table, proceeding with initialization...');
            
            // Clean up any existing DataTable instances and DOM elements first
            if ($.fn.DataTable.isDataTable($productTable)) {
                console.log('Destroying existing DataTable before reinitializing...');
                try {
                    $productTable.DataTable().destroy(true);
                } catch (e) {
                    console.warn('Error destroying DataTable:', e);
                }
            }
            
            // Clean up any DataTable-generated DOM elements in the sidebar
            $('.secondary-sidebar .dataTables_wrapper').each(function() {
                $(this).children().unwrap();
            });
            $('.secondary-sidebar .dataTables_filter').remove();
            $('.secondary-sidebar .dataTables_length').remove();
            $('.secondary-sidebar .dataTables_info').remove();
            $('.secondary-sidebar .dataTables_paginate').remove();
            
            try {
                console.log('Initializing DataTable directly...');
                
                // Initialize DataTable with proper configuration
                $productTable.DataTable({
                    autoWidth: true,
                    columnDefs: [{
                        //			orderable: false,
                        //			targets: [ 25 ]
                    }],
                    colReorder: true,
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    lengthMenu: [10, 25, 50],
                    language: {
                        search: '_INPUT_',
                        lengthMenu: '_MENU_',
                        paginate: {
                            'first': '{{ __t("common.first", "First") }}',
                            'last': '{{ __t("common.last", "Last") }}',
                            'next': '&rarr;',
                            'previous': '&larr;'
                        }
                    },
                    stateSave: true,
                    fixedColumns: true,
                    scrollResize: true,
                    scrollX: true,
                    scrollCollapse: true,
                    pageLength: 10,
                    order: [[ 0, 'asc' ]]
                });

                // Add placeholder to the datatable filter option in the sidebar
                $('.secondary-sidebar .dataTables_filter input[type=search]').attr('placeholder', '{{ __t("common.find", "Find") }}');

                // Enable Select2 select for the length option in the sidebar
                $('.secondary-sidebar .dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    width: 'auto'
                });

                // Handle row clicks
                $(".lease-order-row").off('click.datatable').on("click.datatable", function() {
                    $(".lease-order-row").removeClass('active');
                    $(this).addClass('active');
                });

                console.log('Product DataTable initialized successfully');
            } catch (error) {
                console.error('Error initializing Product DataTable:', error);
            } finally {
                isInitializingDataTable = false;
            }
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>



    <script>
        function initSlim() {
            // Check if DataTable is already initialized and destroy it if exists
            // This prevents duplicate initialization errors
            console.log('slim script reload');
            // Remove old script if exists
            const oldScript = document.getElementById('slim-script');
            if (oldScript) {
                oldScript.remove();
            }

            // Create and append new script using Alpine
            const script = document.createElement('script');
            script.id = 'slim-script';
            script.src = "{{ asset('slim/js/slim.kickstart.min.js') }}";
            document.body.appendChild(script);
        }


        function initTypeahead() {
            console.log('initTypeahead');

            if (typeof $.fn.typeahead !== 'function') {
                console.warn('Typeahead plugin not found!');
                return;
            }

            const list = @json($productGroups->pluck('name'));
            console.log(list);

            $('.typeahead')
            // remove any old instance/data
            // .each(function() {
            //     const $input = $("#product_group_name");
            //     $input.data('typeahead', null);
            // })
            // re-init

            $("#product_group_name").typeahead({
                    source: list,
                    minLength: 1,
                    autoSelect: true,
                    items: list.length,
                    
                    afterSelect(item) {
                        console.log('Selected:', item);
                        // Update the Livewire model
                        @this.set('product_group_name', item);
                    }
                });

                $("#product_group_name").on('focus keyup', function(e) {
                    if ($("#product_group_name").val().length === 0) {
                        // direct lookup on the underlying instance
                        $("#product_group_name").data('typeahead').lookup();
                    }
                });
            
        }

        function setAvatarFromSlim() {
            const slim = document.getElementById('slim-image');
            if (slim) {
                const resultImage = document.querySelector('#slim-image .slim-result img.in');
                const base64Data = resultImage.src;
                @this.set('product_cover_img', base64Data);
            }
        }

        function handleSlimSubmitForm(event) {
            event.preventDefault(); // Prevent the default form submission
            console.log('handleSlimSubmitForm');
            setAvatarFromSlim();
        }

        initSlim();

        document.addEventListener('livewire:initialized', () => {
            console.log('livewire:initialized');
            
            // Initialize Product DataTable after Livewire is ready
            setTimeout(() => {
                console.log('Initializing DataTable after Livewire initialization...');
                initProductDataTable();
            }, 1000);
            
            @this.on('productSelected', () => {
                console.log('productSelected');
                setTimeout(() => {
                    initSlim();
                    initTypeahead();
                    $('.venobox').venobox();
                    document.getElementById('updateUserProfileForm').addEventListener('submit',
                        handleSlimSubmitForm);
                }, 100);
            });

            @this.on('addProduct', () => {
                console.log('addProduct');
                setTimeout(() => {
                    initSlim();
                    initTypeahead();
                    document.getElementById('addProductForm').addEventListener('submit',
                        handleSlimSubmitForm);
                }, 100);
            });

            // Stock modal events
            @this.on('showStockModal', () => {
                console.log('🚀 [JS] showStockModal event received');
                // Add a small delay to ensure Livewire state is updated
                setTimeout(() => {
                    $('#stockAdjustmentModal').modal('show');
                }, 100);
            });

            @this.on('hideStockModal', () => {
                console.log('🚀 [JS] hideStockModal event received');
                $('#stockAdjustmentModal').modal('hide');
                // Remove any remaining modal backdrop
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

            // Handle modal close events - DISABLED FOR TESTING
            // $('#stockAdjustmentModal').on('hidden.bs.modal', function () {
            //     @this.call('closeStockModal');
            // });

            @this.on('confirmStockOperation', (data) => {
                console.log('🚀 [JS] confirmStockOperation event received:', data);

                // Temporarily hide the modal so the confirmation dialog is visible
                $('#stockAdjustmentModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');

                if (typeof Swal === 'undefined') {
                    console.warn('⚠️ [JS] SweetAlert not available, using fallback confirmation.');
                    const confirmed = window.confirm(`Confirm stock operation?\nCurrent: ${data.currentStock}\nNew: ${data.newStock}`);
                    if (confirmed) {
                        @this.call('processStockOperation', true);
                    } else {
                        setTimeout(() => {
                            $('#stockAdjustmentModal').modal('show');
                        }, 200);
                    }
                    return;
                }

                const operationLabel = data.operationType ? data.operationType.replace('_', ' ') : 'stock operation';

                Swal.fire({
                    title: '{{ __t('product.confirm_stock_operation', 'Confirm Stock Operation') }}',
                    html: `
                        <div style="text-align: left;">
                            <!-- Product Information -->
                            <div style="display: flex; align-items: center; margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff;">
                                <div style="margin-right: 15px;">
                                    <img src="${data.productImage || '{{ asset('assets/images/default_product.png') }}'}" 
                                         alt="{{ __t('product.product_image', 'Product Image') }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                </div>
                                <div>
                                    <h6 style="margin: 0 0 5px 0; color: #007bff; font-weight: bold;">${data.productName || 'N/A'}</h6>
                                    <p style="margin: 0; color: #6c757d; font-size: 14px;"><strong>{{ __t('product.sku', 'SKU') }}:</strong> ${data.productSku || 'N/A'}</p>
                                    <p style="margin: 0; color: #6c757d; font-size: 14px;"><strong>{{ __t('warehouse.warehouse', 'Warehouse') }}:</strong> ${data.warehouseName || 'N/A'}</p>
                                </div>
                            </div>
                            
                            <!-- Operation Details -->
                            <div style="margin-bottom: 15px;">
                                <h6 style="color: #495057; margin-bottom: 10px; font-weight: bold;">
                                    <i class="icon-cog" style="margin-right: 5px;"></i>Operation Details
                                </h6>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <div>
                                        <p style="margin: 5px 0;"><strong>{{ __t('product.operation', 'Operation') }}:</strong> <span style="color: #dc3545; font-weight: bold;">${operationLabel.toUpperCase()}</span></p>
                                        <p style="margin: 5px 0;"><strong>{{ __t('product.current_stock', 'Current Stock') }}:</strong> <span style="color: #007bff; font-weight: bold;">${data.currentStock ?? 0} ${data.unitName || 'pcs'}</span></p>
                                        <p style="margin: 5px 0;"><strong>{{ __t('product.new_stock', 'New Stock') }}:</strong> <span style="color: #28a745; font-weight: bold;">${data.newStock ?? 0} ${data.unitName || 'pcs'}</span></p>
                                    </div>
                                    <div>
                                        <p style="margin: 5px 0;"><strong>{{ __t('product.document_number', 'Document No') }}:</strong> <span style="color: #6c757d;">${data.documentNumber || 'N/A'}</span></p>
                                        <p style="margin: 5px 0;"><strong>{{ __t('product.date', 'Date') }}:</strong> <span style="color: #6c757d;">${data.operationDate || 'N/A'}</span></p>
                                        <p style="margin: 5px 0;"><strong>{{ __t('product.time', 'Time') }}:</strong> <span style="color: #6c757d;">${data.operationTime || 'N/A'}</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quantity Change Summary -->
                            <div style="background-color: #e8f4fd; padding: 10px; border-radius: 6px; text-align: center; margin-bottom: 15px;">
                                <p style="margin: 0; font-weight: bold; color: #495057;">
                                    Quantity Change: 
                                    <span style="color: ${(data.newStock - data.currentStock) >= 0 ? '#28a745' : '#dc3545'};">
                                        ${(data.newStock - data.currentStock) >= 0 ? '+' : ''}${(data.newStock - data.currentStock)} ${data.unitName || 'pcs'}
                                    </span>
                                </p>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '{{ __t('product.confirm_operation', 'Confirm Operation') }}',
                    cancelButtonText: '{{ __t('common.cancel', 'Cancel') }}',
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    width: '600px',
                    didOpen: () => {
                        console.log('🚀 [JS] SweetAlert opened', Swal.getPopup());
                    }
                }).then((result) => {
                    console.log('🚀 [JS] Confirmation result:', result);
                    if (result.isConfirmed) {
                        console.log('🚀 [JS] User confirmed, calling processStockOperation with confirm=true');
                        @this.call('processStockOperation', true);
                    } else {
                        console.log('🚀 [JS] User cancelled, showing modal again');
                        setTimeout(() => {
                            $('#stockAdjustmentModal').modal('show');
                        }, 200);
                    }
                });
            });

            @this.on('refreshComponent', () => {
                console.log('🚀 [JS] refreshComponent event received');
                
                // Reset the initialization flag
                isInitializingDataTable = false;
                
                // Completely destroy the DataTable and clean up DOM
                const $productTable = $('.secondary-sidebar .datatable-reorder-state-saving').first();
                if ($productTable.length > 0 && $.fn.DataTable.isDataTable($productTable)) {
                    console.log('🧹 Destroying DataTable completely for refresh...');
                    $productTable.DataTable().destroy(true);
                }
                
                // Remove all DataTable-generated elements
                $('.secondary-sidebar .dataTables_wrapper').remove();
                $('.secondary-sidebar .dataTables_filter').remove();
                $('.secondary-sidebar .dataTables_length').remove();
                $('.secondary-sidebar .dataTables_info').remove();
                $('.secondary-sidebar .dataTables_paginate').remove();
                $('.secondary-sidebar .dataTables_scrollHead').remove();
                $('.secondary-sidebar .dataTables_scrollBody').remove();
                $('.secondary-sidebar .dataTables_scrollFoot').remove();
                
                // Dispatch refresh to ProductList component
                Livewire.dispatch('refreshProductList');
                
                // Wait for Livewire to refresh and then reinitialize DataTable
                setTimeout(() => {
                    console.log('🔄 Reinitializing DataTable after component refresh...');
                    initProductDataTable();
                }, 800);
            });

            @this.on('showSuccessMessage', (data) => {
                // Handle array data structure - Livewire sometimes wraps data in arrays
                const eventData = Array.isArray(data) ? data[0] : data;
                
                // Small delay to ensure modal is fully closed
                setTimeout(() => {
                    const swalConfig = {
                        icon: 'success',
                        title: '{{ __t('common.success', 'Success') }}',
                        text: eventData.message,
                        confirmButtonText: '{{ __t('common.ok', 'OK') }}'
                    };
                    
                    // If there's a redirect URL, make it mandatory to click OK
                    if (eventData.redirectUrl) {
                        swalConfig.allowOutsideClick = false;
                        swalConfig.allowEscapeKey = false;
                    }
                    
                    Swal.fire(swalConfig).then((result) => {
                        if (result.isConfirmed && eventData.redirectUrl) {
                            // Add a small delay to ensure database transaction is committed
                            // setTimeout(() => {
                            //     window.location.replace(eventData.redirectUrl);
                            // }, 1000); // 1 second delay to ensure DB commit
                        }
                    }).catch((error) => {
                        console.error('SweetAlert error:', error);
                    });
                }, 300);
            });

            @this.on('showErrorMessage', (message) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message.message,
                    confirmButtonText: 'OK'
                });
            });
        });

        // Back navigation function
        function goBackToWarehouse() {
            const urlParams = new URLSearchParams(window.location.search);
            const returnTo = urlParams.get('return_to');
            const warehouseId = urlParams.get('warehouse_id');
            
            if (returnTo === 'warehouse' && warehouseId) {
                // Navigate back to warehouse page with the specific warehouse selected and inventory tab active
                window.location.href = '{{ route("menu.menu_warehouse") }}?warehouse_id=' + warehouseId + '#tab-inventory';
            } else {
                // Fallback to browser back
                window.history.back();
            }
        }

        // Product delete confirmation function
        function confirmDelete(productId) {
            Swal.fire({
                title: "{{ __t('common.are_you_sure', 'Are you sure?') }}",
                text: "{{ __t('product.change_status_to_inactive', 'This will change the product status to inactive. The product will no longer be available for new transactions.') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ __t('product.change_to_inactive', 'Yes, change to inactive!') }}",
                cancelButtonText: "{{ __t('common.cancel', 'Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteProduct');
                }
            });
        }
    </script>
@endpush

