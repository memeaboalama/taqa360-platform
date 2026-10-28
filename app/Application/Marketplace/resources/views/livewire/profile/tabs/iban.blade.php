<div class="tab-pane fade p-0 border-0" id="iban-tab-pane"
     role="tabpanel" aria-labelledby="basicInfo-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateIban" enctype="multipart/form-data">
            @php
                $hasIban = (bool)($this->merchantIban ?? $this->merchantTempIban);
                $curBankName = $this->merchantIban?->bank_name ?? $this->temp_bank_name;
                $curBeneficiary = $this->merchantIban?->beneficiary_full_name ?? $this->temp_beneficiary_full_name;
                $curIban = $this->merchantIban?->iban ?? $this->temp_iban;
                $curFilePath = $this->merchantIban?->iban_file_path ?? $this->temp_iban_file_path;
            @endphp

            @if(!$hasIban)
                <div class="row gy-3">
                    <div class="col-md-6">
                        <h4 class="text-center">{{ __('marketplace::home.Send a request to change the IBAN') }}</h4>
                        <div class="col-md-12">
                            <label for="bank_name" class="form-label text-default">{{ __('marketplace::home.bank_name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('bank_name') is-invalid @enderror"
                                   id="bank_name"
                                   value="{{ $this->bank_name }}"
                                   wire:model.defer="bank_name"
                                   placeholder="{{ __('marketplace::home.bank_name') }}">
                            @error('bank_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="beneficiary_full_name" class="form-label text-default">{{ __('marketplace::home.beneficiary_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('beneficiary_full_name') is-invalid @enderror"
                                   id="beneficiary_full_name"
                                   value="{{ $this->beneficiary_full_name }}"
                                   wire:model.defer="beneficiary_full_name"
                                   placeholder="{{ __('marketplace::home.beneficiary_full_name') }}">
                            @error('beneficiary_full_name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="iban" class="form-label text-default">{{ __('marketplace::home.iban') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('iban') is-invalid @enderror"
                                   id="iban"
                                   value="{{ $this->iban }}"
                                   wire:model.defer="iban"
                                   placeholder="{{ __('marketplace::home.iban') }}">
                            @error('iban') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-xl-6 mt-2">
                            <label for="iban_file" class="form-label text-black">@lang('marketplace::home.Iban File') <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('iban_file') is-invalid @enderror" id="iban_file"  wire:model.defer="iban_file">
                            <label for="iban_file" class="form-label mt-2 text-danger">Max size : 1 MB , 250 px * 250 px</label>
                            @error("iban_file")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                @MerchantHasRole(['edit iban info_profile'])
                <div class="row gy-3 mt-3">
                    <div class="col-xl-4 d-grid mt-4">
                        <button type="submit" class="btn btn-lg btn-primary" wire:loading.attr="disabled" wire:target="updateIban">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="updateIban">
                                @lang('marketplace::home.Send')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="updateIban">
                                @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
                @endMerchantHasRole
            @else
                <div class="row gy-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-default fw-semibold mb-1">{{ __('marketplace::home.bank_name') }}</label>
                            <p class="fs-6 mb-0 border-bottom pb-2">{{ $curBankName }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-default fw-semibold mb-1">{{ __('marketplace::home.beneficiary_full_name') }}</label>
                            <p class="fs-6 mb-0 border-bottom pb-2">{{ $curBeneficiary }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-default fw-semibold mb-1">{{ __('marketplace::home.iban') }}</label>
                            <p class="fs-6 mb-0 border-bottom pb-2">{{ $curIban }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        @if($curFilePath)
                            <a href="{{ $curFilePath }}" target="_blank">
                                <img src="{{ $curFilePath }}" class="img-fluid rounded-3 border" alt="@lang('marketplace::home.Iban File')">
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <label class="form-label mt-3 text-info">  {{ __('marketplace::home.iban_message') }} : <a href="mailto:{{config('mail.from.address')}}" style="color: #0a58ca">{{config('mail.from.address')}}</a> . </label>
        </form>
    </div>

</div>
