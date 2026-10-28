<div>
    <div class="modal fade" id="updateRegion"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Update Region')</h6>
                    <button type="button" class="btn-close" id="updateRegionCloseHeaderBtn" data-bs-dismiss="modal"
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
                                                    <label for="region-title-update-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::lang.Region Title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-region-title-update-{{$localeCode}}"
                                                            onclick="translateLocalizedField('region-title-update-{{$localeCode}}','region-title-update-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="region-title-update-{{$localeCode}}"
                                                           placeholder="{{ __('admin::lang.Title') }}" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('region-title-update-{{$localeCode}}','region-title-update-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach

                                            <div class="col-md-8">
                                                <label for="city" class="form-label text-black">@lang('admin::lang.City') <span class="text-danger">*</span></label>
                                                <select class="form-control @error("city_id") is-invalid @enderror" wire:model.defer="city_id">
                                                    <option selected value="">--{{ __('admin::lang.Select City') }}--</option>
                                                    @foreach($cities as $city)
                                                        <option selected value="{{ $city->id }}">{{ $city->title }}</option>
                                                    @endforeach
                                                </select>

                                                @error("city_id") <span
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
                                                <label for="region-active"
                                                       class="form-label text-black">@lang('admin::lang.Status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="region-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}
                                                    >
                                                    <label for="region-active" class="label-success"
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
                        <button type="button" class="btn btn-outline-danger" id="updateRegionCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="store">@lang('admin::lang.Close')
                        </button>
                        <button type="submit" class="btn btn-success" id="updateRegionSubmitBtn"
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