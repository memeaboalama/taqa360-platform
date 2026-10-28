<div>
    <div class="modal fade" id="sendNotification" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">{{ __('admin::home.Create new Notification') }}</h6>
                    <button type="button" class="btn-close" id="sendNotificationCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="send"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="send">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-xl-6">
                                <label for="device_type"
                                       class="form-label text-black">{{ __('admin::home.send to') }}</label>
                                <select wire:model.defer="device_type" class="form-control @error('device_type') is-invalid @enderror">
                                    <option value="App\Domain\User\Models\User">{{ __('admin::home.customers') }}</option>
                                    <option value="Domain\Driver\Models\Driver">{{ __('admin::home.Drivers') }}</option>
                                    <option value="Domain\MarketPlace\Models\MerchantEmployee">{{ __('admin::home.Merchants') }}</option>
                                </select>
                                @error("device_type") <span
                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="title" class="form-label text-black">{{ __('admin::home.Title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error("title") is-invalid @enderror"
                                       id="title"
                                       placeholder="{{ __('admin::home.Title') }}" wire:model.defer="title">
                                @error("title") <span
                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-12">
                                <label for="body" class="form-label text-black">{{ __('admin::home.message') }} <span class="text-danger">*</span></label>
                                <textarea id="body" class="form-control @error("body") is-invalid @enderror text-black"
                                          rows="7" placeholder="{{ __('admin::home.message') }}" wire:model.defer="body"></textarea>
                                @error("body")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="sendNotificationCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="send">{{ __('admin::home.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="send">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="send">
                                {{ __('admin::home.send') }}
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="send">
                                {{ __('admin::home.wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
