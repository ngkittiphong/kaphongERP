<!-----------------------------  Start Add Branch Form    -------------------------->
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="row p-l-10 p-r-10 panel panel-flat">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        {{ __t('branch.add_new_branch', 'Add New Branch') }}
                        @if($candidateBranchCode)
                            <span class="text-muted">({{ $candidateBranchCode }})</span>
                        @endif
                    </h4>
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
                    
                    @if ($errors->has('general'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            @foreach ($errors->get('general') as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>  
                    @endif
                    
                    <form wire:submit.prevent="saveBranch">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">{{ __t('branch.company', 'Company') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_name" value="{{ $companyNameTh }}" readonly>
                                    <input type="hidden" wire:model="company_id">
                                    @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch_code">{{ __t('branch.branch_code', 'Branch Code') }} <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="branch_code" class="form-control" id="branch_code" placeholder="{{ __t('branch.enter_branch_code', 'Enter branch code') }}">
                                    @error('branch_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_th">{{ __t('branch.branch_name_th', 'Branch Name (Thai)') }} <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="name_th" class="form-control" id="name_th" placeholder="{{ __t('branch.enter_thai_name', 'Enter Thai name') }}">
                                    @error('name_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_en">{{ __t('branch.branch_name_en', 'Branch Name (English)') }}</label>
                                    <input type="text" wire:model="name_en" class="form-control" id="name_en" placeholder="{{ __t('branch.enter_english_name', 'Enter English name') }}">
                                    @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_th">{{ __t('branch.address_th', 'Address (Thai)') }}</label>
                                    <textarea wire:model="address_th" class="form-control" id="address_th" rows="3" placeholder="{{ __t('branch.enter_thai_address', 'Enter Thai address') }}"></textarea>
                                    @error('address_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_en">{{ __t('branch.address_en', 'Address (English)') }}</label>
                                    <textarea wire:model="address_en" class="form-control" id="address_en" rows="3" placeholder="{{ __t('branch.enter_english_address', 'Enter English address') }}"></textarea>
                                    @error('address_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bill_address_th">{{ __t('branch.billing_address_th', 'Billing Address (Thai)') }}</label>
                                    <textarea wire:model="bill_address_th" class="form-control" id="bill_address_th" rows="3" placeholder="{{ __t('branch.enter_thai_billing_address', 'Enter Thai billing address') }}"></textarea>
                                    @error('bill_address_th') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bill_address_en">{{ __t('branch.billing_address_en', 'Billing Address (English)') }}</label>
                                    <textarea wire:model="bill_address_en" class="form-control" id="bill_address_en" rows="3" placeholder="{{ __t('branch.enter_english_billing_address', 'Enter English billing address') }}"></textarea>
                                    @error('bill_address_en') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="post_code">{{ __t('branch.postal_code', 'Postal Code') }}</label>
                                    <input type="text" wire:model="post_code" class="form-control" id="post_code" placeholder="{{ __t('branch.enter_postal_code', 'Enter postal code') }}">
                                    @error('post_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_country_code">{{ __t('branch.phone_country_code', 'Phone Country Code') }}</label>
                                    <input type="text" wire:model="phone_country_code" class="form-control" id="phone_country_code" placeholder="{{ __t('branch.phone_country_code_placeholder', '+66') }}">
                                    @error('phone_country_code') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_number">{{ __t('branch.phone_number', 'Phone Number') }}</label>
                                    <input type="text" wire:model="phone_number" class="form-control" id="phone_number" placeholder="{{ __t('branch.enter_phone_number', 'Enter phone number') }}">
                                    @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fax">{{ __t('branch.fax', 'Fax') }}</label>
                                    <input type="text" wire:model="fax" class="form-control" id="fax" placeholder="{{ __t('branch.enter_fax_number', 'Enter fax number') }}">
                                    @error('fax') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">{{ __t('branch.email', 'Email') }}</label>
                                    <input type="email" wire:model="email" class="form-control" id="email" placeholder="{{ __t('branch.enter_email', 'Enter email') }}">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="website">{{ __t('branch.website', 'Website') }}</label>
                                    <input type="url" wire:model="website" class="form-control" id="website" placeholder="{{ __t('branch.enter_website_url', 'Enter website URL') }}">
                                    @error('website') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_name">{{ __t('branch.contact_name', 'Contact Name') }}</label>
                                    <input type="text" wire:model="contact_name" class="form-control" id="contact_name" placeholder="{{ __t('branch.enter_contact_name', 'Enter contact name') }}">
                                    @error('contact_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_email">{{ __t('branch.contact_email', 'Contact Email') }}</label>
                                    <input type="email" wire:model="contact_email" class="form-control" id="contact_email" placeholder="{{ __t('branch.enter_contact_email', 'Enter contact email') }}">
                                    @error('contact_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_mobile">{{ __t('branch.contact_mobile', 'Contact Mobile') }}</label>
                                    <input type="text" wire:model="contact_mobile" class="form-control" id="contact_mobile" placeholder="{{ __t('branch.enter_contact_mobile', 'Enter contact mobile') }}">
                                    @error('contact_mobile') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude">{{ __t('branch.latitude', 'Latitude') }}</label>
                                    <input type="number" step="any" wire:model="latitude" class="form-control" id="latitude" placeholder="{{ __t('branch.enter_latitude', 'Enter latitude') }}">
                                    @error('latitude') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude">{{ __t('branch.longitude', 'Longitude') }}</label>
                                    <input type="number" step="any" wire:model="longitude" class="form-control" id="longitude" placeholder="{{ __t('branch.enter_longitude', 'Enter longitude') }}">
                                    @error('longitude') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Status is set to Active (1) by default on create; hidden in UI -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="checkbox" style="display: none;">
                                        <label>
                                            <input type="checkbox" wire:model.live="is_head_office" {{ $is_head_office ? 'checked' : '' }}> {{ __t('branch.head_office', 'Head Office') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-default" wire:click="cancelForm">
                                <i class="icon-arrow-left8"></i> {{ __t('common.cancel', 'Cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-checkmark4"></i> {{ __t('branch.save_branch', 'Save Branch') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------------------------  End Add Branch Form ------------------------->
