<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Stock Operations</h5>
    </div>
    <div class="card-body">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form wire:submit.prevent="submit">
            <!-- Operation Type Selection -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Operation Type</label>
                    <select wire:model.live="operationType" class="form-select">
                        <option value="stock_in">Stock In</option>
                        <option value="stock_out">Stock Out</option>
                        <option value="adjustment">Stock Adjustment</option>
                        <option value="transfer">Transfer Stock</option>
                    </select>
                </div>
            </div>

            <!-- Warehouse Selection -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">
                        @if($operationType === 'transfer')
                            From Warehouse
                        @else
                            Warehouse
                        @endif
                    </label>
                    <select wire:model.live="warehouseId" class="form-select">
                        <option value="">Select Warehouse</option>
                        @foreach($availableWarehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">
                                {{ $warehouse->name }} ({{ $warehouse->branch->name_en ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($operationType === 'transfer')
                <div class="col-md-6">
                    <label class="form-label">To Warehouse</label>
                    <select wire:model.live="toWarehouseId" class="form-select">
                        <option value="">Select Destination Warehouse</option>
                        @foreach($availableWarehouses as $warehouse)
                            @if($warehouse->id != $fromWarehouseId)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }} ({{ $warehouse->branch->name_en ?? 'N/A' }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <!-- Product Selection -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Product</label>
                    <select wire:model.live="productId" class="form-select">
                        <option value="">Select Product</option>
                        @foreach($availableProducts as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} ({{ $product->sku_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Current Balance Display -->
            @if($warehouseId && $productId)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong>Current Stock Balance:</strong> {{ $currentBalance }} units
                    </div>
                </div>
            </div>
            @endif

            <!-- Quantity Input -->
            <div class="row mb-3">
                <div class="col-md-6">
                    @if($operationType === 'adjustment')
                        <label class="form-label">New Quantity</label>
                        <input type="number" 
                               wire:model="newQuantity" 
                               class="form-control" 
                               min="0" 
                               step="1" 
                               pattern="[0-9]+"
                               inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               title="Only integer numbers allowed"
                               placeholder="Enter new quantity">
                    @else
                        <label class="form-label">Quantity</label>
                        <input type="number" 
                               wire:model="quantity" 
                               class="form-control" 
                               min="1" 
                               step="1" 
                               pattern="[0-9]+"
                               inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               title="Only integer numbers allowed"
                               placeholder="Enter quantity">
                    @endif
                </div>

                @if($operationType === 'stock_in' || $operationType === 'transfer')
                <div class="col-md-3">
                    <label class="form-label">Unit Price</label>
                    <input type="number" wire:model="unitPrice" class="form-control" step="0.01" min="0" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sale Price</label>
                    <input type="number" wire:model="salePrice" class="form-control" step="0.01" min="0" placeholder="0.00">
                </div>
                @endif
            </div>

            <!-- Transfer Slip ID for Transfer -->
            @if($operationType === 'transfer')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Transfer Slip ID (Optional)</label>
                    <input type="text" wire:model="transferSlipId" class="form-control" placeholder="Enter transfer slip ID">
                </div>
            </div>
            @endif

            <!-- Detail/Notes -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Details/Notes</label>
                    <textarea wire:model="detail" class="form-control" rows="2" placeholder="Enter details or notes"></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-md-12">
                    @if($operationType === 'stock_in')
                        <button type="button" wire:click="stockIn" class="btn btn-success">
                            <i class="fas fa-plus"></i> Stock In
                        </button>
                    @elseif($operationType === 'stock_out')
                        <button type="button" wire:click="stockOut" class="btn btn-danger">
                            <i class="fas fa-minus"></i> Stock Out
                        </button>
                    @elseif($operationType === 'adjustment')
                        <button type="button" wire:click="stockAdjustment" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Adjust Stock
                        </button>
                    @elseif($operationType === 'transfer')
                        <button type="button" wire:click="transferStock" class="btn btn-primary">
                            <i class="fas fa-exchange-alt"></i> Transfer Stock
                        </button>
                    @endif

                    <button type="button" wire:click="$dispatch('warehouseUpdated', {warehouseId: {{ $warehouseId ?? 'null' }}})" class="btn btn-secondary ms-2">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
