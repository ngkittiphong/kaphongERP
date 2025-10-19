<div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row p-l-10 p-r-10 panel panel-flat">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="icon-plus-circle2 position-left"></i>
                            Add New Product Transfer
                        </h3>
                        <div class="heading-elements">
                            <button type="button" class="btn btn-sm btn-default" wire:click="hideForm">
                                <i class="icon-cross2"></i> Cancel
                            </button>
                        </div>
                    </div>
                
                <form wire:submit.prevent="submit" onsubmit="console.log('🔥 Form submit event triggered');">
                    <div class="panel-body" style="overflow: visible;">
                        <!-- Transfer Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-semibold">Transfer Information</h6>
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
                                    <label class="control-label">Transfer Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="dateRequest" readonly>
                                    @error('dateRequest') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Requested By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="userRequestName" readonly>
                                    @error('userRequestName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Deliver Name</label>
                                    <input type="text" class="form-control" wire:model="deliverName" placeholder="Enter deliver name">
                                    @error('deliverName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Origin Warehouse <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="warehouseOriginId">
                                        <option value="">Select Origin Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }} ({{ $warehouse->branch->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                    @error('warehouseOriginId') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Destination Warehouse <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="warehouseDestinationId">
                                        <option value="">Select Destination Warehouse</option>
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
                                    <label class="control-label">Description</label>
                                    <textarea class="form-control" wire:model="description" rows="2" placeholder="Enter transfer description"></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Note</label>
                                    <textarea class="form-control" wire:model="note" rows="2" placeholder="Enter additional notes"></textarea>
                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Transfer Details -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-semibold">Product Transfer Details</h6>
                                <hr>
                            </div>
                        </div>
                        
                        <div class="row" style="overflow: visible;">
                            <div class="col-md-12" style="overflow: visible;">
                                <div class="table-responsive" style="overflow: visible;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="30%">Product</th>
                                                <th width="15%">Quantity</th>
                                                <th width="15%">Unit</th>
                                                <th width="15%">Cost/Unit</th>
                                                <th width="15%">Total Cost</th>
                                                <th width="10%">Action</th>
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
                                                              placeholder="Search product by name or SKU..."
                                                              wire:model.defer="transferProducts.{{ $index }}.product_search"
                                                              autocomplete="off">
                                                   </div>
                                                    
                                                    @error('transferProducts.'.$index.'.product_id') 
                                                        <span class="text-danger small">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           wire:model="transferProducts.{{ $index }}.quantity" 
                                                           step="0.01" min="0.01">
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
                                                            <i class="icon-plus"></i> Add
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
                                                <strong>Total Quantity: {{ $this->getTotalQuantity() }}</strong>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Total Cost: ${{ number_format($this->getTotalCost(), 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-default" wire:click="hideForm" 
                                    @if($isSubmitting) disabled @endif>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" 
                                    @if($isSubmitting) disabled @endif>
                                @if($isSubmitting)
                                    <i class="icon-spinner2 spinner"></i> Creating...
                                @else
                                    <i class="icon-checkmark"></i> Create Transfer
                                @endif
                            </button>
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
</style>
@endpush



