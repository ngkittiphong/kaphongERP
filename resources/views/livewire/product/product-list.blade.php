 <!------------- Start Product List ---->

 <div class="table-responsive">
     <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
         <thead>
             <tr>
                 <th scope="col"><?= __('Products') ?></th>
             </tr>
         </thead>
         <tbody>
             @foreach ($products as $product)
                 <tr class="lease-order-row hover:bg-gray-100 cursor-pointer"
                     wire:click="$dispatch('ProductSelected', { productId: {{ $product->id }} })">
                     <td>
                         <div class="row col-md-12">
                             <div class="col-md-3 col-sm-3">
                                 <div class="thumb media-middle">
                                     <a href="{{ asset('assets/images/default_product.png') }}" class="venobox">
                                         <img src="{{ asset('assets/images/default_product.png') }}" alt="">
                                         <span class="zoom-image"><i class="icon-plus2"></i></span>
                                     </a>
                                 </div>
                             </div>

                             <div class="col-md-8 col-sm-8">
                                 <div class="media-body">
                                     <div class="media-heading text-size-extralarge text-dark">
                                         {{ $product->name }}
                                     </div>
                                     <div class=" text-size-large text-dark">
                                         {{ $product->sku_number }}
                                     </div>
                                     <div class=" text-size-large text-dark">
                                         {{ $product->type->name }}
                                     </div>
                                 </div>
                             </div>

                             <div class="col-md-1 col-sm-1">
                                 <div class="media-right media-middle">
                                     <span class="status-mark bg-{{ 'green' }}" placeholder=""></span>
                                 </div>
                             </div>
                         </div>
                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>
 </div>

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
                 //                scrollX: true,
                 //        scrollY: '500px',
                 //        scrollCollapse: true

                 scrollResize: true,
                 scrollX: true,
                 scrollY: '100vh',
                 scrollCollapse: true
             });

             // Add placeholder to the datatable filter option
             $('.dataTables_filter input[type=search]').attr('placeholder', '<?= __('Find') ?>');

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
             @this.on('userListUpdated', () => {
                 setTimeout(() => {
                     initDataTable();
                 }, 100);
             });
         });
     </script>
 @endpush
