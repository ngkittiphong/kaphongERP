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
            @if($product)
                @include('livewire.product.product-edit-product')
            @else
                @include('livewire.product.product-add-product')
            @endif
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
        .datatable-stock-card {
            font-size: 0.875rem;
        }
        
        .datatable-stock-card th {
            background-color: var(--bs-gray-100);
            font-weight: 600;
            border-bottom: 2px solid var(--bs-gray-300);
        }
        
        .datatable-stock-card td {
            vertical-align: middle;
        }
        
        /* Summary row styling using Bootstrap utilities */
        .datatable-stock-card tr.bg-light td {
            font-weight: 600;
            background-color: var(--bs-gray-200) !important;
        }
        
        .datatable-stock-card tr.bg-info td {
            font-weight: 600;
            background-color: var(--bs-info-bg-subtle) !important;
            color: var(--bs-info-text-emphasis);
        }
    </style>
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
                const searchInput = $('.secondary-sidebar .dataTables_filter input[type=search]');
                if (searchInput.length > 0) {
                    searchInput.attr('placeholder', '{{ __t("common.find", "Find") }}');
                }

                // Enable Select2 select for the length option in the sidebar
                const lengthSelect = $('.secondary-sidebar .dataTables_length select');
                if (lengthSelect.length > 0) {
                    lengthSelect.select2({
                        minimumResultsForSearch: Infinity,
                        width: 'auto'
                    });
                }

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
    
    <!-- DataTable Export Libraries -->
    <script src="{{ asset('js/forms/picker.js') }}"></script>
    <script src="{{ asset('js/forms/picker.date.js') }}"></script>
    <script src="{{ asset('js/forms/picker.time.js') }}"></script>
    <script src="{{ asset('js/forms/spectrum.js') }}"></script>
    <script src="{{ asset('js/pages/pickers.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>



    <script>

        function initSlim() {
            console.log('slim script reload');
            const oldScript = document.getElementById('slim-script');
            if (oldScript) {
                oldScript.remove();
            }

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

            const productGroupList = @json($productGroups->pluck('name'));
            console.log('Product Group List:', productGroupList);

            $("#product_group_name").typeahead({
                source: productGroupList,
                minLength: 1,
                autoSelect: true,
                items: productGroupList.length,
                afterSelect(item) {
                    console.log('Selected Product Group:', item);
                    $("#product_group_name").val(item);
                    @this.product_group_name = item;
                    console.log('Updated Livewire property directly:', item);
                }
            });

            $("#product_group_name").on('focus keyup', function() {
                if ($("#product_group_name").val().length === 0) {
                    $("#product_group_name").data('typeahead').lookup();
                }
            });

            const unitNameList = @json($unitNames);
            console.log('Unit Name List:', unitNameList);

            $("#unit_name").typeahead({
                source: unitNameList,
                minLength: 1,
                autoSelect: true,
                items: unitNameList.length,
                afterSelect(item) {
                    console.log('Selected Unit Name:', item);
                    $("#unit_name").val(item);
                    @this.unit_name = item;
                    console.log('Updated Livewire unit_name property directly:', item);
                }
            });

            $("#unit_name").on('focus keyup', function() {
                if ($("#unit_name").val().length === 0) {
                    $("#unit_name").data('typeahead').lookup();
                }
            });
        }

        function normalizeImageSource(source) {
            if (!source) {
                return null;
            }

            const hasCanvasSupport = typeof HTMLCanvasElement !== 'undefined';
            const hasImageSupport = typeof HTMLImageElement !== 'undefined';

            if (hasCanvasSupport && source instanceof HTMLCanvasElement) {
                try {
                    return source.toDataURL('image/png');
                } catch (error) {
                    console.error('Failed to convert Slim canvas to data URL:', error);
                    return null;
                }
            }

            if (hasImageSupport && source instanceof HTMLImageElement) {
                return source.src || null;
            }

            if (typeof source === 'string' && source.trim() !== '') {
                return source;
            }

            if (hasCanvasSupport && typeof source?.toDataURL === 'function') {
                try {
                    return source.toDataURL('image/png');
                } catch (error) {
                    console.error('Failed to convert canvas-like object to data URL:', error);
                }
            }

            if (typeof source?.src === 'string' && source.src.trim() !== '') {
                return source.src;
            }

            return null;
        }

        function extractImage(data) {
            if (!data || typeof data !== 'object') {
                return null;
            }

            const sections = [data.output, data.input, data];

            for (const section of sections) {
                if (!section || typeof section !== 'object') {
                    continue;
                }

                const candidates = [
                    section.image,
                    section.canvas,
                    section.data,
                    section.dataUrl,
                    section.src,
                    section.file
                ];

                for (const candidate of candidates) {
                    const normalized = normalizeImageSource(candidate);
                    if (normalized) {
                        return normalized;
                    }
                }
            }

            return null;
        }

        function collectSlimDatasets(controller) {
            const datasets = [];

            if (!controller) {
                return datasets;
            }

            const possibleCollections = [controller.data, controller._data];

            for (const collection of possibleCollections) {
                if (!collection) {
                    continue;
                }

                if (Array.isArray(collection)) {
                    datasets.push(...collection);
                } else {
                    datasets.push(collection);
                }
            }

            return datasets;
        }

        function getSlimResultImage(slimElement) {
            if (!slimElement) {
                return null;
            }

            const possibleDatasets = [];

            if (slimElement.slim) {
                possibleDatasets.push(...collectSlimDatasets(slimElement.slim));
            }

            if (typeof Slim !== 'undefined' && typeof Slim.getImages === 'function') {
                try {
                    const images = Slim.getImages(slimElement);
                    if (images) {
                        if (Array.isArray(images)) {
                            possibleDatasets.push(...images);
                        } else {
                            possibleDatasets.push(images);
                        }
                    }
                } catch (error) {
                    console.error('Error calling Slim.getImages:', error);
                }
            }

            for (const dataset of possibleDatasets) {
                const extracted = extractImage(dataset);
                if (extracted) {
                    return extracted;
                }
            }

            const resultImage = slimElement.querySelector('.slim-result img.in');
            if (resultImage && resultImage.src) {
                return resultImage.src;
            }

            const fallbackImage = slimElement.querySelector('.slim-area img');
            if (fallbackImage && fallbackImage.src) {
                return fallbackImage.src;
            }

            return null;
        }

        function setAvatarFromSlim() {
            const slim = document.getElementById('slim-image');
            if (!slim) {
                return;
            }

            // const base64Data = getSlimResultImage(slim);
            // console.log('Slim product image data:', base64Data);
            const resultImage = document.querySelector('#slim-image .slim-result img.in');
            const base64Data = resultImage.src;

            if (base64Data) {
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
                    scheduleStockCardInit('productSelected event', 400);
                    $('.venobox').venobox();
                    
                    // Safely add event listener only if element exists
                    const updateForm = document.getElementById('updateUserProfileForm');
                    if (updateForm) {
                        updateForm.addEventListener('submit', handleSlimSubmitForm);
                    }
                }, 100);
            });

            @this.on('addProduct', () => {
                console.log('addProduct');
                setTimeout(() => {
                    initSlim();
                    initTypeahead();
                    
                    // Safely add event listener only if element exists
                    const addForm = document.getElementById('addProductForm');
                    if (addForm) {
                        addForm.addEventListener('submit', handleSlimSubmitForm);
                    }
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

            @this.on('refreshDataTable', () => {
                console.log('🚀 [JS] refreshDataTable event received');
                
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
                text: "{{ __t('product.delete_product_confirmation', 'This will delete the product. The product will no longer be available for new transactions.') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ __t('product.delete_product', 'Yes, delete!') }}",
                cancelButtonText: "{{ __t('common.cancel', 'Cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteProduct');
                }
            });
        }



        // Helper functions for export customization
        function getProductName() {
            var productName = 'Unknown Product';
            // Try to get product name from various sources
            if (typeof window.livewire !== 'undefined' && window.livewire.find('product-stock-card')) {
                var component = window.livewire.find('product-stock-card');
                if (component && component.get('product') && component.get('product').name_en) {
                    productName = component.get('product').name_en;
                }
            }
            // Fallback: try to get from page title or other elements
            var titleElement = document.querySelector('.panel-title');
            if (titleElement && titleElement.textContent.includes('Stock Card')) {
                // Extract product name from context if available
                var productElement = document.querySelector('[data-product-name]');
                if (productElement) {
                    productName = productElement.getAttribute('data-product-name');
                }
            }
            return productName.replace(/[^a-zA-Z0-9]/g, '_');
        }

        function getDateRange() {
            var startDate = 'Unknown';
            var endDate = 'Unknown';
            
            // Try to get dates from Livewire component
            if (typeof window.livewire !== 'undefined' && window.livewire.find('product-stock-card')) {
                var component = window.livewire.find('product-stock-card');
                if (component) {
                    startDate = component.get('startDate') || 'Unknown';
                    endDate = component.get('endDate') || 'Unknown';
                }
            }
            
            // Fallback: try to get from form inputs
            var startInput = document.querySelector('input[wire\\:model\\.live="startDate"]');
            var endInput = document.querySelector('input[wire\\:model\\.live="endDate"]');
            
            if (startInput && startInput.value) {
                startDate = startInput.value;
            }
            if (endInput && endInput.value) {
                endDate = endInput.value;
            }
            
            return startDate + '_to_' + endDate;
        }

        function getCurrentDateTimeFormatted() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            return `${year}${month}${day}_${hours}${minutes}${seconds}`;
        }

        function getBranchName() {
            var branchName = 'Unknown_Branch';
            
            // Try to get branch name from Livewire component
            if (typeof window.livewire !== 'undefined' && window.livewire.find('product-stock-card')) {
                var component = window.livewire.find('product-stock-card');
                if (component) {
                    var selectedBranchId = component.get('selectedBranchId');
                    if (selectedBranchId) {
                        // Extract branch name from selectedBranchId
                        if (selectedBranchId.startsWith('branch_')) {
                            branchName = 'Branch_' + selectedBranchId.replace('branch_', '');
                        } else if (selectedBranchId.startsWith('warehouse_')) {
                            branchName = 'Warehouse_' + selectedBranchId.replace('warehouse_', '');
                        } else {
                            branchName = 'All_Branches';
                        }
                    }
                }
            }
            
            // Fallback: try to get from dropdown
            var branchSelect = document.querySelector('select[wire\\:model\\.live="selectedBranchId"]');
            if (branchSelect && branchSelect.value) {
                var selectedText = branchSelect.options[branchSelect.selectedIndex].text;
                if (selectedText.includes('All Branches')) {
                    branchName = 'All_Branches';
                } else {
                    branchName = selectedText.replace(/[^a-zA-Z0-9]/g, '_');
                }
            }
            
            return branchName.replace(/[^a-zA-Z0-9_]/g, '_');
        }

        function destroyStockCardDataTable() {
            if (typeof $.fn.DataTable === 'undefined') {
                return;
            }

            var $table = $('.datatable-stock-card');
            
            if ($table.length === 0) {
                return;
            }

            // Check if DataTable is already initialized
            if ($.fn.DataTable.isDataTable($table)) {
                try {
                    console.log('Destroying existing DataTable...');
                    // Use default destroy to keep the original table so Livewire can patch it
                    $table.DataTable().destroy();
                } catch (error) {
                    console.log('Error destroying DataTable:', error);
                }
            } else {
                console.log('Destroy skipped: stock card table is not an initialized DataTable.');
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
        var isStockCardDataTableInitializing = false;

        // Initialize Stock Card DataTable with export functionality
        function initStockCardDataTable() {
            console.log('Initializing Stock Card DataTable...');

            // Prevent multiple simultaneous initializations
            if (isStockCardDataTableInitializing) {
                console.log('DataTable initialization already in progress, skipping...');
                return;
            }

            isStockCardDataTableInitializing = true;

            destroyStockCardDataTable();

            var $table = $('.datatable-stock-card');

            if ($table.length === 0) {
                console.log('No stock card table available to initialize. Skipping DataTable setup.');
                isStockCardDataTableInitializing = false;
                return;
            }

            console.log('Found stock card table, initializing with export buttons...');

            var hasDataAttr = $table.data('has-data');
            var hasData = hasDataAttr === 1 || hasDataAttr === '1' || hasDataAttr === true;

            if (!hasData) {
                console.log('Stock card table rendered without data. Skipping DataTable initialization.');
                isStockCardDataTableInitializing = false;
                return;
            }

            try {
                var dataTable = $table.DataTable({
                ordering: false,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"datatable-header"l B><"datatable-scroll-wrap"t><"datatable-footer"ip>',
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
                            title: 'Stock Card Detail Statement'
                        },
                        {
                            extend: 'csv',
                            className: 'btn btn-success btn-sm',
                            text: '<i class="icon-file-text2 me-1"></i>CSV',
                            title: 'Stock Card Detail Statement',

                                // filename: function() {
                                //     var timestamp = getCurrentDateTimeFormatted();
                                //     var dateRange = getDateRange();
                                //     var branchName = getBranchName();
                                //     var productName = getProductName();
                                //     return 'Stock_Card_' + timestamp + '_' + dateRange + '_' + branchName + '_' + productName;
                                // }
                            filename: 'Stock_Card_Export'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-info btn-sm',
                            text: '<i class="icon-file-excel me-1"></i>Excel',
                            title: 'Stock Card Detail Statement',
                            filename: 'Stock_Card_Export'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-danger btn-sm',
                            text: '<i class="icon-file-pdf me-1"></i>PDF',
                            title: 'Stock Card Detail Statement',
                            filename: 'Stock_Card_Export',
                            orientation: 'landscape',
                            pageSize: 'A4'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-warning btn-sm',
                            text: '<i class="icon-printer me-1"></i>Print',
                            title: 'Stock Card Detail Statement'
                        }
                    ]
                }
            });

            console.log('Stock Card DataTable initialized successfully with export buttons');

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

            // Add custom click handlers for dynamic filenames
            setTimeout(function() {
                var csvButton = dataTable.button('.btn-success');
                if (csvButton && csvButton.length) {
                    $(csvButton.node()).off('click').on('click', function(e) {
                        e.preventDefault();
                        var timestamp = getCurrentDateTimeFormatted();
                        var dateRange = getDateRange();
                        var branchName = getBranchName();
                        var productName = getProductName();
                        var filename = 'Stock_Card_' + timestamp + '_' + dateRange + '_' + branchName + '_' + productName + '.csv';

                        csvButton.trigger();

                        setTimeout(function() {
                            console.log('CSV Export triggered with filename: ' + filename);
                        }, 100);
                    });
                }

                var excelButton = dataTable.button('.btn-info');
                if (excelButton && excelButton.length) {
                    $(excelButton.node()).off('click').on('click', function(e) {
                        e.preventDefault();
                        var timestamp = getCurrentDateTimeFormatted();
                        var dateRange = getDateRange();
                        var branchName = getBranchName();
                        var productName = getProductName();
                        var filename = 'Stock_Card_' + timestamp + '_' + dateRange + '_' + branchName + '_' + productName + '.xlsx';

                        excelButton.trigger();

                        setTimeout(function() {
                            console.log('Excel Export triggered with filename: ' + filename);
                        }, 100);
                    });
                }

                var pdfButton = dataTable.button('.btn-danger');
                if (pdfButton && pdfButton.length) {
                    $(pdfButton.node()).off('click').on('click', function(e) {
                        e.preventDefault();
                        var timestamp = getCurrentDateTimeFormatted();
                        var dateRange = getDateRange();
                        var branchName = getBranchName();
                        var productName = getProductName();
                        var filename = 'Stock_Card_' + timestamp + '_' + dateRange + '_' + branchName + '_' + productName + '.pdf';

                        pdfButton.trigger();

                        setTimeout(function() {
                            console.log('PDF Export triggered with filename: ' + filename);
                        }, 100);
                    });
                }
            }, 200);
            } catch (error) {
                console.error('DataTable initialization failed:', error);
                isStockCardDataTableInitializing = false;
                return;
            }
            
            // Reset the flag when initialization is complete
            isStockCardDataTableInitializing = false;
        }

        function scheduleStockCardInit(reason, delay) {
            var actualDelay = typeof delay === 'number' ? delay : 300;
            console.log('Scheduling stock card DataTable init (' + reason + ') in ' + actualDelay + 'ms');
            setTimeout(function() {
                initStockCardDataTable();
            }, actualDelay);
        }

        if (typeof document !== 'undefined') {
            document.addEventListener('DOMContentLoaded', function() {
                scheduleStockCardInit('DOMContentLoaded', 500);
            });

            document.addEventListener('livewire:load', function() {
                if (typeof Livewire !== 'undefined' && Livewire.hook) {
                    Livewire.hook('message.sent', function() {
                        destroyStockCardDataTable();
                    });

                    Livewire.hook('message.processed', function(message, component) {
                        try {
                            var componentName = component?.fingerprint?.name || 'unknown';
                            var hasTableInComponent = false;

                            if (component && component.el && typeof component.el.querySelector === 'function') {
                                hasTableInComponent = !!component.el.querySelector('.datatable-stock-card');
                            }

                            var hasTableInDocument = !!document.querySelector('.datatable-stock-card');

                            if (hasTableInComponent || hasTableInDocument) {
                                scheduleStockCardInit('Livewire message.processed (' + componentName + ')', 350);
                            }
                        } catch (hookError) {
                            console.error('Error in Livewire message.processed hook for stock card init:', hookError);
                        }
                    });
                } else {
                    console.warn('Livewire.hook not available; falling back to livewire:updated event for stock card init.');
                    document.addEventListener('livewire:updated', function() {
                        scheduleStockCardInit('fallback livewire:updated', 350);
                    });
                }
            });
        }

    </script>
@endpush
