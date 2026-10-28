<div>
    <div class="modal fade" id="updateBrand"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Update Brand')</h6>
                    <button type="button" class="btn-close" id="updateBrandCloseBtn" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                <div class="col-xl-6">
                                                    <label for="brand-title-update-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::home.Title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-brand-title-update-{{$localeCode}}"
                                                            onclick="translateLocalizedField('brand-title-update-{{$localeCode}}','brand-title-update-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="brand-title-update-{{$localeCode}}"
                                                           placeholder="@lang('admin::home.Title')" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('brand-title-update-{{$localeCode}}','brand-title-update-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                            <div class="col-xl-6">
                                                <label for="brand-type-update"
                                                       class="form-label text-black">@lang('admin::home.Type') <span class="text-danger">*</span></label>
                                                <select class="form-control @error("type") is-invalid @enderror" id="brand-type-update" wire:model.defer="type">
                                                    <option selected value="">@lang('admin::home.choose_brand_type')</option>
                                                    <option value="car">@lang('admin::home.Car')</option>
                                                    <option value="vehicle">@lang('admin::home.Vehicle')</option>
                                                </select>

                                                @error("type") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="updateBrandCloseFooterBtn"
                                data-bs-dismiss="modal">@lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success" id="updateBrandSubmitBtn">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                @lang('admin::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('js')
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form) return;
            let isLivewireForm = false;
            for (let i = 0; i < form.attributes.length; i++) {
                if (form.attributes[i].name.indexOf('wire:submit') === 0) {
                    isLivewireForm = true;
                    break;
                }
            }
            if (!isLivewireForm) return;
            const modal = form.closest('#updateBrand');
            if (modal) {
                modal.querySelectorAll('button').forEach(function (btn) {
                    if (!btn.disabled) {
                        btn.setAttribute('data-op-disabled', '1');
                        btn.disabled = true;
                    }
                });
            }
        });
        window.addEventListener('livewire:update', function () {
            document.querySelectorAll('#updateBrand button[data-op-disabled]').forEach(function (btn) {
                const modal = btn.closest('#updateBrand');
                const spinner = modal ? modal.querySelector('.spinner-border') : null;
                const spinnerVisible = spinner && spinner.offsetParent !== null;
                if (!spinnerVisible) {
                    btn.disabled = false;
                    btn.removeAttribute('data-op-disabled');
                }
            });
        });
    </script>
@endpush