	<!--Page Container-->
<section class="main-container">					
		
    
    <!--Page Header-->
    <div class="header no-margin-bottom">
        <div class="header-content">
            <div class="page-title">
                <i class="icon-user position-left"></i> Product
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
                @livewire('product.product-detail')
            </div>
        </div> 
    </div>
</section>


@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Initial Venobox initialization
        console.log('venobox:initialized');
        if (typeof $.fn.venobox === 'function') {
            $('.venobox').venobox();
        }

        // Listen for product selection event
        Livewire.on('productSelected', (data) => {
            // Reinitialize Venobox after product details are loaded
            console.log('venobox:productSelected');
            $('.venobox').venobox();
        });
    });
</script>
<script src="{{ asset('js/venobox.js') }}"></script>
@endpush

<!--/Page Container-->

