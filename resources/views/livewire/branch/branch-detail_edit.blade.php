<!-----------------------------  Start Edit Branch Form    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h4 class="panel-title"><?= __('Edit Branch') ?> - {{ $branch->name_th ?? 'N/A' }}</h4>
                    <div class="elements">
                        <button type="button" class="btn btn-default" wire:click="$dispatch('refreshComponent')">
                            <i class="icon-arrow-left8"></i> <?= __('Back to Details') ?>
                        </button>
                    </div>
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
                    
                    <form wire:submit.prevent="updateBranch">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_company_id"><?= __('Company') ?> <span class="text-danger">*</span></label>
                                    <select wire:model="company_id" class="form-control" id="edit_company_id">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ $company->id == $branch->company_id ? 'selected' : '' }}>
                                                {{ $company->name_th }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_branch_code"><?= __('Branch Code') ?> <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="branch_code" class="form-control" id="edit_branch_code" placeholder="Enter branch code">
                                    @error('branch_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_name_th"><?= __('Branch Name (Thai)') ?> <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="name_th" class="form-control" id="edit_name_th" placeholder="Enter Thai name">
                                    @error('name_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_name_en"><?= __('Branch Name (English)') ?></label>
                                    <input type="text" wire:model="name_en" class="form-control" id="edit_name_en" placeholder="Enter English name">
                                    @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_address_th"><?= __('Address (Thai)') ?></label>
                                    <textarea wire:model="address_th" class="form-control" id="edit_address_th" rows="3" placeholder="Enter Thai address"></textarea>
                                    @error('address_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_address_en"><?= __('Address (English)') ?></label>
                                    <textarea wire:model="address_en" class="form-control" id="edit_address_en" rows="3" placeholder="Enter English address"></textarea>
                                    @error('address_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_bill_address_th"><?= __('Billing Address (Thai)') ?></label>
                                    <textarea wire:model="bill_address_th" class="form-control" id="edit_bill_address_th" rows="3" placeholder="Enter Thai billing address"></textarea>
                                    @error('bill_address_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_bill_address_en"><?= __('Billing Address (English)') ?></label>
                                    <textarea wire:model="bill_address_en" class="form-control" id="edit_bill_address_en" rows="3" placeholder="Enter English billing address"></textarea>
                                    @error('bill_address_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_post_code"><?= __('Postal Code') ?></label>
                                    <input type="text" wire:model="post_code" class="form-control" id="edit_post_code" placeholder="Enter postal code">
                                    @error('post_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_phone_country_code"><?= __('Phone Country Code') ?></label>
                                    <input type="text" wire:model="phone_country_code" class="form-control" id="edit_phone_country_code" placeholder="+66">
                                    @error('phone_country_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_phone_number"><?= __('Phone Number') ?></label>
                                    <input type="text" wire:model="phone_number" class="form-control" id="edit_phone_number" placeholder="Enter phone number">
                                    @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_fax"><?= __('Fax') ?></label>
                                    <input type="text" wire:model="fax" class="form-control" id="edit_fax" placeholder="Enter fax number">
                                    @error('fax') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_email"><?= __('Email') ?></label>
                                    <input type="email" wire:model="email" class="form-control" id="edit_email" placeholder="Enter email">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_website"><?= __('Website') ?></label>
                                    <input type="url" wire:model="website" class="form-control" id="edit_website" placeholder="Enter website URL">
                                    @error('website') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_contact_name"><?= __('Contact Name') ?></label>
                                    <input type="text" wire:model="contact_name" class="form-control" id="edit_contact_name" placeholder="Enter contact name">
                                    @error('contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_contact_email"><?= __('Contact Email') ?></label>
                                    <input type="email" wire:model="contact_email" class="form-control" id="edit_contact_email" placeholder="Enter contact email">
                                    @error('contact_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_contact_mobile"><?= __('Contact Mobile') ?></label>
                                    <input type="text" wire:model="contact_mobile" class="form-control" id="edit_contact_mobile" placeholder="Enter contact mobile">
                                    @error('contact_mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_latitude"><?= __('Latitude') ?></label>
                                    <input type="number" step="any" wire:model="latitude" class="form-control" id="edit_latitude" placeholder="Enter latitude">
                                    @error('latitude') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_longitude"><?= __('Longitude') ?></label>
                                    <input type="number" step="any" wire:model="longitude" class="form-control" id="edit_longitude" placeholder="Enter longitude">
                                    @error('longitude') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" wire:model.live="is_active" {{ $is_active ? 'checked' : '' }}> <?= __('Active Branch') ?>
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
                                <i class="icon-arrow-left8"></i> <?= __('Back to Details') ?>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-checkmark4"></i> <?= __('Update Branch') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------  End Edit Branch Form ------------------------->
