<div class="tab-pane" id="tab-trading">
    <div class="row col-md-12 col-xs-12">
        <div class="col-md-6 col-xs-6">
            <div class="panel-heading no-padding-bottom">
                <h4 class="panel-title"><?= __('Product sale details') ?></h4>
            </div>
            <div class="list-group list-group-lg list-group-borderless">
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            Sale Price :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->sale_price }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->saleVat->name }}
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->saleVat->price_percent }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->saleWithholding->name }}
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->saleWithholding->price_percent }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-12 col-xs-12 text-bold">
                            Sale Description :
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
                <h4 class="panel-title"><?= __('Product buy details') ?></h4>
            </div>
            <div class="list-group list-group-lg list-group-borderless">
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            Buy Price :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->buy_price }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->buyVat->name }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->buyVat->price_percent }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-3 col-xs-3 text-bold">
                            {{ $product->buyWithholding->name }} :
                        </div>
                        <div class="col-md-8 col-xs-8 text-left">
                            {{ $product->buyWithholding->price_percent }}
                        </div>
                    </span>
                </div>
                <div class='row'>
                    <span href="#" class="list-group-item p-l-20">
                        <div class="col-md-12 col-xs-12 text-bold">
                            Buy Description :
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