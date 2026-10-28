<div>
    <div class="modal fade" id="createMainCategory"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Create New Tag')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                                                    <label for="main-cat-title-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::home.Category Title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-main-cat-title-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('main-cat-title-create-{{$localeCode}}','main-cat-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="main-cat-title-create-{{$localeCode}}"
                                                           placeholder="@lang('admin::home.Title')" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('main-cat-title-create-{{$localeCode}}','main-cat-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                    @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                    <div class="col-xl-6">
                                                        <label for="main-cat-desc-create-{{$localeCode}}" class="form-label text-black">
                                                            @lang('admin::home.Description') {{$properties['native']}}
                                                            <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-main-cat-desc-create-{{$localeCode}}"
                                                                onclick="translateLocalizedField('main-cat-desc-create-{{$localeCode}}','main-cat-desc-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                                title="تحديث الترجمة"
                                                                style="cursor: pointer;">
                                                                <i class="ri-refresh-line"></i>
                                                            </button>
                                                        </label>
                                                        <textarea class="form-control @error("description." . $localeCode) is-invalid @enderror" id="main-cat-desc-create-{{$localeCode}}" rows="5" placeholder="@lang('admin::home.Description') {{$properties['native']}}" wire:model.defer="description.{{$localeCode}}" onblur="translateLocalizedField('main-cat-desc-create-{{$localeCode}}','main-cat-desc-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)"></textarea>
                                                        @error("description." . $localeCode) <span
                                                            class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                    </div>
                                                @endforeach

                                                <div class="col-xl-6">
                                                    <label for="type"
                                                           class="form-label text-black">@lang('admin::home.Type') <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error("type") is-invalid @enderror"
                                                           id="category-name"
                                                           placeholder="@lang('admin::home.Type')" wire:model.defer="type">

                                                    @error("type") <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                                </div>

                                            <div class="col-xl-4">
                                                <label for="category-active"
                                                       class="form-label text-black">@lang('admin::home.status') <span class="text-danger">*</span></label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="category-active" class="label-success"
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
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">@lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
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
            const modal = form.closest('#createMainCategory');
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
            document.querySelectorAll('#createMainCategory button[data-op-disabled]').forEach(function (btn) {
                const modal = btn.closest('#createMainCategory');
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
