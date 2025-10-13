<!-----------------------------  Start Add Warehouse Form    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h4 class="panel-title">{{ __t('warehouse.add_new_warehouse', 'Add New Warehouse') }}</h4>
                    <div class="elements">
                        <button class="btn btn-success" wire:click="saveWarehouse">
                            <i class="icon-checkmark"></i> Save Warehouse
                        </button>
                        <button class="btn btn-default" wire:click="cancelForm">
                            <i class="icon-cross"></i> Cancel
                        </button>
                    </div>
                    <a class="elements-toggle"><i class="icon-more"></i></a>
                </div>
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit.prevent="saveWarehouse">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('warehouse.branch', 'Branch') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="branch_id" required>
                                        <option value="">{{ __t('warehouse.select_branch', 'Select Branch') }}</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name_en }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Hidden field for user creator - automatically set to current user --}}
                                <input type="hidden" wire:model="user_create_id">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('warehouse.warehouse_name', 'Warehouse Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="name" placeholder="{{ __t('warehouse.enter_warehouse_name', 'Enter warehouse name') }}" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Hidden field for creation date - automatically set to today --}}
                                <input type="hidden" wire:model="date_create">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {{-- Hidden field for status - automatically set to active --}}
                                <input type="hidden" wire:model="warehouse_status_id">
                            </div>
                            <div class="col-md-6">
                                {{-- Hidden field for average remain price - automatically set to 0 --}}
                                <input type="hidden" wire:model="avr_remain_price">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {{-- Hidden field for main warehouse - automatically set to false --}}
                                <input type="hidden" wire:model="main_warehouse">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="icon-checkmark"></i> Save Warehouse
                                    </button>
                                    <button type="button" class="btn btn-default" wire:click="cancelForm">
                                        <i class="icon-cross"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------  End Add Warehouse Form ------------------------->
