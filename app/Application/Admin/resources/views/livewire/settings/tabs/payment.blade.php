<div class="tab-pane  show fade p-0 border-0" id="PaymentSettings-tab-pane"
     role="tabpanel" aria-labelledby="OrdersSettings-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateOrdersSeetings">
            <div class="row gy-3">
                <div class="col-xl-4">
                    <label for="visa_commission" class="form-label text-default">{{ __('admin::home.Visa') }} <span class="text-danger">*</span></label>
                    <input  type="number"
                            step="0.05"
                            min="0.00"
                            class="form-control form-control-lg @error('visa_commission') is-invalid @enderror"
                            id="visa_commission"
                            value="{{ $this->visa_commission }}"
                            wire:model.defer="visa_commission"
                            placeholder="0.00">
                    @error('visa_commission') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-4">
                    <label for="master_commission" class="form-label text-default">{{ __('admin::home.Master Card') }} <span class="text-danger">*</span></label>
                    <input  type="number"
                            step="0.05"
                            min="0.00"
                            class="form-control form-control-lg @error('master_commission') is-invalid @enderror"
                            id="master_commission"
                            value="{{ $this->master_commission }}"
                            wire:model.defer="master_commission"
                            placeholder="0.00">
                    @error('master_commission') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-xl-4">
                    <label for="mada_commission" class="form-label text-default">{{ __('admin::home.Mada') }} <span class="text-danger">*</span></label>
                    <input  type="number"
                            step="0.05"
                            min="0.00"
                            class="form-control form-control-lg @error('mada_commission') is-invalid @enderror"
                            id="mada_commission"
                            value="{{ $this->mada_commission }}"
                            wire:model.defer="mada_commission"
                            placeholder="0.00">
                    @error('mada_commission') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <!-- <div class="col-xl-2">
                    <label for="apple_commission" class="form-label text-default">{{ __('admin::home.Apple Pay') }}</label>
                    <input  type="number"
                            step="0.05"
                            min="0.00"
                            class="form-control form-control-lg @error('apple_commission') is-invalid @enderror"
                            id="apple_commission"
                            value="{{ $this->apple_commission }}"
                            wire:model.defer="apple_commission"
                            placeholder="0.00">
                    @error('apple_commission') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-xl-2">
                    <label for="stc_commission" class="form-label text-default">{{ __('admin::home.STC Pay') }}</label>
                    <input  type="number"
                            step="0.05"
                            min="0.00"
                            class="form-control form-control-lg @error('stc_commission') is-invalid @enderror"
                            id="stc_commission"
                            value="{{ $this->stc_commission }}"
                            wire:model.defer="stc_commission"
                            placeholder="0.00">
                    @error('stc_commission') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div> -->
            </div>
            @AdminHasRole(['edit profile_settings'])
            <div class="row gy-3 mt-3">
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-md btn-primary px-4">
                        <span class="indicator-label" wire:loading.class="d-none">
                            @lang('admin::home.Save')
                        </span>
                        <span class="d-none" wire:loading.class.remove="d-none">
                            @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
            @endAdminHasRole

            
        </form>
    </div>

</div>
