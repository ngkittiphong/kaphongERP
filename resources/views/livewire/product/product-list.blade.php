 <!------------- Start Product List ---->

 <div class="table-responsive">
     <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
         <thead>
             <tr>
                 <th scope="col"><?= __('Products') ?></th>
             </tr>
         </thead>
         <tbody>
             @if(isset($items) && count($items) > 0)
                 @foreach ($items as $product)
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
             @else
                 <tr>
                     <td colspan="1" class="text-center">
                         <div class="alert alert-info">
                             <i class="icon-info22"></i> No products found. 
                             <br><small>Products count: {{ isset($items) ? count($items) : 'undefined' }}</small>
                         </div>
                     </td>
                 </tr>
             @endif
         </tbody>
     </table>
 </div>

