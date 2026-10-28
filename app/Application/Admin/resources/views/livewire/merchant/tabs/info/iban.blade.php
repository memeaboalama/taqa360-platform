<div >

    <div class="mt-4">
            <div class="row gy-3">
                <div class="row">
                    <div class="col-md-5">
                        <h3 class="text-center">Current IBAN INFO</h3>
                        <form method="POST" wire:submit.prevent="updateInfo">
                            <div class="col-md-12">
                                <label for="current_bank_name" class="form-label text-default">{{ __('admin::home.bank_name') }}</label>
                                <input type="text"
                                       class="form-control form-control-lg @error('current_bank_name') is-invalid @enderror"
                                       id="current_bank_name"
                                       value="{{ $this?->current_bank_name }}"
                                       wire:model.defer="current_bank_name"
                                       placeholder="{{ __('admin::home.bank_name') }}">
                                @error('current_bank_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="current_beneficiary_full_name" class="form-label text-default">{{ __('admin::home.beneficiary_full_name') }}</label>
                                <input type="text"
                                       class="form-control form-control-lg @error('current_beneficiary_full_name') is-invalid @enderror"
                                       id="current_beneficiary_full_name"
                                       value="{{ $this->current_beneficiary_full_name }}"
                                       wire:model.defer="current_beneficiary_full_name"
                                       placeholder="{{ __('admin::home.beneficiary_full_name') }}">
                                @error('current_beneficiary_full_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="current_iban" class="form-label text-default">{{ __('admin::home.iban') }}</label>
                                <input type="text"
                                       class="form-control form-control-lg @error('current_iban') is-invalid @enderror"
                                       id="current_iban"
                                       value="{{ $this->current_iban }}"
                                       wire:model.defer="current_iban"
                                       placeholder="{{ __('admin::home.iban') }}">
                                @error('current_iban') <span class="d-block text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-xl-6 mt-2">
                                <label for="iban_file" class="form-label text-black">Iban File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('iban_file') is-invalid @enderror" id="iban_file"  wire:model.defer="iban_file">
                                <label for="iban_file" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                                @error("iban_file")
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xl-2 mt-2">
                                <a href="{{ $this->current_iban_file_path }}" target="_blank">
                                    @if(in_array(substr($this->merchantIban?->iban_file, strpos($this->merchantIban?->iban_file, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                        <h5 class="mt-5 mx-auto" style="color: #0b5ee7">@lang('marketplace::home.show') @lang('marketplace::home.Iban File')</h5>
                                    @else
                                        <img src="{{ $this->current_iban_file_path }}"
                                             class="img-fluid rounded-start" alt="@lang('marketplace::home.Iban File')" width="100" height="100">
                                    @endif
                                </a>
                            </div>

                            <div class="row gy-3 mt-3">
                                <div class="col-xl-4 d-grid mt-4">
                                    <button class="btn btn-lg btn-success">{{ __('admin::home.update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-1">

                    </div>

                    @if($merchantTempIban)

                            <div class="col-md-5">
                            <h3 class="text-center">Requested IBAN INFO</h3>
                                <form method="POST" wire:submit.prevent="acceptChanges">
                                    <div class="col-md-12">
                                        <label for="bank_name" class="form-label text-default">{{ __('admin::home.bank_name') }}</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('bank_name') is-invalid @enderror"
                                               id="bank_name"
                                               readonly
                                               value="{{ $this->bank_name }}"
                                               wire:model.defer="bank_name"
                                               placeholder="{{ __('admin::home.bank_name') }}">
                                        @error('bank_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label for="beneficiary_full_name" class="form-label text-default">{{ __('admin::home.beneficiary_full_name') }}</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('beneficiary_full_name') is-invalid @enderror"
                                               id="beneficiary_full_name"
                                               readonly
                                               value="{{ $this->beneficiary_full_name }}"
                                               wire:model.defer="beneficiary_full_name"
                                               placeholder="{{ __('admin::home.beneficiary_full_name') }}">
                                        @error('beneficiary_full_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <label for="iban" class="form-label text-default">{{ __('admin::home.iban') }}</label>
                                        <input type="text"
                                               class="form-control form-control-lg @error('iban') is-invalid @enderror"
                                               id="iban"
                                               readonly
                                               value="{{ $this->iban }}"
                                               wire:model.defer="iban"
                                               placeholder="{{ __('admin::home.iban') }}">
                                        @error('iban') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                    </div>

                                    <div class="col-xl-2 mt-2">
                                        <a href="{{ $this->iban_file_path }}" target="_blank">
                                            @if(in_array(substr($this->merchantTempIban?->iban_file, strpos($this->merchantTempIban?->iban_file, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                                <h5 class="mt-5 mx-auto" style="color: #0b5ee7">@lang('marketplace::home.show') @lang('marketplace::home.Iban File')</h5>
                                            @else
                                                <img src="{{ $this->iban_file_path }}"
                                                     class="img-fluid rounded-start" alt="@lang('marketplace::home.Iban File')" width="100" height="100">
                                            @endif
                                        </a>
                                    </div>


                                    <div class="row gy-3 mt-3">
                                        <div class="col-xl-4 d-grid mt-4">
                                            <button class="btn btn-lg btn-success">{{ __('admin::home.accept') }}</button>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    @endif
                </div>
            </div>
    </div>

</div>
