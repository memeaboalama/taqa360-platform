<div>
    <div class="modal fade" id="updateCarType" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Update Car Type')</h6>
                    <button type="button" class="btn-close" id="updateCarTypeCloseHeaderBtn" data-bs-dismiss="modal"
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
                                                    <label for="car-type-title-update-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::lang.Title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-car-type-title-update-{{$localeCode}}"
                                                            onclick="translateLocalizedField('car-type-title-update-{{$localeCode}}','car-type-title-update-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="car-type-title-update-{{$localeCode}}"
                                                           placeholder="@lang('admin::lang.Title')" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('car-type-title-update-{{$localeCode}}','car-type-title-update-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode)
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            <div class="col-xl-6">
                                                <label for="car-type-key-update" class="form-label text-black">@lang('admin::lang.Key')</label>
                                                <input type="text" class="form-control @error('key') is-invalid @enderror"
                                                       id="car-type-key-update"
                                                       placeholder="@lang('admin::lang.Key')" wire:model.defer="key">
                                                @error('key')
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-5">
                                                <label for="car-type-elm-id-update" class="form-label text-black">@lang('admin::lang.Elm Id')</label>
                                                <input type="text" class="form-control @error('elm_id') is-invalid @enderror"
                                                       id="car-type-elm-id-update"
                                                       placeholder="@lang('admin::lang.Elm Id')" wire:model.defer="elm_id">
                                                @error('elm_id')
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-1">
                                                <label for="car-type-active-update" class="form-label text-black">@lang('admin::lang.Active')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mt-3">
                                                    <input id="car-type-active-update"
                                                        type="checkbox" {{ $is_active == '1' ? 'checked' : '' }}
                                                    >
                                                    <label for="car-type-active-update" class="label-success"
                                                        wire:click="updateStatus('{{ $is_active == '1' ? '0' : '1' }}')"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="updateCarTypeCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="store">@lang('admin::lang.Close')</button>
                        <button type="submit" class="btn btn-success" id="updateCarTypeSubmitBtn"
                                wire:loading.attr="disabled" wire:target="store">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                @lang('admin::lang.Update')
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
