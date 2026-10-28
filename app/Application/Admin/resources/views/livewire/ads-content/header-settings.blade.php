<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header border-bottom">
                 <h3 class="card-title">{{ __('admin::lang.Header Settings') }}</h3>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">

                    <div class="mb-4">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Basic Info') }}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Site Name') }} (EN)</label>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer="site_name_en">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Site Name') }} (AR)</label>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer="site_name_ar">
                                </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Top Message') }} (EN)</label>
                                    <textarea class="form-control form-control-sm" wire:model.defer="top_message_en" rows="2"></textarea>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Top Message') }} (AR)</label>
                                    <textarea class="form-control form-control-sm" wire:model.defer="top_message_ar" rows="2"></textarea>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Images') }}</h6>
                        <div class="row g-3">
                            @foreach([
                                'logo' => 'Logo',
                                'mobile_logo' => 'Small Logo',
                                'favicon' => 'Favicon'
                            ] as $key => $label)
                            <div class="col-md-4">
                                <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.' . $label) }}</label>
                                    <div class="position-relative border border-dashed rounded bg-gray-50 text-center d-flex align-items-center justify-content-center hover-bg-light transition-all" style="height: 140px;">

                                        @if(${$key})
                                            <div class="position-absolute align-items-center justify-content-center w-100 h-100 p-2 d-flex bg-white rounded">
                                                <img src="{{ ${$key}->temporaryUrl() }}" class="mw-100 mh-100" style="object-fit: contain;">
                                            </div>
                                        @elseif(!empty(${'existing_'.$key}))
                                                <div class="position-absolute align-items-center justify-content-center w-100 h-100 p-2 d-flex bg-white rounded">
                                                <img src="{{ Storage::url(${'existing_'.$key}) }}" class="mw-100 mh-100" style="object-fit: contain;">
                                            </div>
                                        @else
                                            <div class="text-muted pointer-events-none">
                                                <i class="fe fe-upload-cloud fs-24 mb-1"></i>
                                                <div class="tx-11">{{ __('admin::lang.Upload') }}</div>
                                            </div>
                                        @endif

                                        <div class="position-absolute w-100 h-100 bg-black-20 opacity-0 hover-opacity-100 d-flex align-items-center justify-content-center rounded transition-all pointer-events-none">
                                            <span class="badge bg-dark text-white shadow-sm"><i class="fe fe-edit-2 me-1"></i> Change</span>
                                        </div>

                                        <input type="file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" wire:model="{{ $key }}" accept="{{ $key === 'favicon' ? '.ico,.png,.jpg,.jpeg,.svg' : 'image/*' }}">

                                        @if((${$key} || !empty(${'existing_'.$key})) && !${"delete_$key"})
                                            <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 start-0 m-1 rounded-circle shadow p-0 d-flex align-items-center justify-content-center"
                                                    style="width: 24px; height: 24px; z-index: 100;"
                                                    wire:click.prevent="removeImage('{{ $key }}')">
                                                <i class="fe fe-x tx-12"></i>
                                            </button>
                                        @endif
                                    </div>
                                @error($key) <small class="text-danger d-block mt-1 tx-11">{{ $message }}</small> @enderror
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Promotional Offer') }}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Offer Text') }} (EN)</label>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer="offer_text_en">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Offer Text') }} (AR)</label>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer="offer_text_ar">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <h6 class="text-primary mb-3 pb-1 border-bottom small fw-bold">{{ __('admin::lang.Click Action') }}</h6>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-{{ $click_action_type === 'product' ? '4' : '3' }}">
                                <div class="form-group mb-0">
                                    <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Type') }}</label>
                                    <select class="form-select form-select-sm" wire:model.live="click_action_type">
                                        <option value="none">{{ __('admin::lang.None') }}</option>
                                        @foreach(\App\Support\Enum\ClickActionTypeEnum::cases() as $type)
                                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @if($click_action_type === 'product')
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Filter by Merchant') }}</label>
                                        <livewire:admin::ads-content.shared.live-search
                                            type="merchant"
                                            emitEvent="merchantSelected"
                                            key="search-merchant-filter"
                                            :selectedItem="$selected_merchant"
                                        />
                                    </div>
                                </div>
                            @endif

                            @if(in_array($click_action_type, ['product', 'merchant', 'category']))
                                <div class="col-md-{{ $click_action_type === 'product' ? '4' : '5' }}">
                                    <div class="form-group mb-0">
                                        <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.Select Item') }}</label>
                                        <livewire:admin::ads-content.shared.live-search
                                            :type="$click_action_type"
                                            :merchantId="$click_action_type === 'product' ? $merchant_id : null"
                                            :emitEvent="'itemSelected'"
                                            :key="'search-header-' . $click_action_type . '-' . ($merchant_id ?? 'all')"
                                            :selectedItem="$selected_action_item"
                                        />
                                    </div>
                                </div>
                            @endif

                            @if($click_action_type == 'url')
                                <div class="col-md-9">
                                    <div class="form-group mb-0">
                                        <label class="form-label small mb-1 tx-muted">{{ __('admin::lang.External URL') }}</label>
                                        <input type="url" class="form-control form-control-sm" wire:model.defer="click_action_url" placeholder="https://example.com">
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($click_action_url && in_array($click_action_type, ['product', 'merchant', 'category']))
                            <div class="row mt-2">
                                <div class="col-12">
                                        <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">{{ __('admin::lang.generated_url') }}</span>
                                        <input type="text" class="form-control bg-light" value="{{ $click_action_url }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5">
                            <span wire:loading.remove wire:target="save">
                                <i class="fe fe-save me-1"></i> {{ __('admin::lang.Save Changes') }}
                            </span>
                             <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1"></span> {{ __('admin::lang.Saving...') }}
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
.hover-bg-light:hover { background-color: #f8f9fa !important; border-color: var(--primary) !important; }
.hover-opacity-100:hover { opacity: 1 !important; }
.bg-black-20 { background-color: rgba(0,0,0,0.05); }
.pointer-events-none { pointer-events: none; }
</style>
