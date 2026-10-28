<div class="tab-pane show fade p-0 border-0" id="DeliveryFees-tab-pane"
     role="tabpanel" aria-labelledby="DeliveryFees-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateSearchCapacity">

            <div class="row gy-3">
                <div class="col-xl-3">
                    <label for="vat" class="form-label text-default">{{ __('admin::home.vat') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.05"
                           min="0.00"
                           class="form-control form-control-lg @error('vat') is-invalid @enderror"
                           id="vat"
                           value="{{ $this->vat }}"
                           wire:model.defer="vat"
                           placeholder="0.00">
                    @error('vat') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-3">
                    <label for="delivery_km" class="form-label text-default">{{ __('admin::home.Delivery Default kilometers') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.05"
                           min="0.00"
                           class="form-control form-control-lg @error('delivery_km') is-invalid @enderror"
                           id="delivery_km"
                           value="{{ $this->delivery_km }}"
                           wire:model.defer="delivery_km"
                           placeholder="0.00">
                    @error('delivery_km') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-3">
                    <label for="delivery_fees" class="form-label text-default">{{ __('admin::home.Delivery Default Fees for Default kilometers') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.05"
                           min="0.00"
                           class="form-control form-control-lg @error('delivery_fees') is-invalid @enderror"
                           id="delivery_fees"
                           value="{{ $this->delivery_fees }}"
                           wire:model.defer="delivery_fees"
                           placeholder="0.00">
                    @error('delivery_fees') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-3">
                    <label for="extra_km_fees" class="form-label text-default">{{ __('admin::home.Extra Delivery Fees for every kilometer') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.05"
                           min="0.00"
                           class="form-control form-control-lg @error('extra_km_fees') is-invalid @enderror"
                           id="extra_km_fees"
                           value="{{ $this->extra_km_fees }}"
                           wire:model.defer="extra_km_fees"
                           placeholder="0.00">
                    @error('extra_km_fees') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @AdminHasRole(['edit delivery fees_settings'])
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
