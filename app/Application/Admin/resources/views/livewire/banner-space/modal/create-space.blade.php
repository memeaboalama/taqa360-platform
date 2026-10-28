<div class="modal fade" id="createSpaceModal" tabindex="-1" aria-hidden="true" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fe fe-plus-circle me-2"></i>{{ __('admin::home.Add') }} ( {{ __('admin::lang.Ad Space') }} )
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled"></button>
            </div>

            <form wire:submit.prevent="store">
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">

                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Basic Info') }}</h6>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ __('admin::lang.Space Name') }} (AR) <span class="text-danger">*</span>
                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-space-name-ar"
                                    onclick="translateLocalizedField('space-name-ar','space-name-en','ar','en',true)"
                                    title="{{ __('admin::lang.Translate') }}"
                                    style="cursor: pointer;">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </label>
                            <input type="text" class="form-control" id="space-name-ar" wire:model.defer="name_ar"
                                onblur="translateLocalizedField('space-name-ar','space-name-en','ar','en',false)">
                            @error('name_ar') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ __('admin::lang.Space Name') }} (EN) <span class="text-danger">*</span>
                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-space-name-en"
                                    onclick="translateLocalizedField('space-name-en','space-name-ar','en','ar',true)"
                                    title="{{ __('admin::lang.Translate') }}"
                                    style="cursor: pointer;">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </label>
                            <input type="text" class="form-control" id="space-name-en" wire:model.defer="name_en"
                                onblur="translateLocalizedField('space-name-en','space-name-ar','en','ar',false)">
                            @error('name_en') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Key') }}</label>
                            <input type="text" class="form-control form-control-sm bg-light" wire:model="key" disabled readonly placeholder="{{ __('admin::lang.auto_generated') }}">
                            <small class="text-muted">{{ __('admin::lang.Unique identifier') }}</small>
                            @error('key') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Location') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model="location">
                                <option value="header">{{ __('admin::lang.Header') }}</option>
                                <option value="content_top">{{ __('admin::lang.Content Top') }}</option>
                                <option value="content_middle">{{ __('admin::lang.Content Middle') }}</option>
                                <option value="content_bottom">{{ __('admin::lang.Content Bottom') }}</option>
                                <option value="sidebar">{{ __('admin::lang.Sidebar') }}</option>
                                <option value="footer">{{ __('admin::lang.Footer') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Placement Type') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model.live="type">
                                @foreach(\App\Support\Enum\PlacementTypeEnum::cases() as $typeEnum)
                                <option value="{{ $typeEnum->value }}">{{ $typeEnum->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label small mb-1">{{ __('admin::home.status') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model.defer="status">
                                @foreach(\App\Support\Enum\StatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label small mb-1">{{ __('admin::lang.Priority') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="sort_order" min="0" placeholder="0">
                        </div>
                    </div>


                    @if(!in_array($type, ['product_tabs', 'product_slider', 'product_sidebar']))
                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">{{ __('admin::lang.Display Settings') }}</h6>

                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="desktop_enabled" id="desktopEnabled">
                                    <label class="form-check-label small fw-semibold" for="desktopEnabled">{{ __('admin::lang.Desktop') }}</label>
                                </div>
                                <input type="number" class="form-control form-control-sm mb-1" wire:model.defer="desktop_width" placeholder="{{ __('admin::lang.Width') }}">
                                <input type="number" class="form-control form-control-sm" wire:model.defer="desktop_height" placeholder="{{ __('admin::lang.Height') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="tablet_enabled" id="tabletEnabled">
                                    <label class="form-check-label small fw-semibold" for="tabletEnabled">{{ __('admin::lang.Tablet') }}</label>
                                </div>
                                <input type="number" class="form-control form-control-sm mb-1" wire:model.defer="tablet_width" placeholder="{{ __('admin::lang.Width') }}">
                                <input type="number" class="form-control form-control-sm" wire:model.defer="tablet_height" placeholder="{{ __('admin::lang.Height') }}">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="mobile_enabled" id="mobileEnabled">
                                    <label class="form-check-label small fw-semibold" for="mobileEnabled">{{ __('admin::lang.Mobile') }}</label>
                                </div>
                                <input type="number" class="form-control form-control-sm mb-1" wire:model.defer="mobile_width" placeholder="{{ __('admin::lang.Width') }}">
                                <input type="number" class="form-control form-control-sm" wire:model.defer="mobile_height" placeholder="{{ __('admin::lang.Height') }}">
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(in_array($type, ['product_tabs', 'product_slider', 'product_sidebar']))
                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">
                        <i class="ri-shopping-bag-line me-2"></i>{{ __('admin::lang.Product Settings') }}
                    </h6>

                    <div class="mb-2">
                        <label class="form-label small mb-1">{{ __('admin::lang.Maximum Products') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm" wire:model.defer="max_items" min="1" max="50" placeholder="10">
                        <small class="text-muted">{{ __('admin::lang.Maximum number of active products in this section') }}</small>
                    </div>

                    <div class="alert alert-info py-2 px-3 small mb-0">
                        <i class="ri-information-line me-1"></i>
                        {{ __('admin::lang.Product sections do not require image dimensions. Products will use their own images.') }}
                    </div>
                    @endif


                    @if(in_array($type, ['slider', 'carousel']))
                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">
                        <i class="fe fe-sliders me-2"></i>{{ __('admin::lang.Slider Settings') }}
                    </h6>

                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label class="form-label small mb-1">{{ __('admin::lang.Max Items') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="max_items" min="1" placeholder="5">
                        </div>
                        <div class="col-4">
                            <label class="form-label small mb-1">{{ __('admin::lang.Autoplay Speed (ms)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="autoplay_speed" placeholder="5000">
                        </div>
                        <div class="col-4">
                            <label class="form-label small mb-1">{{ __('admin::lang.Transition Speed (ms)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="transition_speed" placeholder="500">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.defer="autoplay" id="autoplayCreate">
                                <label class="form-check-label small" for="autoplayCreate">{{ __('admin::lang.Autoplay') }}</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.defer="loop" id="loopCreate">
                                <label class="form-check-label small" for="loopCreate">{{ __('admin::lang.Loop') }}</label>
                            </div>
                        </div>
                    </div>
                    @endif


                    @if($type == 'grid_banners')
                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">
                        <i class="fe fe-grid me-2"></i>{{ __('admin::lang.Grid Settings') }}
                    </h6>

                    <div class="row g-2 mb-2">
                        <div class="col-3">
                            <label class="form-label small mb-1">{{ __('admin::lang.Columns (Desktop)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="grid_columns_desktop" placeholder="4">
                        </div>
                        <div class="col-3">
                            <label class="form-label small mb-1">{{ __('admin::lang.Columns (Tablet)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="grid_columns_tablet" placeholder="2">
                        </div>
                        <div class="col-3">
                            <label class="form-label small mb-1">{{ __('admin::lang.Columns (Mobile)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="grid_columns_mobile" placeholder="1">
                        </div>
                        <div class="col-3">
                            <label class="form-label small mb-1">{{ __('admin::lang.Gap (px)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="grid_gap" placeholder="16">
                        </div>
                    </div>
                    @endif

                    @if($type === 'product_tabs')
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fe fe-layers me-2 text-primary"></i>{{ __('admin::lang.Product Tabs Configuration') }}
                            </h6>
                            <span class="badge bg-light text-dark">
                                {{ count(array_filter($tabConfig, fn($t) => $t['enabled'] ?? false)) }}/{{ count($tabConfig) }} {{ __('admin::lang.Enabled') }}
                            </span>
                        </div>

                        <div class="alert alert-light border-start border-primary border-4 py-2 px-3 mb-3">
                            <small class="text-muted">
                                <i class="fe fe-info me-1"></i>
                                {{ __('admin::lang.Enable tabs and configure their queries to show dynamic product lists') }}
                            </small>
                        </div>

                        <div class="row g-2">
                            @foreach(\Application\Admin\Livewire\BannerSpace\Modal\CreateSpace::AVAILABLE_TABS as $key => $defaultTitles)
                            <div class="col-12">
                                <div class="card border mb-2 {{ ($tabConfig[$key]['enabled'] ?? false) ? 'border-primary' : '' }}">
                                    <div class="card-body p-3">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model.live="tabConfig.{{ $key }}.enabled"
                                                        id="tab_{{ $key }}"
                                                        style="width: 2.5rem; height: 1.25rem;">
                                                    <label class="form-check-label visually-hidden" for="tab_{{ $key }}">{{ __('admin::lang.Enable') }}</label>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="fw-semibold small">{{ $defaultTitles[app()->getLocale()] ?? $defaultTitles['en'] }}</div>
                                                <small class="text-muted">{{ $key }}</small>
                                            </div>

                                            @if($tabConfig[$key]['enabled'] ?? false)
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model="tabConfig.{{ $key }}.title_en"
                                                    placeholder="{{ __('admin::lang.Title (EN)') }}">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model="tabConfig.{{ $key }}.title_ar"
                                                    placeholder="{{ __('admin::lang.Title (AR)') }}"
                                                    dir="rtl">
                                            </div>

                                            <div class="col-md-3">
                                                <select class="form-select form-select-sm"
                                                    wire:model.live="tabConfig.{{ $key }}.query.type">
                                                    <option value="preset">{{ __('admin::lang.Preset') }} ({{ ucfirst(str_replace('_', ' ', $key)) }})</option>
                                                    <option value="custom">{{ __('admin::lang.Custom Query') }}</option>
                                                </select>
                                            </div>

                                            <div class="col-auto">
                                                @if(($tabConfig[$key]['query']['type'] ?? 'preset') === 'preset')
                                                <span class="badge bg-success-transparent text-success">
                                                    <i class="fe fe-check-circle"></i> {{ __('admin::lang.Auto') }}
                                                </span>
                                                @else
                                                <span class="badge bg-warning-transparent text-warning">
                                                    <i class="fe fe-alert-triangle"></i> {{ __('admin::lang.Custom') }}
                                                </span>
                                                @endif
                                            </div>
                                            @else
                                            <div class="col">
                                                <small class="text-muted">{{ __('admin::lang.Enable this tab to configure') }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-md-4">
                                <label class="form-label small mb-1">
                                    <i class="fe fe-hash me-1"></i>{{ __('admin::lang.Max Items per Tab') }}
                                </label>
                                <input type="number" class="form-control form-control-sm"
                                    wire:model="max_items" min="1" max="50" placeholder="10">
                                <small class="text-muted">{{ __('admin::lang.Default') }}: 10</small>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" wire:loading.attr="disabled">{{ __('admin::home.close') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">
                            <i class="fe fe-save me-1"></i>{{ __('admin::home.Save') }}
                        </span>
                        <span wire:loading wire:target="store">
                            <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::home.Save') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function translateLocalizedField(sourceId, targetId, from, to, force) {
        if (typeof window.__translationInProgress === 'undefined') {
            window.__translationInProgress = {};
        }
        var sourceEl = document.getElementById(sourceId);
        var targetEl = document.getElementById(targetId);
        if (!sourceEl || !targetEl) return;

        var text = (sourceEl.value || '').trim();
        if (!text) return;

        if (!force && (targetEl.value || '').trim() !== '') return;

        var key = sourceId;
        if (window.__translationInProgress[key]) return;
        window.__translationInProgress[key] = true;

        var btn = document.getElementById('translate-' + sourceId);
        var originalHtml = btn ? btn.innerHTML : '';

        if (btn) {
            btn.disabled = true;
            btn.classList.add('disabled');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        }

        fetch('https://api.mymemory.translated.net/get?q=' + encodeURIComponent(text) + '&langpair=' + from + '|' + to)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                var translated = data && data.responseData && data.responseData.translatedText;
                if (translated) {
                    targetEl.value = translated;
                    targetEl.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    targetEl.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            })
            .catch(function(error) {
                console.error('Translation failed:', error);
            })
            .finally(function() {
                window.__translationInProgress[key] = false;
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('disabled');
                    btn.innerHTML = originalHtml;
                }
            });
    }
</script>