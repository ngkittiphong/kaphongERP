<!-- Add Warehouse Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="addWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWarehouseModalLabel">
                    <i class="icon-plus-circle2"></i> {{ __t('warehouse.add_warehouse', 'Add Warehouse') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="cancelWarehouseForm">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form wire:submit.prevent="createWarehouse">
                <div class="modal-body">
                    <!-- Error Messages -->
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> {{ __t('common.error', 'Error') }}!</h4>
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    @if ($errors->has('warehouseName'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> {{ __t('common.error', 'Error') }}!</h4>
                            {{ $errors->first('warehouseName') }}
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> {{ __t('common.success', 'Success') }}!</h4>
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="warehouseName" class="control-label">
                            {{ __t('warehouse.warehouse_name', 'Warehouse Name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('warehouseName') is-invalid @enderror" 
                               id="warehouseName" 
                               wire:model="warehouseName"
                               placeholder="{{ __t('warehouse.enter_warehouse_name', 'Enter warehouse name') }}"
                               autocomplete="off">
                        @error('warehouseName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Branch Info Display -->
                    @if($branch)
                        <div class="form-group">
                            <label class="control-label">{{ __t('branch.branch', 'Branch') }}</label>
                            <div class="form-control-static">
                                <strong>{{ $branch->name_th ?? $branch->name_en ?? 'N/A' }}</strong>
                                @if($branch->branch_code)
                                    <span class="text-muted">({{ $branch->branch_code }})</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="cancelWarehouseForm">
                        <i class="icon-cross2"></i> {{ __t('common.cancel', 'Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="createWarehouse">
                            <i class="icon-checkmark2"></i> {{ __t('common.save', 'Save') }}
                        </span>
                        <span wire:loading wire:target="createWarehouse">
                            <i class="icon-spinner2 spinner"></i> {{ __t('common.saving', 'Saving') }}...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

