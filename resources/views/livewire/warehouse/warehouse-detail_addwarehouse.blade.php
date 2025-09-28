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
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name_en }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('warehouse.user_creator', 'User Creator') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="user_create_id" required>
                                        <option value="">Select User</option>
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_create_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('warehouse.warehouse_name', 'Warehouse Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="name" placeholder="Enter warehouse name" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('warehouse.creation_date', 'Creation Date') }} <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" wire:model="date_create" required>
                                    @error('date_create') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('common.status', 'Status') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="warehouse_status_id" required>
                                        <option value="">Select Status</option>
                                        @foreach($warehouse_statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_status_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ __t('warehouse.average_remain_price', 'Average Remain Price') }}</label>
                                    <input type="number" class="form-control" wire:model="avr_remain_price" placeholder="0.00" step="0.01" min="0">
                                    @error('avr_remain_price') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" wire:model="main_warehouse" value="1">
                                            {{ __t('warehouse.main_warehouse', 'Main Warehouse') }}
                                        </label>
                                    </div>
                                </div>
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
