<div class="tab-pane" id="tab-trading">
    <div class="row col-md-12 col-xs-12">
        <div class="col-md-6 col-xs-6">
            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title">{{ __t('product.product_sale_details', 'Product sale details') }}</h4>
            </div>
            <div class="list-group list-group-lg list-group-borderless">
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ __t('product.sale_price', 'Sale Price') }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ currency($product->sale_price) }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->saleVat ? $product->saleVat->name : 'No VAT' }}
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->saleVat ? $product->saleVat->price_percent : '0' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->saleWithholding ? $product->saleWithholding->name : 'No Withholding' }}
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->saleWithholding ? $product->saleWithholding->price_percent : '0' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-12 col-xs-12 text-bold">
                            {{ __t('product.sale_description', 'Sale Description') }} :
                        </div>
                        <div class="col-md-12 col-xs-12 text-left">
                            {{ $product->sale_description }}
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <div class="row col-md-6 col-xs-6">

            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title">{{ __t('product.product_buy_details', 'Product buy details') }}</h4>
            </div>
            <div class="list-group list-group-lg list-group-borderless">
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ __t('product.buy_price', 'Buy Price') }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ currency($product->buy_price) }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->buyVat ? $product->buyVat->name : 'No VAT' }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->buyVat ? $product->buyVat->price_percent : '0' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->buyWithholding ? $product->buyWithholding->name : 'No Withholding' }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->buyWithholding ? $product->buyWithholding->price_percent : '0' }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-12 col-xs-12 text-bold">
                            {{ __t('product.buy_description', 'Buy Description') }} :
                        </div>
                        <div class="col-md-12 col-xs-12 text-left">
                            {{ $product->buy_description }}
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>