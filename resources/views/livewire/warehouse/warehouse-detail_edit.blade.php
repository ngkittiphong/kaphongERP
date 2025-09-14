<!-----------------------------  Start Edit Warehouse Form    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h4 class="panel-title"><?= __('Edit Warehouse') ?></h4>
                    <div class="elements">
                        <button class="btn btn-success" wire:click="updateWarehouse">
                            <i class="icon-checkmark"></i> Update Warehouse
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

                    <form wire:submit.prevent="updateWarehouse">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Branch') ?> <span class="text-danger">*</span></label>
                                    <select class="form-control" wire:model="branch_id" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name_th }} ({{ $branch->name_en }})</option>
                                        @endforeach
                                    </select>
                                    @error('branch_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Warehouse Code') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="warehouse_code" placeholder="Enter warehouse code" required>
                                    @error('warehouse_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Thai Name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" wire:model="name_th" placeholder="Enter Thai name" required>
                                    @error('name_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('English Name') ?></label>
                                    <input type="text" class="form-control" wire:model="name_en" placeholder="Enter English name">
                                    @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Thai Address') ?></label>
                                    <textarea class="form-control" wire:model="address_th" rows="3" placeholder="Enter Thai address"></textarea>
                                    @error('address_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('English Address') ?></label>
                                    <textarea class="form-control" wire:model="address_en" rows="3" placeholder="Enter English address"></textarea>
                                    @error('address_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Phone Number') ?></label>
                                    <input type="text" class="form-control" wire:model="phone_number" placeholder="Enter phone number">
                                    @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Email') ?></label>
                                    <input type="email" class="form-control" wire:model="email" placeholder="Enter email address">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Contact Name') ?></label>
                                    <input type="text" class="form-control" wire:model="contact_name" placeholder="Enter contact name">
                                    @error('contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Contact Mobile') ?></label>
                                    <input type="text" class="form-control" wire:model="contact_mobile" placeholder="Enter contact mobile">
                                    @error('contact_mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Contact Email') ?></label>
                                    <input type="email" class="form-control" wire:model="contact_email" placeholder="Enter contact email">
                                    @error('contact_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><?= __('Description') ?></label>
                                    <textarea class="form-control" wire:model="description" rows="3" placeholder="Enter description"></textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" wire:model="is_active" value="1">
                                            <?= __('Active') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" wire:model="is_main_warehouse" value="1">
                                            <?= __('Main Warehouse') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="icon-checkmark"></i> Update Warehouse
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
<!------------------------------------  End Edit Warehouse Form ------------------------->
