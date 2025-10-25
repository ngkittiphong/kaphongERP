<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon-check position-left"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon-cross position-left"></i>
            {{ session('error') }}
        </div>
    @endif

    @if ($showAddForm)
        @livewire('warehouse.warehouse-add-transfer-form')
    @elseif($transferSlip)
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row p-l-10 p-r-10 panel panel-flat">
                        <div class="panel-heading">
                            <div class="tabbable">
                                <ul class="nav nav-tabs nav-tabs-highlight">
                                    <li class="active">
                                        <a href="#tab-detail" data-toggle="tab" class="panel-title"
                                            aria-expanded="true">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">{{ __t('common.detail', 'Detail') }}</h3>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-detail">
                                <div class="row col-md-12 col-xs-12">
                                    <div class="panel-heading no-padding-bottom">
                                        <div class="row">
                                            <div class="col-md-8 col-xs-8 col-lg-8">
                                                <h4>
                                                    <i class="icon-file-text position-left text-warning"></i>
                                                    {{ $transferSlip->transfer_slip_number }} 
                                                    <span class="text-primary">
                                                        <i
                                                            class="icon-{{ $this->getStatusIcon($transferSlip->status->name ?? '') }} position-left"></i>
                                                        ({{ $transferSlip->status->translated_name ?? 'N/A' }})
                                                    </span>
                                                </h4>
                                            </div>
                                            <div class="col-md-4 col-xs-4 col-lg-4 text-right">
                                                <div class="btn-group">
                                                    @if ($this->canChangeStatus())
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="icon-cog position-left"></i>
                                                            {{ __t('transfer.change_status', 'Change Status') }}
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right"
                                                            style="min-width: 200px;">
                                                            @foreach ($this->getAllowedStatusChanges() as $status)
                                                                <a class="dropdown-item" href="#"
                                                                    wire:click="confirmStatusChange({{ $status->id }})"
                                                                    style="display: block; width: 100%; padding: 10px 16px; border-bottom: 1px solid #f0f0f0; clear: both;">
                                                                    <i class="icon-{{ $this->getStatusIcon($status->name) }} position-left text-{{ $this->getStatusColor($status->name) }}"
                                                                        style="margin-right: 8px;"></i>
                                                                    <span
                                                                        style="display: inline-block; vertical-align: middle;">{{ $status->translated_name }}</span>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    @if ($this->canCancelTransfer())
                                                        <button type="button" class="btn btn-danger"
                                                            wire:click="openCancelModal">
                                                            <i class="icon-cross position-left"></i>
                                                            {{ __t('transfer.cancel_transfer', 'Cancel Transfer') }}
                                                        </button>
                                                    @endif
                                                    
                                                    @if (!$this->canChangeStatus() && !$this->canCancelTransfer())
                                                        <div class="text-muted">
                                                            <i class="icon-lock position-left"></i>
                                                            Status Locked
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="row col-md-12 col-xs-12 col-lg-12">
                                            <div class="col-md-3 col-xs-12 col-lg-3 text-left text-size-extralarge">
                                                <i class="icon-office position-left text-primary"></i>
                                                {{ $transferSlip->company_name ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-9 col-xs-12 col-lg-9 text-left text-size-extralarge">
                                                <i class="icon-calendar position-left text-info"></i>
                                                {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : 'N/A' }}
                                            </div>
                                        </div>
                                        
                                        @if ($transferSlip->description)
                                            <div class="col-md-12 col-xs-12 col-lg-12 text-left text-size-extralarge">
                                                Description: {{ $transferSlip->description }}
                                            </div>
                                        @endif
                                        
                                        @if ($transferSlip->note)
                                            <div class="col-md-12 col-xs-12 col-lg-12 text-left text-size-extralarge">
                                                Remark: {{ $transferSlip->note }}
                                            </div>
                                        @endif
                                        
                                        <div class="row">
                                            <div
                                                class="col-md-6 col-xs-6 col-lg-6 text-left text-size-extralarge panel panel-white">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <span
                                                            class="text-primary">{{ $transferSlip->warehouse_origin_name ?? ($transferSlip->warehouseOrigin->name ?? 'N/A') }}</span>
                                                        - {{ __t('transfer.outbound', 'Outbound') }}
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div
                                                            class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            {{ __t('transfer.pick_date', 'Pick Date') }}:
                                                        </div>
                                                        <div
                                                            class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->date_request ? $transferSlip->date_request->format('d M Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div
                                                            class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            {{ __t('transfer.picker', 'Picker') }}:
                                                        </div>
                                                        <div
                                                            class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->user_request_name ?? ($transferSlip->userRequest->username ?? 'N/A') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div
                                                class="col-md-6 col-xs-6 col-lg-6 text-left text-size-extralarge panel panel-white">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <span
                                                            class="text-primary">{{ $transferSlip->warehouse_destination_name ?? ($transferSlip->warehouseDestination->name ?? 'N/A') }}</span>
                                                        - {{ __t('transfer.inbound', 'Inbound') }}
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div
                                                            class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            {{ __t('transfer.receive_date', 'Receive Date') }}:
                                                        </div>
                                                        <div
                                                            class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->date_receive ? $transferSlip->date_receive->format('d M Y') : '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div
                                                            class="col-md-4 col-xs-6 col-lg-4 text-left text-size-extralarge">
                                                            {{ __t('transfer.receiver', 'Receiver') }}:
                                                        </div>
                                                        <div
                                                            class="col-md-8 col-xs-6 col-lg-8 text-left text-size-extralarge">
                                                            {{ $transferSlip->user_receive_name ?? ($transferSlip->userReceive->username ?? '-') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($transferSlip->transferSlipDetails && $transferSlip->transferSlipDetails->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table datatable-transfer-detail table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __t('product.product_code', 'Product Code') }}</th>
                                                        <th>{{ __t('product.product_name', 'Product Name') }}</th>
                                                        <th>{{ __t('product.quantity', 'Quantity') }}</th>
                                                        <th>{{ __t('product.unit', 'Unit') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($transferSlip->transferSlipDetails as $index => $detail)
                                                        <tr class="text-default">
                                                            <td class="col-md-1">{{ $index + 1 }}.</td>
                                                            <td class="col-md-1">
                                                                <a
                                                                    href="#">{{ $detail->product->sku_number ?? 'N/A' }}</a>
                                                            </td>
                                                            <td class="col-md-5">{{ $detail->product->name ?? 'N/A' }}
                                                            </td>
                                                            <td class="col-md-3">{{ $detail->quantity ?? 0 }}</td>
                                                            <td>{{ $detail->product->unit_name ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="icon-inbox text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">{{ __t('product.no_product_details_found', 'No product details found') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center text-muted py-5">
                        <i class="icon-inbox text-muted" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">{{ __t('warehouse.no_transfer_selected', 'No Transfer Selected') }}</h4>
                        <p>{{ __t('warehouse.select_transfer_slip_from_list', 'Please select a transfer slip from the list to view details') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Change Confirmation Modal -->
    @if ($showStatusChangeModal)
        <div class="modal fade in" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1"
            role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="icon-warning text-warning position-left"></i>
                            {{ __t('common.confirm_status_change', 'Confirm Status Change') }}
                        </h5>
                    </div>
                    <div class="modal-body">
                        <p>{{ __t('common.confirm_status_change_message', 'Are you sure you want to change the status of transfer slip') }}
                            <strong>{{ $transferSlip->transfer_slip_number }}</strong> {{ __t('common.to', 'to') }}
                            <strong>{{ $this->selectedStatusTranslatedName }}</strong>?
                        </p>
                        
                        @if ($selectedStatusName === 'Cancelled')
                            <div class="form-group">
                                <label for="cancellationReason" class="control-label">{{ __t('warehouse.cancellation_reason', 'Cancellation Reason') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="cancellationReason" wire:model="cancellationReason" rows="3"
                                    placeholder="{{ __t('warehouse.provide_cancellation_reason', 'Please provide a reason for cancelling this transfer...') }}" required></textarea>
                                @error('cancellationReason')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            @if ($transferSlip->status->name === 'In Transit')
                                <div class="alert alert-warning">
                                    <i class="icon-warning position-left"></i>
                                    <strong>{{ __t('common.note', 'Note') }}:</strong> {{ __t('warehouse.transfer_in_transit_note', 'This transfer is currently in transit. Cancelling will restore') }}
                                    the inventory to the sender warehouse.
                                </div>
                            @endif
                        @endif
                        
                        @if ($selectedStatusName === 'Delivered')
                            <div class="alert alert-info">
                                <i class="icon-info position-left"></i>
                                This will also set the receive date to today and assign you as the receiver.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" wire:click="cancelStatusChange"
                                onclick="console.log('🔥 Cancel button clicked');">
                            <i class="icon-cross position-left"></i>
                            {{ __t('common.cancel', 'Cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="confirmStatusUpdate"
                                onclick="console.log('🔥 Confirm Change button clicked');">
                            <i class="icon-check position-left"></i>
                            {{ __t('common.confirm_change', 'Confirm Change') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancellation Modal -->
    @if ($showCancelModal)
        <div class="modal fade in" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1"
            role="dialog" id="cancelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="icon-warning text-danger position-left"></i>
                            Cancel Transfer
                        </h5>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel transfer slip
                            <strong>{{ $transferSlip->transfer_slip_number }}</strong>?
                        </p>
                        
                        <div class="form-group">
                            <label for="cancellationReason" class="control-label">{{ __t('warehouse.cancellation_reason', 'Cancellation Reason') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="cancellationReason" wire:model="cancellationReason" rows="3"
                                placeholder="{{ __t('warehouse.provide_cancellation_reason', 'Please provide a reason for cancelling this transfer...') }}" required></textarea>
                            @error('cancellationReason')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        @if ($transferSlip->status->name === 'In Transit')
                            <div class="alert alert-warning">
                                <i class="icon-warning position-left"></i>
                                <strong>{{ __t('common.note', 'Note') }}:</strong> {{ __t('warehouse.transfer_in_transit_note', 'This transfer is currently in transit. Cancelling will restore') }}
                                the inventory to the sender warehouse.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" wire:click="hideCancelModal">
                            <i class="icon-cross position-left"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="cancelTransfer"
                                onclick="console.log('🔥 Cancel Transfer confirmation clicked');">
                            <i class="icon-cross position-left"></i>
                            Cancel Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<style>
    /* Ensure consistent width for typeahead inputs */
    .product-typeahead {
        width: 100% !important;
        min-width: 200px !important;
    }

    .product-typeahead.tt-input {
        width: 100% !important;
        min-width: 200px !important;
        background-color: #fff !important;
    }

    /* Typeahead wrapper container */
    .twitter-typeahead {
        width: 100% !important;
        display: block !important;
    }

    /* Ensure the input field maintains its original width */
    input.product-typeahead {
        width: 100% !important;
        min-width: 200px !important;
        box-sizing: border-box !important;
    }

    /* Typeahead dropdown styles */
    .tt-menu {
        position: absolute !important;
        top: auto !important;
        bottom: calc(100% + 6px) !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 99999 !important;
        width: 100% !important;
        max-height: 260px !important;
        overflow-y: auto !important;
        background: #ffffff !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.08) !important;
    }

    .tt-suggestion {
        padding: 8px 12px !important;
        cursor: pointer !important;
        color: #333 !important;
        line-height: 1.4 !important;
        border-bottom: 1px solid #f0f0f0 !important;
        background: #fff !important;
    }

    .tt-suggestion:last-child {
        border-bottom: none !important;
    }

    .tt-suggestion:hover,
    .tt-suggestion.tt-cursor {
        background-color: #f5f5f5 !important;
    }

    .tt-highlight {
        font-weight: 600 !important;
        color: #2c3e50 !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.3.1/typeahead.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    console.log('TransferDetail: Script initialized');

    let bloodhoundEngine = null;
    let productDataset = [];

    const suggestionTemplate = (item) => {
        const unit = item.unit || 'N/A';
        const price = (parseFloat(item.price) || 0).toFixed(2);
        return `
            <div>
                <strong>${item.name}</strong><br>
                <small class="text-muted">{{ __t('product.sku', 'SKU') }}: ${item.sku} | {{ __t('product.unit', 'Unit') }}: ${unit} | {{ __t('product.price', 'Price') }}: $${price}</small>
            </div>
        `;
    };

    const initTypeahead = (data = null) => {
        if (!window.jQuery) {
            console.error('Typeahead: jQuery missing');
            return false;
        }

        // Use provided data or fallback to stored dataset
        if (data && data.length > 0) {
            productDataset = data;
        }
        
        if (productDataset.length === 0) {
            console.warn('Typeahead: No products found, dataset length:', productDataset.length);
            return false;
        }

        console.log('Typeahead: Initializing with', productDataset.length, 'products');

        // Initialize Bloodhound with product data
        bloodhoundEngine = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('search'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: productDataset,
            identify: obj => obj.id,
            sufficient: 5
        });

        bloodhoundEngine.initialize();

        const inputs = document.querySelectorAll('input.product-typeahead');
        if (inputs.length === 0) {
            console.log('Typeahead: No input fields found');
            return false;
        }

        console.log('Typeahead: Found', inputs.length, 'input fields');

        inputs.forEach((input, idx) => {
            const $input = window.jQuery(input);

            if ($input.data('tt-initialized')) {
                $input.typeahead('destroy');
                $input.removeData('tt-initialized');
            }

            $input.typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'products',
                display: 'display',
                limit: 10,
                source: bloodhoundEngine.ttAdapter(),
                templates: {
                    notFound: '<div class="tt-suggestion">{{ __t('product.no_products_found', 'No products found') }}</div>',
                    suggestion: suggestionTemplate
                }
            }).on('typeahead:select typeahead:autocomplete', function (e, datum) {
                console.log('Typeahead: Product selected:', datum);
                const index = this.dataset.index;
                if (index !== undefined) {
                    Livewire.dispatch('selectProductFromSearch', {
                        id: datum.id,
                        index: parseInt(index, 10)
                    });
                }
            }).on('typeahead:close', function () {
                if (!this.value) {
                    Livewire.dispatch('clearProductSearch');
                }
            }).on('focus', function() {
                // Re-initialize typeahead when input gains focus
                console.log('Typeahead: Input focused, ensuring typeahead is active');
                const $this = window.jQuery(this);
                if (!$this.data('tt-initialized')) {
                    console.log('Typeahead: Re-initializing on focus');
                    setTimeout(() => {
                        if (productDataset.length > 0) {
                            initTypeahead();
                        }
                    }, 50);
                }
            }).on('blur', function() {
                // Keep typeahead active but don't destroy it
                console.log('Typeahead: Input blurred');
            });

            $input.data('tt-initialized', true);
        });

        console.log('Typeahead: Initialization complete');
        return true;
    };

    // Try initialization with retry mechanism
    const tryInit = (attempt = 1, maxAttempts = 10) => {
        setTimeout(() => {
            const success = initTypeahead();
            
            if (!success && attempt < maxAttempts) {
                console.log(`Typeahead: Attempt ${attempt}/${maxAttempts} failed, retrying...`);
                tryInit(attempt + 1, maxAttempts);
            } else if (!success) {
                console.error('Typeahead: Failed after', maxAttempts, 'attempts');
            } else {
                console.log('Typeahead: Successfully initialized on attempt', attempt);
            }
        }, attempt * 100); // 100ms, 200ms, 300ms, etc.
    };

    document.addEventListener('livewire:initialized', () => {

    // Add global focus listener for all product typeahead inputs
    $(document).on('focus', 'input.product-typeahead', function() {
        console.log('Typeahead: Global focus event on product input');
        const $this = window.jQuery(this);
        if (!$this.data('tt-initialized')) {
            console.log('Typeahead: Re-initializing on global focus');
            setTimeout(() => {
                if (productDataset.length > 0) {
                    initTypeahead();
                }
            }, 50);
        }
    });

    // Listen for when the transfer form is ready
    Livewire.on('transferFormReady', (event) => {
        console.log('Typeahead: transferFormReady event received with data:', event);
        const productData = event[0]?.productData || event.productData || [];
        console.log('Typeahead: Received', productData.length, 'products from event');
        
        // Initialize immediately with the received data
        const success = initTypeahead(productData);
        if (!success) {
            console.log('Typeahead: Direct initialization failed, trying with retry mechanism');
            tryInit();
        }
    });

    // Re-initialize after Livewire updates
    Livewire.hook('message.processed', () => {
        console.log('Typeahead: message.processed event received');
        setTimeout(() => {
            // Only try to init if we have data stored
            if (productDataset.length > 0) {
                initTypeahead();
            }
        }, 100);
    });

    // Listen for new product row added
    Livewire.on('transferProductAdded', () => {
        console.log('Typeahead: New product row added, re-initializing...');
        setTimeout(() => {
            if (productDataset.length > 0) {
                initTypeahead();
            }
        }, 100);
    });

    // Listen for product row removed
    Livewire.on('transferProductRemoved', () => {
        console.log('Typeahead: Product row removed, re-initializing...');
        setTimeout(() => {
            if (productDataset.length > 0) {
                initTypeahead();
            }
        }, 100);
    });

    // Listen for transfer confirmation event
    Livewire.on('confirmTransferCreation', (event) => {
        console.log('🔥 Transfer confirmation event received:', event);
        
        // Handle both array and object event formats
        const data = Array.isArray(event) ? event[0] : event;
        console.log('🔥 Processed event data:', data);
        
        // Validate data structure
        if (!data || typeof data !== 'object') {
            console.error('🔥 Invalid event data received:', data);
            return;
        }
        
        // Set default values to prevent undefined errors
        const transferData = {
            transferSlipNumber: data.transferSlipNumber || 'TF' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + String(new Date().getDate()).padStart(2, '0') + '0001',
            originWarehouse: data.originWarehouse || 'Unknown',
            destinationWarehouse: data.destinationWarehouse || 'Unknown',
            productCount: data.productCount || 0,
            totalQuantity: data.totalQuantity || 0,
            totalCost: data.totalCost || 0,
            transferProducts: data.transferProducts || []
        };
        
        console.log('🔥 Using transfer data:', transferData);
        
        if (typeof Swal === 'undefined') {
            console.warn('⚠️ SweetAlert not available, using fallback confirmation.');
            const confirmed = window.confirm(
                `Confirm Transfer Creation?\n\n` +
                `Transfer Slip Number: ${transferData.transferSlipNumber}\n` +
                `From: ${transferData.originWarehouse}\n` +
                `To: ${transferData.destinationWarehouse}\n` +
                `Products: ${transferData.productCount}\n` +
                `Total Quantity: ${transferData.totalQuantity}\n` +
                `Total Cost: $${transferData.totalCost.toFixed(2)}`
            );
            if (confirmed) {
                Livewire.dispatch('confirmSubmit', { confirmed: true }, 'warehouse.warehouse-add-transfer-form');
            }
            return;
        }

        // Build product list HTML with error handling
        let productListHtml = '';
        try {
            productListHtml = transferData.transferProducts.map(product => `
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                    <div style="flex: 1;">
                        <strong>${product.name || 'Unknown Product'}</strong>
                    </div>
                    <div style="text-align: right;">
                        ${product.quantity || 0} ${product.unit || 'pcs'} - $${(product.cost || 0).toFixed(2)}
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('🔥 Error building product list:', error);
            productListHtml = '<div>{{ __t('product.error_loading_product_details', 'Error loading product details') }}</div>';
        }

        Swal.fire({
            title: '{{ __t("transfer.confirm_transfer_creation", "Confirm Transfer Creation") }}',
            html: `
                <div style="text-align: left;">
                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <h5 style="margin: 0 0 10px 0; color: #495057;">{{ __t('transfer.transfer_details', 'Transfer Details') }}</h5>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                            <div><strong>{{ __t('transfer.from', 'From') }}:</strong> ${transferData.originWarehouse}</div>
                            <div><strong>{{ __t('transfer.to', 'To') }}:</strong> ${transferData.destinationWarehouse}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                            <div><strong>{{ __t('transfer.items', 'Items') }}:</strong> ${transferData.productCount}</div>
                            <div><strong>{{ __t('transfer.total_qty', 'Total Qty') }}:</strong> ${transferData.totalQuantity}</div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div><strong>{{ __t('transfer.transfer_slip_number', 'Transfer Slip Number') }}:</strong> <span style="color: #007bff; font-weight: bold;">${transferData.transferSlipNumber}</span></div>
                            <div><strong>{{ __t('transfer.date', 'Date') }}:</strong> ${new Date().toLocaleDateString()}</div>
                        </div>
                    </div>
                    
                    <div style="background-color: #e8f4fd; padding: 10px; border-radius: 6px; text-align: center; margin-bottom: 15px;">
                        <p style="margin: 0; font-weight: bold; color: #495057;">
                            {{ __t('transfer.total_cost', 'Total Cost') }}: 
                            <span style="color: #007bff; font-size: 1.2em;">$${transferData.totalCost.toFixed(2)}</span>
                        </p>
                    </div>

                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px;">
                        <h6 style="margin: 0 0 10px 0; color: #495057;">{{ __t('transfer.products_to_transfer', 'Products to be transferred') }}:</h6>
                        ${productListHtml}
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __t("transfer.create_transfer", "Create Transfer") }}',
            cancelButtonText: '{{ __t("common.cancel", "Cancel") }}',
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            allowOutsideClick: false,
            allowEscapeKey: false,
            width: '700px',
            didOpen: () => {
                console.log('🔥 Transfer confirmation dialog opened');
            }
        }).then((result) => {
            console.log('🔥 Transfer confirmation result:', result);
            if (result.isConfirmed) {
                console.log('🔥 User confirmed transfer creation');
                Livewire.dispatch('confirmSubmit', { confirmed: true }, 'warehouse.warehouse-add-transfer-form');
            } else {
                console.log('🔥 User cancelled transfer creation');
            }
        });
    });

});
</script>
@endpush

