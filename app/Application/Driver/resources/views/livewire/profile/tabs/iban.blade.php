<div class="tab-pane fade p-0 border-0" id="iban-tab-pane"
     role="tabpanel" aria-labelledby="basicInfo-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateIban" enctype="multipart/form-data">
            <div class="row gy-3">
                <div class="row">
                    <div class="col-md-5">
                        <h4 class="text-center">{{ __('driver::home.Send a request to change the IBAN') }}</h4>
                        <div class="col-md-12">
                            <label for="bank_name" class="form-label text-default">{{ __('driver::home.bank_name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('bank_name') is-invalid @enderror"
                                   id="bank_name"
                                   value="{{ $this->bank_name }}"
                                   wire:model.defer="bank_name"
                                   placeholder="{{ __('driver::home.bank_name') }}">
                            @error('bank_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="beneficiary_full_name" class="form-label text-default">{{ __('driver::home.beneficiary_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('beneficiary_full_name') is-invalid @enderror"
                                   id="beneficiary_full_name"
                                   value="{{ $this->beneficiary_full_name }}"
                                   wire:model.defer="beneficiary_full_name"
                                   placeholder="{{ __('driver::home.beneficiary_full_name') }}">
                            @error('beneficiary_full_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="iban" class="form-label text-default">{{ __('driver::home.iban') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('iban') is-invalid @enderror"
                                   id="iban"
                                   value="{{ $this->iban }}"
                                   wire:model.defer="iban"
                                   placeholder="{{ __('driver::home.iban') }}">
                            @error('iban') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>


                        <div class="col-xl-6 mt-2">
                            <label for="iban_file" class="form-label text-black">@lang('driver::home.Iban File') <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('iban_file') is-invalid @enderror" id="iban_file"  wire:model.defer="iban_file">
                            <label for="iban_file" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                            @error("iban_file")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="col-xl-2 mt-2">
                            <a href="{{ $this?->iban_file_path }}" target="_blank">
                                @if(in_array(substr($this->driverIban?->iban_file, strpos($this->driverIban?->iban_file, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto" style="color: #0b5ee7">@lang('driver::home.show') @lang('driver::home.Iban File')</h5>
                                @else
                                    @if($this?->iban_file_path)

                                    <img src="{{ $this?->iban_file_path }}"
                                         class="img-fluid rounded-start" alt="@lang('driver::home.Iban File')" width="100" height="100">
                                    @endif
                                @endif
                            </a>
                        </div>

                        <label class="form-label mt-2 text-info">  {{ __('driver::home.iban_message') }} : <a href="mailto:IT@EATS.SA" style="color: #0a58ca">IT@EATS.SA</a> . </label>


                    </div>

                    <div class="col-md-1">

                    </div>

                    @if($this->driverTempIban)
                        <div class="col-md-5">
                        <h4 class="text-center">{{ __('driver::home.Current IBAN INFO') }}</h4>
                        <div class="col-md-12">
                            <label for="temp_bank_name" class="form-label text-default">{{ __('driver::home.bank_name') }}</label>
                            <input type="text"
                                   class="form-control form-control-lg @error('temp_bank_name') is-invalid @enderror"
                                   id="temp_bank_name"
                                   disabled
                                   value="{{ $this?->temp_bank_name }}"
                                   wire:model.defer="temp_bank_name"
                                   placeholder="{{ __('driver::home.bank_name') }}">
                            @error('temp_bank_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="temp_beneficiary_full_name" class="form-label text-default">{{ __('driver::home.beneficiary_full_name') }}</label>
                            <input type="text"
                                   class="form-control form-control-lg @error('temp_beneficiary_full_name') is-invalid @enderror"
                                   id="temp_beneficiary_full_name"
                                   disabled
                                   value="{{ $this->temp_beneficiary_full_name }}"
                                   wire:model.defer="temp_beneficiary_full_name"
                                   placeholder="{{ __('driver::home.beneficiary_full_name') }}">
                            @error('temp_beneficiary_full_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="temp_iban" class="form-label text-default">{{ __('driver::home.iban') }}</label>
                            <input type="text"
                                   class="form-control form-control-lg @error('temp_iban') is-invalid @enderror"
                                   id="temp_iban"
                                   disabled
                                   value="{{ $this->temp_iban }}"
                                   wire:model.defer="temp_iban"
                                   placeholder="{{ __('driver::home.iban') }}">
                            @error('temp_iban') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                            <div class="col-xl-2 mt-2">
                                <a href="{{ $this?->temp_iban_file_path }}" target="_blank">
                                    @if(in_array(substr($this?->driverTempIban->iban_file, strpos($this?->driverTempIban->iban_file, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                        <h5 class="mt-5 mx-auto" style="color: #0b5ee7">@lang('driver::home.show') @lang('driver::home.Iban File')</h5>
                                    @else
                                    <img src="{{ $this?->temp_iban_file_path }}"
                                         class="img-fluid rounded-start" alt="@lang('driver::home.Iban File')" width="100" height="100">
                                    @endif
                                </a>
                            </div>

                    </div>
                    @endif
                </div>
            </div>
            @DriverHasRole(['edit iban info_profile'])
            <div class="row gy-3 mt-3 @if($this?->temp_iban != $this?->driverIban?->iban) d-none @endif">
                <div class="col-xl-4 d-grid mt-4">
                    <button type="submit" class="btn btn-lg btn-primary">
                        <span class="indicator-label" wire:loading.class="d-none">
                            @lang('driver::home.Send')
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                            @lang('driver::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
            @endDriverHasRole
        </form>
    </div>

</div>
