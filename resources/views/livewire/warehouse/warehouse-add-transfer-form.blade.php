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
                            <button type="button" class="btn btn-sm btn-info" wire:click="testMethod">
                                <i class="icon-bug"></i> Test
                            </button>
                            <button type="button" class="btn btn-sm btn-default" wire:click="hideForm">
                                <i class="icon-cross2"></i> Cancel
                            </button>
                        </div>
                    </div>
                
                <form wire:submit.prevent="submit" onsubmit="console.log('🔥 Form submit event triggered');">
                    <div class="panel-body">
                        <!-- Transfer Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-semibold">Transfer Information</h6>
                                <hr>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="companyName" placeholder="Enter company name">
                                    @error('companyName') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Transfer Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" wire:model="dateRequest">
                                    @error('dateRequest') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Requested By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="userRequestName" placeholder="Enter requester name">
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
                        
                        <div class="row">
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
                        
                        <div class="row">
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
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
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
                                                    <select class="form-control" wire:model="transferProducts.{{ $index }}.product_id">
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $prod)
                                                            <option value="{{ $prod->id }}">{{ $prod->name }} ({{ $prod->sku_number }})</option>
                                                        @endforeach
                                                    </select>
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
                                    </table>
                                </div>
                                
                                @if(count($transferProducts) < $maxProducts)
                                    <button type="button" class="btn btn-sm btn-success" wire:click="addEmptyProduct">
                                        <i class="icon-plus"></i> Add Product
                                    </button>
                                @endif
                                
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
