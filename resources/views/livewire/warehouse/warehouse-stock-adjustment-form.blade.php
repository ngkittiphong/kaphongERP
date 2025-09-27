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

    <form wire:submit.prevent="stockAdjustment">
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
                <div class="alert alert-info">
                    <strong>Current Stock:</strong> {{ number_format($currentStockBalance) }} {{ $selectedProduct->unit_name }}
                    in {{ $selectedWarehouse->name }}
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- New Quantity -->
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">New Quantity <span class="text-danger">*</span></label>
                    <input type="number" wire:model="newQuantity" class="form-control" placeholder="Enter new quantity" min="0" step="1">
                    @error('newQuantity') <span class="text-danger">{{ $message }}</span> @enderror
                    <span class="help-block">Set the exact quantity that should be in stock</span>
                </div>
            </div>

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
                    <input type="text" wire:model="detail" class="form-control" placeholder="Reason for adjustment (e.g., Physical count, Damage, Loss)">
                    @error('detail') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Adjustment Preview -->
        @if($newQuantity !== null && $newQuantity !== '' && $selectedProduct)
        <div class="row">
            <div class="col-md-12">
                <div class="alert {{ $adjustmentDifference == 0 ? 'alert-info' : ($adjustmentDifference > 0 ? 'alert-success' : 'alert-warning') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Current Stock:</strong><br>
                            {{ number_format($currentStockBalance) }} {{ $selectedProduct->unit_name }}
                        </div>
                        <div class="col-md-4">
                            <strong>New Stock:</strong><br>
                            {{ number_format($newQuantity) }} {{ $selectedProduct->unit_name }}
                        </div>
                        <div class="col-md-4">
                            <strong>Adjustment:</strong><br>
                            @if($adjustmentDifference > 0)
                                <span class="text-success">+{{ number_format($adjustmentDifference) }}</span> {{ $selectedProduct->unit_name }}
                                <br><small class="text-muted">(Increase)</small>
                            @elseif($adjustmentDifference < 0)
                                <span class="text-danger">{{ number_format($adjustmentDifference) }}</span> {{ $selectedProduct->unit_name }}
                                <br><small class="text-muted">(Decrease)</small>
                            @else
                                <span class="text-muted">No Change</span>
                                <br><small class="text-info">Will create adjustment record with 0 difference</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Warning for significant changes -->
        @if($adjustmentDifference != 0 && abs($adjustmentDifference) > 100)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <i class="icon-warning22"></i>
                    <strong>Large Adjustment Detected!</strong>
                    You're making a significant change of {{ number_format(abs($adjustmentDifference)) }} units. 
                    Please ensure this is correct and provide a detailed reason.
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
                    <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                        <i class="icon-wrench position-left"></i>
                        <span wire:loading.remove>Adjust Stock</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>