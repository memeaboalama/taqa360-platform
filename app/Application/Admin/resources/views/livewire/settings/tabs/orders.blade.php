<div class="tab-pane  show fade p-0 border-0" id="OrdersSettings-tab-pane"
     role="tabpanel" aria-labelledby="OrdersSettings-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateOrdersSeetings">
            <div class="row gy-3">
                <div class="col-xl-3">
                    <label for="min_order_price" class="form-label text-default">{{ __('admin::home.Minimum Order Price') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control form-control-lg @error('min_order_price') is-invalid @enderror"
                           id="min_order_price"
                           value="{{ $this->min_order_price }}"
                           wire:model.defer="min_order_price"
                           placeholder="Minimum Order Price">
                    @error('min_order_price') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-3">
                    <label for="pending_order_expiry" class="form-label text-default">{{ __('admin::home.Expiration Period Pending Order') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control form-control-lg @error('pending_order_expiry') is-invalid @enderror"
                           id="pending_order_expiry"
                           value="{{ $this->pending_order_expiry }}"
                           wire:model.defer="pending_order_expiry"
                           placeholder="Expiration Period Of Pending Order">
                    @error('pending_order_expiry') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-xl-3">
                    <label for="confirmed_order_expiry" class="form-label text-default">{{ __('admin::home.Expiration Period Confirmed Order') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control form-control-lg @error('confirmed_order_expiry') is-invalid @enderror"
                           id="confirmed_order_expiry"
                           value="{{ $this->confirmed_order_expiry }}"
                           wire:model.defer="confirmed_order_expiry"
                           placeholder="Expiration Period Of Confirmed Order">
                    @error('confirmed_order_expiry') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="col-xl-3">
                    <label for="resend_order_to_drivers" class="form-label text-default">{{ __('admin::home.Resend Order to drivers') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           class="form-control form-control-lg @error('resend_order_to_drivers') is-invalid @enderror"
                           id="resend_order_to_drivers"
                           value="{{ $this->resend_order_to_drivers }}"
                           wire:model.defer="resend_order_to_drivers"
                           placeholder="Minimum Order Price">
                    @error('resend_order_to_drivers') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
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
