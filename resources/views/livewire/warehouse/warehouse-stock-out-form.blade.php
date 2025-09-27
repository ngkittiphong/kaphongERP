<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="stockOut">
        <div class="row">
            <!-- Warehouse Selection -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Warehouse <span class="text-danger">*</span></label>
                    <select wire:model="warehouseId" class="form-control">
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">
                                {{ $warehouse->name }} ({{ $warehouse->branch->name_en ?? 'No Branch' }})
                            </option>
                        @endforeach
                    </select>
                    @error('warehouseId') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Product Selection -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Product <span class="text-danger">*</span></label>
                    <select wire:model="productId" class="form-control">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} ({{ $product->unit_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('productId') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        @if($selectedWarehouse && $selectedProduct)
        <div class="row">
            <div class="col-md-12">
                <div class="alert {{ $currentStockBalance > 0 ? 'alert-info' : 'alert-warning' }}">
                    <strong>Current Stock:</strong> {{ number_format($currentStockBalance) }} {{ $selectedProduct->unit_name }}
                    in {{ $selectedWarehouse->name }}
                    @if($currentStockBalance == 0)
                        <br><strong>Warning:</strong> No stock available for this product in the selected warehouse.
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Quantity -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" wire:model="quantity" class="form-control" placeholder="Enter quantity" min="1" step="1">
                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                    @if($currentStockBalance > 0)
                        <span class="help-block">Maximum available: {{ number_format($currentStockBalance) }}</span>
                    @endif
                </div>
            </div>

            <!-- Unit Price -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Unit Price</label>
                    <input type="number" wire:model="unitPrice" class="form-control" placeholder="Enter unit price" min="0" step="0.01">
                    @error('unitPrice') <span class="text-danger">{{ $message }}</span> @enderror
                    <span class="help-block">Optional: Update average buy price</span>
                </div>
            </div>

            <!-- Sale Price -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Sale Price</label>
                    <input type="number" wire:model="salePrice" class="form-control" placeholder="Enter sale price" min="0" step="0.01">
                    @error('salePrice') <span class="text-danger">{{ $message }}</span> @enderror
                    <span class="help-block">Optional: Update average sale price</span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Date -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Date</label>
                    <input type="date" wire:model="dateActivity" class="form-control">
                    @error('dateActivity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Detail -->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Detail/Notes</label>
                    <input type="text" wire:model="detail" class="form-control" placeholder="Optional notes (e.g., reason for stock out)">
                    @error('detail') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Stock Out Preview -->
        @if($quantity && $selectedProduct && $currentStockBalance > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="alert {{ ($quantity > $currentStockBalance) ? 'alert-danger' : 'alert-success' }}">
                    @if($quantity > $currentStockBalance)
                        <i class="icon-cross2"></i>
                        <strong>Insufficient Stock!</strong> 
                        You're trying to take out {{ number_format($quantity) }} but only {{ number_format($currentStockBalance) }} available.
                    @else
                        <i class="icon-checkmark3"></i>
                        <strong>Stock Out Preview:</strong>
                        <br>Taking out: {{ number_format($quantity) }} {{ $selectedProduct->unit_name }}
                        <br>Remaining after: {{ number_format($currentStockBalance - $quantity) }} {{ $selectedProduct->unit_name }}
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="text-right">
                    <button type="button" wire:click="resetForm" class="btn btn-default">
                        <i class="icon-reload-alt position-left"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
                        <i class="icon-minus-circle2 position-left"></i>
                        <span wire:loading.remove>Stock Out</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>