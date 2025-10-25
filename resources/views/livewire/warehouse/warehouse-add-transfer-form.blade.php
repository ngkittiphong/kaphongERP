<div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row p-l-10 p-r-10 panel panel-flat">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="icon-plus-circle2 position-left"></i>
                            {{ __t('transfer.add_new_product_transfer', 'Add New Product Transfer') }}
                        </h3>
                        <div class="heading-elements">
                            <button type="button" class="btn btn-sm btn-default" wire:click="hideForm">
                                <i class="icon-cross2"></i> {{ __t('common.cancel', 'Cancel') }}
                            </button>
                        </div>
                    </div>
                
                <form wire:submit.prevent="submit" onsubmit="console.log('🔥 Form submit event triggered');">
                    <div class="panel-body" style="overflow: visible;">
                        <!-- Transfer Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-semibold">{{ __t('transfer.transfer_information', 'Transfer Information') }}</h6>
                                <hr>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="companyName" readonly>
                                    @error('companyName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.transfer_date', 'Transfer Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="dateRequest" readonly>
                                    @error('dateRequest') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.requested_by', 'Requested By') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="userRequestName" readonly>
                                    @error('userRequestName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.deliver_name', 'Deliver Name') }}</label>
                                    <input type="text" class="form-control" wire:model="deliverName" placeholder="{{ __t('transfer.enter_deliver_name', 'Enter deliver name') }}">
                                    @error('deliverName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.origin_warehouse', 'Origin Warehouse') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model.lazy="warehouseOriginId" @if($originWarehouseSelected) disabled @endif>
                                        <option value="">{{ __t('transfer.select_origin_warehouse', 'Select Origin Warehouse') }}</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->branch->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                    @error('warehouseOriginId') <span class="text-danger">{{ $message }}</span> @enderror
                                    @if($originWarehouseSelected)
                                        <small class="text-muted">
                                            <i class="icon-lock position-left"></i>
                                            {{ __t('transfer.origin_warehouse_locked', 'Origin warehouse is locked after selection') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.destination_warehouse', 'Destination Warehouse') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="warehouseDestinationId">
                                        <option value="">{{ __t('transfer.select_destination_warehouse', 'Select Destination Warehouse') }}</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->branch->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                    @error('warehouseDestinationId') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Tax ID</label>
                                    <input type="text" class="form-control" wire:model="taxId" placeholder="Enter tax ID">
                                    @error('taxId') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phone</label>
                                    <input type="text" class="form-control" wire:model="tel" placeholder="Enter phone number">
                                    @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Company Address</label>
                                    <textarea class="form-control" wire:model="companyAddress" rows="2" placeholder="Enter company address"></textarea>
                                    @error('companyAddress') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.description', 'Description') }}</label>
                                    <textarea class="form-control" wire:model="description" rows="2" placeholder="{{ __t('transfer.enter_transfer_description', 'Enter transfer description') }}"></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('transfer.note', 'Note') }}</label>
                                    <textarea class="form-control" wire:model="note" rows="2" placeholder="{{ __t('transfer.enter_additional_notes', 'Enter additional notes') }}"></textarea>
                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Transfer Details -->
                        @if($originWarehouseSelected)
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-semibold">{{ __t('transfer.product_transfer_details', 'Product Transfer Details') }}</h6>
                                <hr>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="icon-info position-left"></i>
                                    {{ __t('transfer.select_origin_warehouse_first', 'Please select an origin warehouse to add products for transfer.') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($originWarehouseSelected)
                        <div class="row" style="overflow: visible;">
                            <div class="col-md-12" style="overflow: visible;">
                                <div class="table-responsive" style="overflow: visible;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="30%">{{ __t('transfer.product', 'Product') }}</th>
                                                <th width="15%">{{ __t('transfer.quantity', 'Quantity') }}</th>
                                                <th width="15%">{{ __t('transfer.unit', 'Unit') }}</th>
                                                <th width="15%">{{ __t('transfer.cost_per_unit', 'Cost/Unit') }}</th>
                                                <th width="15%">{{ __t('transfer.total_cost', 'Total Cost') }}</th>
                                                <th width="10%">{{ __t('transfer.action', 'Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transferProducts as $index => $product)
                                            <tr>
                                                <td>
                                                   <div class="position-relative">
                                                       <input type="text"
                                                              class="form-control product-typeahead"
                                                              data-index="{{ $index }}"
                                                              placeholder="{{ __t('transfer.search_product_placeholder', 'Search product by name or SKU...') }}"
                                                              wire:model.defer="transferProducts.{{ $index }}.product_search"
                                                              autocomplete="off">
                                                   </div>
                                                   
                                                   @if(!empty($transferProducts[$index]['product_id']))
                                                       <div class="mt-1">
                                                           <span class="badge badge-info">
                                                               {{ __t('transfer.available', 'Available') }}: {{ $transferProducts[$index]['available_quantity'] ?? 0 }} {{ $transferProducts[$index]['unit_name'] ?? 'units' }}
                                                           </span>
                                                       </div>
                                                       
                                                       @if(($transferProducts[$index]['available_quantity'] ?? 0) <= 0)
                                                           <div class="alert alert-warning mt-1 mb-0" style="padding: 8px 12px; font-size: 12px;">
                                                               <i class="icon-warning position-left"></i>
                                                               <strong>{{ __t('common.warning', 'Warning') }}:</strong> {{ __t('transfer.warning_no_stock', 'This product has no available stock in the origin warehouse.') }}
                                                           </div>
                                                       @endif
                                                   @endif
                                                    
                                                    @error('transferProducts.'.$index.'.product_id') 
                                                        <span class="text-danger small">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           wire:model="transferProducts.{{ $index }}.quantity" 
                                                           step="1" min="1"                                pattern="[0-9]+"
                                                           inputmode="numeric"
                                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                           max="{{ !empty($transferProducts[$index]['product_id']) ? ($transferProducts[$index]['available_quantity'] ?? 0) : '' }}">
                                                    
                                                    @if(!empty($transferProducts[$index]['product_id']) && ($transferProducts[$index]['quantity'] ?? 0) > ($transferProducts[$index]['available_quantity'] ?? 0))
                                                        <div class="alert alert-danger mt-1 mb-0" style="padding: 6px 10px; font-size: 11px;">
                                                            <i class="icon-warning position-left"></i>
                                                            <strong>{{ __t('transfer.exceeds_available', 'Exceeds Available') }}:</strong> {{ __t('transfer.requested', 'Requested') }} {{ $transferProducts[$index]['quantity'] ?? 0 }} {{ __t('transfer.but_only_available', 'but only') }} {{ $transferProducts[$index]['available_quantity'] ?? 0 }} {{ __t('transfer.available_units', 'available') }}
                                                        </div>
                                                    @endif
                                                    
                                                    @error('transferProducts.'.$index.'.quantity') 
                                                        <span class="text-danger small">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" 
                                                           wire:model="transferProducts.{{ $index }}.unit_name" 
                                                           readonly>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           wire:model="transferProducts.{{ $index }}.cost_per_unit" 
                                                           step="0.01" min="0">
                                                    @error('transferProducts.'.$index.'.cost_per_unit') 
                                                        <span class="text-danger small">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           wire:model="transferProducts.{{ $index }}.cost_total" 
                                                           readonly>
                                                </td>
                                                <td>
                                                    @if(count($transferProducts) > 1)
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                wire:click="removeProduct({{ $index }})">
                                                            <i class="icon-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-right">
                                                    @if(count($transferProducts) < $maxProducts)
                                                        <button type="button" class="btn btn-sm btn-success" wire:click="addEmptyProduct">
                                                            <i class="icon-plus"></i> {{ __t('transfer.add', 'Add') }}
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                @error('transferProducts') 
                                    <span class="text-danger">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Summary -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ __t('transfer.total_quantity', 'Total Quantity') }}: {{ $this->getTotalQuantity() }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __t('transfer.total_cost_label', 'Total Cost') }}: ${{ number_format($this->getTotalCost(), 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="panel-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-default" wire:click="hideForm" 
                                    @if($isSubmitting) disabled @endif>
                                {{ __t('common.cancel', 'Cancel') }}
                            </button>
                            @if($originWarehouseSelected)
                                <button type="submit" class="btn btn-primary" 
                                        @if($isSubmitting) disabled @endif>
                                    @if($isSubmitting)
                                        <i class="icon-spinner2 spinner"></i> {{ __t('transfer.creating', 'Creating...') }}
                                    @else
                                        <i class="icon-checkmark"></i> {{ __t('transfer.create_transfer', 'Create Transfer') }}
                                    @endif
                                </button>
                            @else
                                <button type="button"
                                        class="btn btn-info"
                                        wire:click="proceedToProductSelection"
                                        wire:loading.attr="disabled"
                                        wire:target="proceedToProductSelection"
                                        @if(!$warehouseOriginId) disabled @endif>
                                    <i class="icon-arrow-right5"></i> {{ __t('transfer.next', 'Next') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Product dataset will be passed via Livewire event -->

@push('styles')
<style>
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

    .tt-menu::-webkit-scrollbar {
        width: 8px !important;
    }

    .tt-menu::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 4px !important;
    }

    .tt-menu::-webkit-scrollbar-thumb {
        background: #888 !important;
        border-radius: 4px !important;
    }

    .tt-menu::-webkit-scrollbar-thumb:hover {
        background: #555 !important;
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

    .product-typeahead.tt-input {
        background-color: #fff !important;
    }

    /* Ensure consistent width for typeahead inputs */
    .product-typeahead {
        width: 100% !important;
        min-width: 200px !important;
    }

    .product-typeahead.tt-input {
        width: 100% !important;
        min-width: 200px !important;
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

    .table-responsive {
        overflow: visible !important;
    }

    .panel-body {
        overflow: visible !important;
    }

    /* Disabled select field styling */
    select:disabled {
        background-color: #f5f5f5 !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
    }

    select:disabled option {
        background-color: #f5f5f5 !important;
        color: #6c757d !important;
    }

    /* Available quantity badge styling */
    .badge-info {
        background-color: #5bc0de !important;
        color: #fff !important;
        font-size: 11px !important;
        padding: 4px 8px !important;
    }

    /* Warning alert styling for insufficient stock */
    .alert-warning {
        background-color: #fcf8e3 !important;
        border-color: #faebcc !important;
        color: #8a6d3b !important;
        border-radius: 4px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    .alert-warning .icon-warning {
        color: #f0ad4e !important;
    }

    /* Alert danger styling for quantity validation */
    .alert-danger {
        background-color: #f2dede !important;
        border-color: #ebccd1 !important;
        color: #a94442 !important;
        border-radius: 4px !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    .alert-danger .icon-warning {
        color: #d9534f !important;
    }
</style>
@endpush


