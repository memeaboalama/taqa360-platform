<div>
    <div class="modal fade" id="createCountry"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Create New Country')</h6>
                    <button type="button" class="btn-close" id="createCountryCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="store"
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
                                                    <label for="country-title-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::lang.Country Title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-country-title-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('country-title-create-{{$localeCode}}','country-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="country-title-create-{{$localeCode}}"
                                                           placeholder="{{ __('admin::lang.Title') }}" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('country-title-create-{{$localeCode}}','country-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach

                                                <div class="col-xl-4">
                                                    <label for="iso"
                                                           class="form-label text-black">@lang('admin::lang.Iso') <span class="text-danger">*</span></label>
                                                    <input
                                                        id="iso"
                                                        type="text"
                                                        maxlength="3"
                                                        class="form-control uppercase @error("iso") is-invalid @enderror"
                                                        wire:model.defer="iso">

                                                    @error("iso") <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="col-xl-4">
                                                    <label for="priority"
                                                           class="form-label text-black">@lang('admin::lang.Priority') <span class="text-danger">*</span></label>
                                                    <input
                                                        id="priority"
                                                        type="number"
                                                        min="1"
                                                        step="1"
                                                        class="form-control @error("priority") is-invalid @enderror"
                                                        wire:model.defer="priority">

                                                    @error("priority") <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>

                                            <div class="col-xl-4">
                                                <label for="country-active"
                                                       class="form-label text-black">@lang('admin::lang.Status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="country-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}
                                                    >
                                                    <label for="country-active" class="label-success"
                                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="createCountryCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="store">@lang('admin::lang.Close')
                        </button>
                        <button type="submit" class="btn btn-success" id="createCountrySubmitBtn"
                                wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    @lang('admin::lang.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    @lang('admin::lang.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>