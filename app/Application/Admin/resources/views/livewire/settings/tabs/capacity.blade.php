<div class="tab-pane show fade p-0 border-0" id="searchCapacity-tab-pane"
     role="tabpanel" aria-labelledby="searchCapacity-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateSearchCapacity">

            <div class="row gy-3">
                <div class="col-xl-6">
                    <label for="restaurant_search_capacity" class="form-label text-default">{{ __('admin::home.Customer search capacity for a restaurant') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.05"
                           min="0.00"
                           class="form-control form-control-lg @error('restaurant_search_capacity') is-invalid @enderror"
                           id="restaurant_search_capacity"
                           value="{{ $this->restaurant_search_capacity }}"
                           wire:model.defer="restaurant_search_capacity"
                           placeholder="0.00">
                    @error('restaurant_search_capacity') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-6">
                    <label for="driver_search_capacity" class="form-label text-default">{{ __('admin::home.Restaurant search capacity for a driver') }} <span class="text-danger">*</span></label>
                    <input type="number"
                           step="0.05"
                           min="0.00"
                           class="form-control form-control-lg @error('driver_search_capacity') is-invalid @enderror"
                           id="driver_search_capacity"
                           value="{{ $this->restaurant_search_capacity }}"
                           wire:model.defer="driver_search_capacity"
                           placeholder="0.00">
                    @error('driver_search_capacity') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>
            </div>
            @AdminHasRole(['edit search capacity_settings'])
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
