@push('scripts')
    <script>
        function initDataTable() {
            // Check if DataTable is already initialized and destroy it if exists
            // This prevents duplicate initialization errors
            if ($.fn.DataTable.isDataTable('.datatable-reorder-state-saving')) {
                $('.datatable-reorder-state-saving').DataTable().destroy();
            }

            $.extend($.fn.dataTable.defaults, {
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
                        'first': 'First',
                        'last': 'Last',
                        'next': '&rarr;',
                        'previous': '&larr;'
                    }
                }
            });

            // Save state after reorder
            $('.datatable-reorder-state-saving').DataTable({
                stateSave: true,
                fixedColumns: true,
                scrollResize: true,
                scrollX: true,
                scrollCollapse: true
            });

            // Add placeholder to the datatable filter option
            $('.dataTables_filter input[type=search]').attr('placeholder', '{{ __("Find") }}');

            // Enable Select2 select for the length option
            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });

            $(".lease-order-row").on("click", function() {
                $(".lease-order-row").removeClass('active');
                $(this).addClass(' active');
            });
        }

        initDataTable();

        document.addEventListener('livewire:initialized', () => {
            @this.on('{{ $listUpdatedEvent ?? 'userListUpdated' }}', () => {
                setTimeout(() => {
                    initDataTable();
                }, 100);
            });
        });
    </script>
@endpush
