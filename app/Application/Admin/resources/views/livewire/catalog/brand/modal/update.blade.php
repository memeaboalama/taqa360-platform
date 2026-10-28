<div>
    <div class="modal fade" id="updateBrand" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::lang.Update Brand')</h6>
                    <button type="button" class="btn-close" id="updateBrandCloseHeaderBtn" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="store"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-6">
                                                    <label for="brand-name-{{$localeCode}}" class="form-label text-black">@lang('admin::lang.Name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error("name." . $localeCode) is-invalid @enderror"
                                                            id="brand-name-{{$localeCode}}"
                                                            placeholder="@lang('admin::lang.Name')" wire:model.defer="name.{{$localeCode}}">
                                                    @error("name." . $localeCode)
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-12">
                                                    <label for="brand-description-{{$localeCode}}" class="form-label text-black">@lang('admin::lang.Description') {{$properties['native']}}</label>
                                                    <textarea class="form-control @error("description." . $localeCode) is-invalid @enderror"
                                                            id="brand-description-{{$localeCode}}"
                                                            placeholder="@lang('admin::lang.Description')" wire:model.defer="description.{{$localeCode}}"
                                                            rows="3"></textarea>
                                                    @error("description." . $localeCode)
                                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            <div class="col-xl-4">
                                                <label for="brand-active" class="form-label text-black">@lang('admin::lang.Active')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="brand-active-update"
                                                        type="checkbox" {{ $is_active == '1' ? 'checked' : '' }}
                                                    >
                                                    <label for="brand-active-update" class="label-success"
                                                        wire:click="updateStatus('{{ $is_active == '1' ? '0' : '1' }}')"></label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="brand-image-update" class="form-label text-black">@lang('admin::lang.Image')</label>
                                                <div class="d-flex align-items-center gap-4">
                                                    @if($image)
                                                        <div class="position-relative me-3">
                                                            <img src="{{ $image->temporaryUrl() }}"
                                                                alt="New Preview"
                                                                class="rounded shadow-sm"
                                                                style="width: 120px; height: 120px; object-fit: cover;">
                                                        </div>
                                                    @elseif($currentImage)
                                                        <div class="position-relative me-3">
                                                            <img src="{{ $currentImage }}"
                                                                alt="Current Image"
                                                                class="rounded shadow-sm"
                                                                style="width: 120px; height: 120px; object-fit: cover;">
                                                        </div>
                                                    @endif

                                                    <div class="flex-grow-1">
                                                        <input type="file"
                                                            class="form-control @error('image') is-invalid @enderror"
                                                            id="brand-image-update"
                                                            wire:model="image"
                                                            accept="image/*">
                                                        @error('image')
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                        @enderror
                                                        
                                                        <div wire:loading wire:target="image" class="mt-2">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                                                            </div>
                                                            <small class="text-muted">Uploading...</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" id="updateBrandCloseFooterBtn"
                                data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="store">@lang('admin::lang.Close')</button>
                        <button type="submit" class="btn btn-success" id="updateBrandSubmitBtn"
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
