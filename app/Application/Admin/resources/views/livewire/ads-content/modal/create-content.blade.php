<div class="modal fade" id="createContentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fe fe-plus-circle me-2"></i>{{ __('admin::home.Add') }} {{ __('admin::lang.Content') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="store">
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Basic Info') }}</h6>

                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label small mb-1">{{ __('admin::lang.Internal Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" wire:model.defer="name">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ $placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER ? __('admin::lang.Site Name') : __('admin::lang.Title') }} (EN)
                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-ads-content-title-en"
                                    onclick="translateLocalizedField('ads-content-title-en','ads-content-title-ar','en','ar',true)"
                                    title="{{ __('admin::lang.Translate') }}"
                                    style="cursor: pointer;">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </label>
                            <input type="text" class="form-control form-control-sm" id="ads-content-title-en" wire:model.defer="title_en"
                                onblur="translateLocalizedField('ads-content-title-en','ads-content-title-ar','en','ar',false)">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ $placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER ? __('admin::lang.Site Name') : __('admin::lang.Title') }} (AR)
                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-ads-content-title-ar"
                                    onclick="translateLocalizedField('ads-content-title-ar','ads-content-title-en','ar','en',true)"
                                    title="{{ __('admin::lang.Translate') }}"
                                    style="cursor: pointer;">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </label>
                            <input type="text" class="form-control form-control-sm" id="ads-content-title-ar" wire:model.defer="title_ar"
                                onblur="translateLocalizedField('ads-content-title-ar','ads-content-title-en','ar','en',false)">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ $placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER ? __('admin::lang.Top Message') : __('admin::lang.Subtitle') }} (EN)
                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-ads-content-subtitle-en"
                                    onclick="translateLocalizedField('ads-content-subtitle-en','ads-content-subtitle-ar','en','ar',true)"
                                    title="{{ __('admin::lang.Translate') }}"
                                    style="cursor: pointer;">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </label>
                            <textarea class="form-control form-control-sm" id="ads-content-subtitle-en" wire:model.defer="subtitle_en" rows="2"
                                onblur="translateLocalizedField('ads-content-subtitle-en','ads-content-subtitle-ar','en','ar',false)"></textarea>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ $placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER ? __('admin::lang.Top Message') : __('admin::lang.Subtitle') }} (AR)
                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-ads-content-subtitle-ar"
                                    onclick="translateLocalizedField('ads-content-subtitle-ar','ads-content-subtitle-en','ar','en',true)"
                                    title="{{ __('admin::lang.Translate') }}"
                                    style="cursor: pointer;">
                                    <i class="ri-refresh-line"></i>
                                </button>
                            </label>
                            <textarea class="form-control form-control-sm" id="ads-content-subtitle-ar" wire:model.defer="subtitle_ar" rows="2"
                                onblur="translateLocalizedField('ads-content-subtitle-ar','ads-content-subtitle-en','ar','en',false)"></textarea>
                        </div>
                    </div>

                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">{{ __('admin::lang.Images') }}</h6>
                    @if($placement && $placement->key === 'home_hero_slider')
                        <small class="text-muted d-block mb-2">{{ __('admin::lang.Full image display hint') }}</small>
                    @endif

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ $placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER ? __('admin::lang.Logo') : __('admin::lang.Desktop Image') }} <span class="text-danger">*</span>
                                @if($placement && $placement->key !== 'home_hero_slider')
                                <small class="text-muted">
                                    ({{ $placement->settings['display']['devices']['desktop']['width'] ?? '' }}x{{ $placement->settings['display']['devices']['desktop']['height'] ?? '' }}px)
                                </small>
                                @endif
                            </label>
                            <div>
                                <input type="file" id="ads-content-image-desktop" class="form-control form-control-sm @error('image_desktop') is-invalid @enderror" accept="image/*" data-property="image_desktop">
                                @error('image_desktop') <small class="text-danger">{{ $message }}</small> @enderror
                                <img id="ads-crop-preview-image_desktop" class="ads-crop-preview d-none mt-1" src="" alt="{{ __('admin::lang.Desktop Image') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ $placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER ? __('admin::lang.Small Logo') : __('admin::lang.Mobile Image') }}
                                @if($placement && $placement->key !== 'home_hero_slider')
                                <small class="text-muted">
                                    ({{ $placement->settings['display']['devices']['mobile']['width'] ?? '' }}x{{ $placement->settings['display']['devices']['mobile']['height'] ?? '' }}px)
                                </small>
                                @endif
                            </label>
                            <div>
                                <input type="file" id="ads-content-image-mobile" class="form-control form-control-sm @error('image_mobile') is-invalid @enderror" accept="image/*" data-property="image_mobile">
                                @error('image_mobile') <small class="text-danger">{{ $message }}</small> @enderror
                                <img id="ads-crop-preview-image_mobile" class="ads-crop-preview d-none mt-1" src="" alt="{{ __('admin::lang.Mobile Image') }}">
                            </div>
                        </div>

                        @if(!$placement || $placement->type !== \App\Support\Enum\PlacementTypeEnum::HEADER)
                        <div class="col-6">
                            <label class="form-label small mb-1">
                                {{ __('admin::lang.Tablet Image') }}
                                @if($placement && $placement->key !== 'home_hero_slider')
                                <small class="text-muted">
                                    ({{ $placement->settings['display']['devices']['tablet']['width'] ?? '' }}x{{ $placement->settings['display']['devices']['tablet']['height'] ?? '' }}px)
                                </small>
                                @endif
                            </label>
                            <div>
                                <input type="file" id="ads-content-image-tablet" class="form-control form-control-sm @error('image_tablet') is-invalid @enderror" accept="image/*" data-property="image_tablet">
                                @error('image_tablet') <small class="text-danger">{{ $message }}</small> @enderror
                                <img id="ads-crop-preview-image_tablet" class="ads-crop-preview d-none mt-1" src="" alt="{{ __('admin::lang.Tablet Image') }}">
                            </div>
                        </div>
                        @endif

                        @if($placement && $placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER)
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Favicon') }}</label>
                            <input type="file" class="form-control form-control-sm" wire:model="image_favicon" accept=".ico,.png,.jpg,.jpeg,.svg">
                        </div>
                        @endif
                    </div>

                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">{{ __('admin::lang.Click Action') }}</h6>

                    <div class="row g-2 mb-2 align-items-end">
                        <div class="col-md-{{ $click_action_type === 'product' ? '4' : '6' }}">
                            <label class="form-label small mb-1">{{ __('admin::lang.Click Action Type') }}</label>
                            <select class="form-select form-select-sm" wire:model.live="click_action_type" style="height: 38px;">
                                <option value="none">{{ __('admin::lang.None') }}</option>
                                @foreach(\App\Support\Enum\ClickActionTypeEnum::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($click_action_type === 'product')
                        <div class="col-md-4">
                            <label class="form-label small mb-1">{{ __('admin::lang.Merchant') }} <small class="text-muted">({{ __('admin::lang.Optional') }})</small></label>
                            <livewire:admin::ads-content.shared.live-search
                                type="merchant"
                                emitEvent="merchantSelected"
                                key="search-merchant-filter" />
                        </div>
                        @endif

                        @if(in_array($click_action_type, ['product', 'merchant', 'category']))
                        <div class="col-md-{{ $click_action_type === 'product' ? '4' : '6' }}">
                            <label class="form-label small mb-1">{{ __('admin::lang.Search', ['type' => ucfirst($click_action_type)]) }}</label>
                            <livewire:admin::ads-content.shared.live-search
                                :type="$click_action_type"
                                :merchantId="$click_action_type === 'product' ? $merchant_id : null"
                                :emitEvent="'itemSelected'"
                                :key="'search-create-' . $click_action_type . '-' . ($merchant_id ?? 'all')" />
                        </div>
                        @endif

                        @if($click_action_type == 'url')
                        <div class="col-md-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.URL') }}</label>
                            <input type="url" class="form-control form-control-sm" style="height: 38px;" wire:model.defer="click_action_url" placeholder="https://example.com">
                        </div>
                        @endif

                        @if($click_action_url && in_array($click_action_type, ['product', 'merchant', 'category']))
                        <div class="col-12 mt-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">{{ __('admin::lang.generated_url') }}</span>
                                <input type="text" class="form-control bg-light" wire:model="click_action_url" readonly>
                            </div>
                        </div>
                        @endif
                    </div>

                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-3">{{ __('admin::lang.Schedule') }}</h6>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Start Date') }}</label>
                            <input type="datetime-local" class="form-control form-control-sm" wire:model.defer="start_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.End Date') }}</label>
                            <input type="datetime-local" class="form-control form-control-sm" wire:model.defer="end_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Status') }}</label>
                            <select class="form-select form-select-sm" wire:model.defer="status">
                                @foreach(\App\Support\Enum\BannerStatusEnum::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Priority') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="priority" min="0" max="100" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('admin::home.Close') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <span wire:loading.remove wire:target="store">
                            <i class="fe fe-save me-1"></i>{{ __('admin::home.Save') }}
                        </span>
                        <span wire:loading wire:target="store">
                            <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::home.Wait') }}
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