	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-user position-left"></i> {{ __t('menu.products', 'Product') }}
            </div>
            @livewire('product.product-add-product-btn')
        </div>
    </div>		
    <!--/Page Header-->

    <div class="container-fluid page-people">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 secondary-sidebar">
                <div class="sidebar-content" style="height: 100vh">
                    @livewire('product.product-list')
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                @livewire('product.product-detail', ['productId' => request()->get('product_id')])
            </div>
        </div> 
    </div>
</section>


@push('scripts')
<script>
    function initProductDataTable() {
        console.log('Initializing Product DataTable...');
        console.log('jQuery version:', $.fn.jquery);
        console.log('DataTable available:', typeof $.fn.DataTable);
        console.log('Table elements found:', $('.datatable-reorder-state-saving').length);
        console.log('Table HTML:', $('.datatable-reorder-state-saving')[0]?.outerHTML);
        
        // Check if DataTable is already initialized and destroy it if exists
        if ($.fn.DataTable.isDataTable('.datatable-reorder-state-saving')) {
            console.log('Destroying existing DataTable...');
            $('.datatable-reorder-state-saving').DataTable().destroy();
        }

        // Check if the table exists
        if ($('.datatable-reorder-state-saving').length === 0) {
            console.log('Table not found, skipping DataTable initialization');
            return;
        }

        try {
            // Initialize DataTable with proper configuration
            $('.datatable-reorder-state-saving').DataTable({
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

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder', '{{ __t("common.find", "Find") }}');

            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
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
            console.error('Error initializing DataTable:', error);
        }
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        console.log('DOM ready, initializing components...');
        
        // Initialize Venobox
        if (typeof $.fn.venobox === 'function') {
            $('.venobox').venobox();
        }

        // Initialize DataTable after a short delay to ensure Livewire is ready
        setTimeout(() => {
            initProductDataTable();
        }, 500);
    });

    // Listen for Livewire events
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized');
        
        // Listen for product selection event
        Livewire.on('productSelected', (data) => {
            console.log('Product selected:', data);
            // Reinitialize Venobox after product details are loaded
            if (typeof $.fn.venobox === 'function') {
                $('.venobox').venobox();
            }
        });

        // Listen for product list updates
        Livewire.on('productListUpdated', () => {
            console.log('Product list updated, reinitializing DataTable...');
            setTimeout(() => {
                initProductDataTable();
            }, 200);
        });
    });
</script>
<script src="{{ asset('js/venobox.js') }}"></script>
@endpush

<!--/Page Container-->

