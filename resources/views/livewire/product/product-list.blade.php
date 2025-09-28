<!------------- Start Product List ---->

<div class="table-responsive">
    <table class="table table-hover datatable-reorder-state-saving datatable-fixed-left">
        <thead>
            <tr>
                <th scope="col">{{ __t('menu.products', 'Products') }}</th>
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
                                    <div class="text-size-large text-dark">
                                        {{ $product->sku_number }}
                                    </div>
                                    <div class="text-size-large text-dark">
                                        {{ $product->type->name }}
                                    </div>
                                    @if($product->status)
                                        <div class="text-size-small text-muted">
                                            <span class="badge bg-{{ $this->getStatusColor($product->status->name) }}">
                                                {{ $product->status->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-1 col-sm-1">
                                <div class="media-right media-middle">
                                    @if($product->status)
                                        <div class="status-circle bg-{{ $this->getStatusColor($product->status->name) }}" 
                                             style="width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                                            <i class="icon-{{ $this->getStatusIcon($product->status->name) }} text-white"></i>
                                        </div>
                                    @else
                                        <span class="status-mark bg-secondary" placeholder=""></span>
                                    @endif
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
                             <i class="icon-info22"></i> {{ __t('product.no_products_found', 'No products found.') }} 
                             <br><small>{{ __t('product.products_count', 'Products count') }}: {{ isset($items) ? count($items) : 'undefined' }}</small>
                         </div>
                     </td>
                 </tr>
             @endif
         </tbody>
     </table>
 </div>

