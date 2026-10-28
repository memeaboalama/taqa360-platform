<div>
    <div class="modal fade" id="createCategory"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Create new category')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form method="POST" wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                <div class="col-xl-6">
                                                    <label for="market-cat-title-create-{{$localeCode}}" class="form-label text-black">
                                                        @lang('admin::home.Category Title') {{$properties['native']}} <span class="text-danger">*</span>
                                                        <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-market-cat-title-create-{{$localeCode}}"
                                                            onclick="translateLocalizedField('market-cat-title-create-{{$localeCode}}','market-cat-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                            title="تحديث الترجمة"
                                                            style="cursor: pointer;">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </label>
                                                    <input type="text" class="form-control @error("title." . $localeCode) is-invalid @enderror"
                                                           id="market-cat-title-create-{{$localeCode}}"
                                                           placeholder="@lang('admin::home.Title')" wire:model.defer="title.{{$localeCode}}"
                                                           onblur="translateLocalizedField('market-cat-title-create-{{$localeCode}}','market-cat-title-create-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                    @error("title." . $localeCode) <span
                                                        class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach


                                                <div class="col-xl-6">
                                                    <label for="type"
                                                           class="form-label text-black">@lang('admin::home.sort') <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control @error("sort") is-invalid @enderror"
                                                           id="sort"
                                                           min="1"
                                                           placeholder="@lang('admin::home.sort')" wire:model.defer="sort">

                                                    @error("sort") <span
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

                                                <div class="col-xl-10">
                                                    <label for="image" class="form-label text-black">@lang('admin::home.image')</label>
                                                    <input type="file" class="form-control" id="image" placeholder="{{ __("admin::home.image") }}" wire:model.defer="image">
                                                    @error("image")
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-xl-12">
                                                    <label for="image" class="form-label text-black">@lang('admin::home.color') <span class="text-danger">*</span></label>
                                                    <input type="color" class="form-control form-control-color border-0" wire:model.defer="color"
                                                           id="exampleColorInput" value="{{ $color }}" title="Choose your color">
                                                    @error("color")
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
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
            const modal = form.closest('#createCategory');
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
            document.querySelectorAll('#createCategory button[data-op-disabled]').forEach(function (btn) {
                const modal = btn.closest('#createCategory');
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
