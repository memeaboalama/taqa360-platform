<div>
    <div class="modal fade" id="createMenu" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.Create New Menu')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-xxl-9 col-xl-9 col-lg-8 col-md-7">
                                <label for="service-name" class="form-label text-black">@lang('marketplace::home.Menu Name') ({{ LaravelLocalization::getSupportedLocales()['ar']['native'] }}) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="service-name" placeholder="{{ __('marketplace::home.Menu Name') }}" wire:model.defer="title.ar">
                                @error("title.ar")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-5">
                                <label for="service-active" class="form-label text-black">@lang('marketplace::home.status') <span class="text-danger">*</span></label>
                                <div class="custom-toggle-switch d-flex align-items-center mt-3">
                                    <input id="service-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                    <label for="service-active" class="label-success" wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                </div>
                            </div>

                            <div class="col-xxl-12 col-xl-12 col-lg-12">
                                <label for="service-name-en" class="form-label text-black">@lang('marketplace::home.Menu Name') ({{ LaravelLocalization::getSupportedLocales()['en']['native'] }}) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="service-name-en" placeholder="{{ __('marketplace::home.Menu Name') }}" wire:model.defer="title.en">
                                @error("title.en")
                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">@lang('marketplace::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                @lang('marketplace::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
