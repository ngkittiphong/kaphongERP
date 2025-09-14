<!-----------------------------  Start Add Branch Form    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h4 class="panel-title"><?= __('Add New Branch') ?></h4>
                </div>
                <div class="panel-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('message') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form wire:submit.prevent="saveBranch">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_id"><?= __('Company') ?> <span class="text-danger">*</span></label>
                                    <select wire:model="company_id" class="form-control" id="company_id">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name_th }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch_code"><?= __('Branch Code') ?> <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="branch_code" class="form-control" id="branch_code" placeholder="Enter branch code">
                                    @error('branch_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_th"><?= __('Branch Name (Thai)') ?> <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="name_th" class="form-control" id="name_th" placeholder="Enter Thai name">
                                    @error('name_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_en"><?= __('Branch Name (English)') ?></label>
                                    <input type="text" wire:model="name_en" class="form-control" id="name_en" placeholder="Enter English name">
                                    @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_th"><?= __('Address (Thai)') ?></label>
                                    <textarea wire:model="address_th" class="form-control" id="address_th" rows="3" placeholder="Enter Thai address"></textarea>
                                    @error('address_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_en"><?= __('Address (English)') ?></label>
                                    <textarea wire:model="address_en" class="form-control" id="address_en" rows="3" placeholder="Enter English address"></textarea>
                                    @error('address_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number"><?= __('Phone Number') ?></label>
                                    <input type="text" wire:model="phone_number" class="form-control" id="phone_number" placeholder="Enter phone number">
                                    @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email"><?= __('Email') ?></label>
                                    <input type="email" wire:model="email" class="form-control" id="email" placeholder="Enter email">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_name"><?= __('Contact Name') ?></label>
                                    <input type="text" wire:model="contact_name" class="form-control" id="contact_name" placeholder="Enter contact name">
                                    @error('contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_mobile"><?= __('Contact Mobile') ?></label>
                                    <input type="text" wire:model="contact_mobile" class="form-control" id="contact_mobile" placeholder="Enter contact mobile">
                                    @error('contact_mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" wire:model.live="is_active" {{ $is_active ? 'checked' : '' }}> <?= __('Active') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" wire:model.live="is_head_office" {{ $is_head_office ? 'checked' : '' }}> <?= __('Head Office') ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-default" wire:click="cancelForm">
                                <i class="icon-arrow-left8"></i> <?= __('Cancel') ?>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-checkmark4"></i> <?= __('Save Branch') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------  End Add Branch Form ------------------------->
