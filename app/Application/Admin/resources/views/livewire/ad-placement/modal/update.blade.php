<div class="modal fade {{ $showOffcanvas ? 'show' : '' }}" id="updatePlacementModal" tabindex="-1"
     style="display: {{ $showOffcanvas ? 'block' : 'none' }}; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header bg-warning-transparent">
                <h6 class="modal-title fw-bold text-warning">
                    <i class="fe fe-edit me-2"></i>{{ __('admin::lang.Edit Placement') }}
                </h6>
                <button type="button" class="btn-close" wire:click="close"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <h6 class="text-warning mb-2 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Basic Info') }}</h6>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Name (AR)') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" wire:model="name_ar" placeholder="اسم المساحة">
                            @error('name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Name (EN)') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" wire:model="name_en" placeholder="Placement Name">
                            @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Key') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="key" placeholder="unique_key">
                            @error('key') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Location') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" wire:model="location" placeholder="header_top">
                            @error('location') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model.live="type">
                                @foreach(\App\Support\Enum\PlacementTypeEnum::cases() as $typeEnum)
                                    <option value="{{ $typeEnum->value }}">{{ $typeEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model="status">
                                @foreach(\App\Support\Enum\StatusEnum::cases() as $statusEnum)
                                    <option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small mb-1">{{ __('admin::lang.Sort Order') }}</label>
                        <input type="number" class="form-control form-control-sm" wire:model="sort_order" min="0" placeholder="0">
                        @error('sort_order') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    @if(in_array($type, ['slider', 'carousel', 'product_slider']))
                        <h6 class="text-warning mb-2 pb-1 border-bottom small fw-bold mt-3">
                            <i class="fe fe-sliders me-2"></i>{{ __('admin::lang.Slider Settings') }}
                        </h6>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">{{ __('admin::lang.Max Items') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" wire:model="max_items" min="1" max="50" placeholder="5">
                                <small class="text-muted">{{ __('admin::lang.Max active banners') }}</small>
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">{{ __('admin::lang.Speed (ms)') }}</label>
                                <input type="number" class="form-control form-control-sm" wire:model="autoplay_speed" min="1000" placeholder="5000">
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="autoplay" id="autoplayUpdate">
                                    <label class="form-check-label small" for="autoplayUpdate">{{ __('admin::lang.Autoplay') }}</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="loop" id="loopUpdate">
                                    <label class="form-check-label small" for="loopUpdate">{{ __('admin::lang.Loop') }}</label>
                                </div>
                            </div>
                        </div>

                        @if($key === 'home_hero_slider' && $type === 'slider')
                            <div class="mt-3">
                                <label class="form-label small mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'خلفية قسم السلايدر' : 'Slider section background' }}
                                </label>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="border rounded bg-light d-flex align-items-center justify-content-center overflow-hidden"
                                         style="width: 180px; height: 90px; flex: 0 0 180px;">
                                        @if($backgroundImage)
                                            <img src="{{ $backgroundImage->temporaryUrl() }}" alt="" class="w-100 h-100" style="object-fit: cover;">
                                        @elseif($existingBackgroundImage && !$removeBackground && \Illuminate\Support\Facades\Storage::disk('fileDisk')->exists($existingBackgroundImage))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('fileDisk')->url($existingBackgroundImage) }}" alt="" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <span class="text-muted small">{{ app()->getLocale() === 'ar' ? 'لا توجد خلفية' : 'No background set' }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" class="form-control form-control-sm" wire:model="backgroundImage" accept="image/jpeg,image/png,image/webp">
                                        <small class="text-muted d-block mt-1">
                                            {{ app()->getLocale() === 'ar' ? 'ارفع صورة JPG أو PNG أو WebP (حتى 5 ميجابايت).' : 'Upload a JPG, PNG, or WebP image (up to 5 MB).' }}
                                        </small>
                                        @error('backgroundImage') <small class="text-danger d-block">{{ $message }}</small> @enderror
                                        <div wire:loading wire:target="backgroundImage" class="small text-primary mt-1">
                                            {{ app()->getLocale() === 'ar' ? 'جارٍ رفع الصورة...' : 'Uploading image...' }}
                                        </div>
                                        @if($backgroundImage || ($existingBackgroundImage && !$removeBackground))
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" wire:click="removeBackground">
                                                {{ app()->getLocale() === 'ar' ? 'إزالة الخلفية' : 'Remove background' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(in_array($type, ['product_tabs', 'product_slider', 'product_sidebar']))
                        <h6 class="text-warning mb-2 pb-1 border-bottom small fw-bold mt-3">
                            <i class="ri-shopping-bag-line me-2"></i>{{ __('admin::lang.Product Settings') }}
                        </h6>

                        <div class="mb-2">
                            <label class="form-label small mb-1">{{ __('admin::lang.Maximum Products') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" wire:model="max_items" min="1" max="50" placeholder="10">
                            <small class="text-muted">{{ __('admin::lang.Maximum number of active products in this section') }}</small>
                            @error('max_items') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="alert alert-info py-2 px-3 small mb-0">
                            <i class="ri-information-line me-1"></i>
                            {{ __('admin::lang.Product sections do not require image dimensions. Products will use their own images.') }}
                        </div>
                    @endif

                    @if($type === 'grid_banners')
                        <h6 class="text-warning mb-2 pb-1 border-bottom small fw-bold mt-3">
                            <i class="fe fe-grid me-2"></i>{{ __('admin::lang.Grid Settings') }}
                        </h6>

                        <div class="mb-2">
                            <label class="form-label small mb-1">{{ __('admin::lang.Max Items') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model="max_items" min="1" max="20" placeholder="4">
                        </div>
                    @endif



                    @if($type === 'product_tabs')
                        <h6 class="text-warning mb-2 pb-1 border-bottom small fw-bold mt-3">
                            <i class="bi bi-collection me-2"></i>{{ __('admin::lang.Product Tabs Configuration') }}
                        </h6>

                        <div class="alert alert-warning py-2 px-3 small mb-2">
                            <i class="ri-alert-line me-1"></i>
                            {{ __('admin::lang.Select which tabs to display. Each tab will show products from that category') }}
                        </div>

                        <div class="groups-container">
                            @foreach(\Application\Admin\Livewire\AdPlacement\Modal\Update::AVAILABLE_TABS as $key => $defaultTitles)
                                <div class="card bg-light border p-2 mb-2">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" wire:model="tabConfig.{{ $key }}.enabled" id="tab_update_{{ $key }}">
                                            <label class="form-check-label fw-bold small" for="tab_update_{{ $key }}">
                                                {{ $defaultTitles[app()->getLocale()] ?? $defaultTitles['en'] }}
                                            </label>
                                        </div>
                                    </div>

                                    @if($tabConfig[$key]['enabled'])
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small mb-1">{{ __('admin::lang.Title (EN)') }}</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       wire:model="tabConfig.{{ $key }}.title_en" placeholder="Title (EN)">
                                                @error("tabConfig.{$key}.title_en") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1">{{ __('admin::lang.Title (AR)') }}</label>
                                                <input type="text" class="form-control form-control-sm"
                                                       wire:model="tabConfig.{{ $key }}.title_ar" placeholder="Title (AR)">
                                                @error("tabConfig.{$key}.title_ar") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" wire:click="close">{{ __('admin::lang.Cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fe fe-save me-1"></i>{{ __('admin::lang.Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($showOffcanvas)
    <div class="modal-backdrop fade show" wire:click="close"></div>
@endif
