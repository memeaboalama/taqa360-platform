<div class="product-editor" id="merchant-product-editor">
    <style>
        .product-editor { --editor-border: var(--default-border, #e8ecf1); padding-bottom: 1.5rem; min-width:0; max-width:100%; }
        .product-editor .editor-heading { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
        .product-editor .editor-heading h2 { font-size:1.5rem; font-weight:700; margin-bottom:.5rem; }
        .product-editor .editor-heading > * { min-width:0; max-width:100%; }
        .product-editor .editor-subtitle { color:var(--text-muted); margin:0; font-size:.875rem; }
        .product-editor .editor-product { display:flex; gap:1rem; align-items:center; margin-bottom:1.75rem; }
        .product-editor .editor-thumbnail { width:78px; height:78px; object-fit:contain; border:1px solid var(--editor-border); border-radius:12px; background:var(--custom-white); padding:.4rem; }
        .product-editor .editor-product h3 { font-size:1.25rem; font-weight:700; margin:0 0 .5rem; }
        .product-editor .editor-grid { display:grid; grid-template-columns:minmax(0, 1fr) 250px; gap:1.25rem; align-items:start; }
        .product-editor .editor-main { min-width:0; }
        .product-editor .editor-specifications-card { overflow:visible !important; }
        .product-editor .editor-multiselect { position:relative; }
        .product-editor .editor-multiselect summary { cursor:pointer; list-style:none; overflow-wrap:anywhere; }
        .product-editor .editor-multiselect summary::-webkit-details-marker { display:none; }
        .product-editor .editor-multiselect[open] { z-index:20; }
        .product-editor .editor-multiselect-options { position:absolute; inset-inline:0; top:calc(100% + 5px); max-height:260px; overflow-y:auto; padding:.5rem; background:var(--custom-white,#fff); border:1px solid var(--editor-border); border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,.12); }
        .product-editor .editor-multiselect-option { display:flex; align-items:center; gap:.65rem; padding:.65rem .75rem; margin:0; border-radius:5px; cursor:pointer; }
        .product-editor .editor-multiselect-option:hover { background:rgba(var(--primary-rgb),.06); }
        .product-editor .editor-multiselect-option .form-check-input { float:none; margin:0; flex-shrink:0; }
        .product-editor .editor-card { background:var(--custom-white, #fff); border:1px solid var(--editor-border); border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,.025); margin-bottom:1.25rem; overflow:hidden; }
        .product-editor .editor-card-heading { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.75rem; border-bottom:1px solid var(--editor-border); padding:1.15rem 1.25rem; }
        .product-editor .editor-card-heading h4 { font-size:1rem; font-weight:700; margin:0; }
        .product-editor .editor-card-body { padding:1.25rem; }
        .product-editor .form-label { font-size:.875rem; margin-bottom:.5rem; }
        .product-editor .form-control,.product-editor .form-select { min-height:42px; border-radius:7px; font-size:.9375rem; }
        .product-editor .editor-tabs { display:flex; gap:1rem; border-bottom:1px solid var(--editor-border); margin-bottom:1.25rem; }
        .product-editor .editor-tab { border:0; border-bottom:2px solid transparent; padding:.85rem 1rem; background:transparent; color:var(--text-muted); font-weight:500; }
        .product-editor .editor-tab.active { color:rgb(var(--primary-rgb)); border-bottom-color:rgb(var(--primary-rgb)); font-weight:700; }
        .product-editor .editor-step { display:inline-flex; align-items:center; justify-content:center; width:23px; height:23px; border-radius:50%; background:rgba(var(--primary-rgb),.07); margin-inline-end:.5rem; font-size:.75rem; }
        .product-editor .editor-tab.active .editor-step { background:rgb(var(--primary-rgb)); color:#fff; }
        .product-editor .editor-sidebar { position:sticky; top:90px; grid-column:2; grid-row:1; }
        .product-editor .editor-sidebar h4 { font-size:1rem; font-weight:700; margin:0 0 1.25rem; }
        .product-editor .editor-progress { height:5px; background:rgba(var(--primary-rgb),.1); border-radius:8px; overflow:hidden; margin:1rem 0; }
        .product-editor .editor-progress span { display:block; height:100%; background:rgb(var(--primary-rgb)); transition:width .2s; }
        .product-editor .editor-checks { list-style:none; padding:0; margin:1.25rem 0; color:var(--text-muted); font-size:.875rem; }
        .product-editor .editor-checks li { margin-bottom:1rem; display:flex; gap:.5rem; align-items:start; }
        .product-editor .editor-checks i { color:var(--success-color, #26bf94); }
        .product-editor .editor-footer { position:sticky; bottom:12px; z-index:8; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding:1rem 1.25rem; overflow:visible; }
        .product-editor .editor-empty { text-align:center; color:var(--text-muted); padding:3.5rem 1rem; }
        .product-editor .editor-gallery { display:flex; flex-wrap:wrap; gap:.75rem; }
        .product-editor .editor-image { position:relative; width:110px; }
        .product-editor .editor-image img { width:110px; height:100px; object-fit:contain; border-radius:8px; border:1px solid var(--editor-border); }
        .product-editor .editor-image button { position:absolute; inset-inline-end:4px; top:4px; }
        .product-editor .editor-upload { position:relative; border:1px dashed rgba(var(--primary-rgb),.35); background:rgba(var(--primary-rgb),.025); border-radius:10px; transition:border-color .2s,background .2s; }
        .product-editor .editor-upload:hover,.product-editor .editor-upload:focus-within { border-color:rgb(var(--primary-rgb)); background:rgba(var(--primary-rgb),.06); }
        .product-editor .editor-upload:focus-within { outline:2px solid rgba(var(--primary-rgb),.3); outline-offset:3px; }
        .product-editor .editor-upload-invalid { border-color:var(--danger-color,#dc3545); }
        .product-editor .editor-upload-label { display:flex; align-items:center; flex-wrap:wrap; gap:.85rem; padding:1.1rem; margin:0; cursor:pointer; min-height:116px; }
        .product-editor .editor-upload-icon { display:flex; align-items:center; justify-content:center; width:44px; height:44px; flex-shrink:0; border-radius:12px; background:rgba(var(--primary-rgb),.09); color:rgb(var(--primary-rgb)); font-size:1.5rem; }
        .product-editor .editor-upload-content { display:flex; flex:1; min-width:130px; flex-direction:column; gap:.4rem; overflow-wrap:anywhere; }
        .product-editor .editor-upload-content strong { font-size:.875rem; font-weight:600; }
        .product-editor .editor-upload-button { padding:.5rem .75rem; background:var(--custom-white,#fff); border:1px solid rgba(var(--primary-rgb),.25); color:rgb(var(--primary-rgb)); border-radius:7px; font-size:.8125rem; }
        .product-editor .editor-upload-filename { color:rgb(var(--primary-rgb)); font-size:.8125rem; }
        .product-editor .editor-upload-loading { padding:0 1.1rem 1rem; font-size:.875rem; }
        .product-editor .editor-preview { position:fixed; inset:0; z-index:1060; background:rgba(0,0,0,.45); padding:2rem 1rem; overflow:auto; display:flex; align-items:flex-start; justify-content:center; }
        .product-editor .editor-preview-content { width:min(100%,780px); background:var(--custom-white, #fff); border-radius:14px; box-shadow:0 12px 50px rgba(0,0,0,.2); margin:auto; }
        .product-editor .editor-preview[hidden] { display:none !important; }
        .product-editor .editor-leave-dialog { z-index:1070; }
        .product-editor .editor-leave-dialog .editor-preview-content { max-width:480px; }
        .product-editor .editor-leave-icon { display:flex; align-items:center; justify-content:center; width:58px; height:58px; border-radius:16px; background:rgba(var(--primary-rgb),.09); color:rgb(var(--primary-rgb)); font-size:1.8rem; margin-bottom:1rem; }
        .product-editor .editor-preview table th { width:40%; }
        .product-editor .editor-muted { color:var(--text-muted); font-size:.8125rem; }
        .product-editor .editor-boolean-options .btn { border-radius:7px !important; min-height:42px; }
        @media(min-width:1600px) { .product-editor .editor-grid { grid-template-columns:minmax(0,1fr) 280px; } }
        @media(max-width:991px) { .product-editor .editor-grid { grid-template-columns:minmax(0,1fr); } .product-editor .editor-sidebar { position:static; grid-column:auto; grid-row:auto; } .product-editor .editor-footer { bottom:0; } }
        @media(max-width:575px) { .product-editor .editor-card-body,.product-editor .editor-card-heading { padding:1rem; } .product-editor .editor-tab { padding:.75rem .5rem; } .product-editor .editor-heading .btn { flex:1; min-width:0; white-space:normal; } .product-editor .editor-footer .d-flex { width:100%; } .product-editor .editor-footer .btn { flex:1; min-width:0; white-space:normal; } .product-editor .editor-product > div { min-width:0; overflow-wrap:anywhere; } }
    </style>

    <form wire:submit.prevent="publish" novalidate>
        <div class="editor-heading">
            <div>
                <h2>@lang('marketplace::products.edit_title')</h2>
                <p class="editor-subtitle">@lang('marketplace::products.intro')</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('marketplace:products') }}" class="btn btn-light border">@lang('marketplace::products.back')</a>
                <button type="button" class="btn btn-outline-primary" wire:click="$set('showPreview', true)" wire:loading.attr="disabled"><i class="ri-eye-line me-1"></i>@lang('marketplace::products.preview')</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="publish"><i class="ri-check-line me-1"></i>@lang('marketplace::products.publish')</button>
            </div>
        </div>

        <div class="editor-product">
            @if($image)
                <img src="{{ $image->temporaryUrl() }}" class="editor-thumbnail" alt="{{ $title[app()->getLocale()] ?? '' }}">
            @elseif($image_path)
                <img src="{{ $image_path }}" class="editor-thumbnail" alt="{{ $title[app()->getLocale()] ?? '' }}">
            @else
                <span class="editor-thumbnail d-flex align-items-center justify-content-center text-primary"><i class="ri-box-3-line fs-2"></i></span>
            @endif
            <div>
                <h3>{{ $title[app()->getLocale()] ?? $title['ar'] ?? $title['en'] ?? '' }}</h3>
                <span class="editor-muted">#{{ $product_id }}</span>
                <span class="badge {{ $status === 'active' ? 'bg-success-transparent' : 'bg-warning-transparent' }} ms-2">{{ $status === 'active' ? __('marketplace::home.Active') : __('marketplace::products.draft') }}</span>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert" tabindex="-1">
                <strong>@lang('marketplace::products.fix_errors')</strong>
                <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="editor-grid">
            <main class="editor-main">
                <section class="editor-card" aria-labelledby="product-classification-heading">
                    <div class="editor-card-heading">
                        <h4 id="product-classification-heading">@lang('marketplace::products.classification')</h4>
                        <span class="badge bg-primary-transparent">@lang('marketplace::products.three_levels')</span>
                    </div>
                    <div class="editor-card-body">
                        <div class="row g-3">
                            @foreach([
                                ['main_category_id', 'main_category', $categories, false],
                                ['sub_category_id', 'sub_category', $subcategories, !$main_category_id],
                                ['child_category_id', 'child_category', $childCategories, !$sub_category_id],
                            ] as [$field, $label, $options, $disabled])
                                <div class="col-md-4">
                                    <label class="form-label" for="editor-{{ $field }}">{{ __('marketplace::products.' . $label) }} @if($field === 'main_category_id')<span class="text-danger">*</span>@endif</label>
                                    <select id="editor-{{ $field }}" wire:model="{{ $field }}" class="form-select @error($field) is-invalid @enderror" @if($disabled) disabled @endif>
                                        <option value="">@lang('marketplace::products.select_category')</option>
                                        @foreach($options as $option)<option value="{{ $option->id }}">{{ $option->title }}</option>@endforeach
                                    </select>
                                    @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <nav class="editor-tabs" role="tablist" aria-label="{{ __('marketplace::products.edit_title') }}">
                    @foreach(['details', 'specifications'] as $tab)
                        <button type="button" id="editor-tab-{{ $tab }}" class="editor-tab {{ $activeTab === $tab ? 'active' : '' }}" role="tab" aria-selected="{{ $activeTab === $tab ? 'true' : 'false' }}" aria-controls="editor-panel-{{ $tab }}" wire:click="$set('activeTab', '{{ $tab }}')" wire:loading.attr="disabled">
                            <span class="editor-step">{{ $loop->iteration }}</span>{{ __('marketplace::products.' . $tab) }}
                        </button>
                    @endforeach
                </nav>

                <div id="editor-panel-details" role="tabpanel" aria-labelledby="editor-tab-details" @if($activeTab !== 'details') hidden @endif>
                    <section class="editor-card">
                        <div class="editor-card-heading"><h4>@lang('marketplace::products.basic_details')</h4></div>
                        <div class="editor-card-body">
                            <div class="row g-4">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <div class="col-md-6">
                                        <label class="form-label" for="editor-title-{{ $localeCode }}">{{ __('admin::main.fields.title') }} {{ $properties['native'] }} <span class="text-danger">*</span></label>
                                        <input id="editor-title-{{ $localeCode }}" dir="{{ $localeCode === 'ar' ? 'rtl' : 'ltr' }}" type="text" wire:model.lazy="title.{{ $localeCode }}" class="form-control @error('title.' . $localeCode) is-invalid @enderror">
                                        @error('title.' . $localeCode)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                @endforeach
                                <div class="col-md-6">
                                    <label class="form-label" for="editor-price">{{ __('admin::main.fields.default_price') }} <span class="text-danger">*</span></label>
                                    <input id="editor-price" type="number" min="0" step="0.01" wire:model.lazy="default_price" class="form-control @error('default_price') is-invalid @enderror">
                                    @error('default_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="editor-stock">{{ __('admin::main.fields.stock') }}</label>
                                    <input id="editor-stock" type="number" min="0" step="1" wire:model.lazy="stock" class="form-control @error('stock') is-invalid @enderror">
                                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <div class="col-md-6">
                                        <label class="form-label" for="editor-description-{{ $localeCode }}">{{ __('admin::main.fields.description') }} {{ $properties['native'] }} <span class="text-danger">*</span></label>
                                        <textarea id="editor-description-{{ $localeCode }}" dir="{{ $localeCode === 'ar' ? 'rtl' : 'ltr' }}" rows="4" wire:model.lazy="description.{{ $localeCode }}" class="form-control @error('description.' . $localeCode) is-invalid @enderror"></textarea>
                                        @error('description.' . $localeCode)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="editor-card">
                        <div class="editor-card-heading"><h4>@lang('marketplace::products.images')</h4></div>
                        <div class="editor-card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    @include('marketplace::livewire.product.partials.upload-field', [
                                        'uploadId' => 'editor-image', 'uploadModel' => 'image',
                                        'uploadAccept' => 'image/jpeg,image/png,image/gif,image/svg+xml',
                                        'uploadLabel' => __('marketplace::products.main_image'),
                                        'uploadHint' => __('marketplace::products.images_hint'),
                                        'uploadIcon' => 'ri-image-add-line',
                                        'uploadSelected' => $image ? $image->getClientOriginalName() : '',
                                    ])
                                </div>
                                <div class="col-md-6">
                                    @include('marketplace::livewire.product.partials.upload-field', [
                                        'uploadId' => 'editor-images', 'uploadModel' => 'additional_images',
                                        'uploadAccept' => 'image/jpeg,image/png,image/gif,image/svg+xml', 'uploadMultiple' => true,
                                        'uploadLabel' => __('marketplace::products.additional_images'),
                                        'uploadHint' => __('marketplace::products.images_hint'),
                                        'uploadIcon' => 'ri-image-add-line',
                                        'uploadSelected' => count($additional_images) ? __('marketplace::products.selected_images', ['count' => count($additional_images)]) : '',
                                    ])
                                    @error('additional_images.*')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <div class="editor-gallery">
                                        @foreach($existing_files as $index => $file)
                                            <div class="editor-image" wire:key="editor-existing-image-{{ $file['id'] }}">
                                                <img src="{{ $file['url'] }}" alt="{{ $file['original_name'] ?? '' }}">
                                                <button type="button" class="btn btn-sm btn-danger btn-icon" wire:click="removeExistingFile({{ $index }})" aria-label="{{ __('marketplace::products.remove') }}"><i class="ri-close-line"></i></button>
                                            </div>
                                        @endforeach
                                        @foreach($additional_images as $index => $uploadedImage)
                                            <div class="editor-image" wire:key="editor-new-image-{{ $index }}">
                                                <img src="{{ $uploadedImage->temporaryUrl() }}" alt="{{ $uploadedImage->getClientOriginalName() }}">
                                                <button type="button" class="btn btn-sm btn-danger btn-icon" wire:click="removeAdditionalImage({{ $index }})" aria-label="{{ __('marketplace::products.remove') }}"><i class="ri-close-line"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if($addition_groups->isNotEmpty())
                                    <div class="col-12">
                                        <label class="form-label" for="editor-addition-groups">{{ __('admin::main.fields.addition_groups') }}</label>
                                        <select id="editor-addition-groups" multiple class="form-select" wire:model.defer="addition_group_ids">
                                            @foreach($addition_groups as $group)<option value="{{ $group->id }}">{{ $group->title }}</option>@endforeach
                                        </select>
                                        @error('addition_group_ids.*')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    @if(count($item_prices))
                        <section class="editor-card">
                            <div class="editor-card-heading"><h4>@lang('marketplace::products.prices')</h4></div>
                            <div class="editor-card-body table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead><tr><th>{{ __('admin::main.home.servicer type') }}</th><th>{{ __('marketplace::home.service_price') }}</th><th>{{ __('admin::main.home.status') }}</th></tr></thead>
                                    <tbody>
                                        @foreach($item_prices as $index => $price)
                                            <tr wire:key="editor-price-{{ $price['type_service_id'] }}">
                                                <td>{{ __('admin::home.' . $price['type_service_name']) }}</td>
                                                <td><input type="number" min="0" step="0.01" wire:model.defer="item_prices.{{ $index }}.price" class="form-control" aria-label="{{ $price['type_service_name'] }}">@error('item_prices.' . $index . '.price')<div class="text-danger">{{ $message }}</div>@enderror</td>
                                                <td><input type="checkbox" class="form-check-input" @if($price['status'] === 'active') checked @endif wire:click="updateStatusRow({{ $index }})" aria-label="{{ __('admin::main.home.status') }} {{ $price['type_service_name'] }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif
                </div>

                <div id="editor-panel-specifications" role="tabpanel" aria-labelledby="editor-tab-specifications" @if($activeTab !== 'specifications') hidden @endif>
                    <section class="editor-card editor-specifications-card">
                        <div class="editor-card-heading"><h4>@lang('marketplace::products.specifications')</h4><span class="editor-muted">{{ __('marketplace::products.available_fields', ['count' => $this->visibleFields->count()]) }}</span></div>
                        <div class="editor-card-body">
                            @if($this->visibleFields->isEmpty())
                                <div class="editor-empty"><i class="ri-list-settings-line d-block fs-2 mb-3"></i>{{ __($main_category_id ? 'marketplace::products.no_fields' : 'marketplace::products.choose_category') }}</div>
                            @else
                                @foreach($masterSpecifications->whereIn('id', $master_specification_ids) as $specification)
                                    @php($visibleProperties = $specification->properties->filter(fn ($property) => $this->fieldVisible($property)))
                                    @if($visibleProperties->isNotEmpty())
                                        <div class="mb-4" wire:key="editor-template-{{ $specification->id }}">
                                            <h5 class="fs-15 fw-semibold mb-3">{{ $specification->meta['name_' . app()->getLocale()] ?? $specification->name }}</h5>
                                            <div class="row g-4">
                                                @foreach($visibleProperties as $property)
                                                    @include('marketplace::livewire.product.partials.specification-field', ['property' => $property])
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="border-top pt-4 mt-3">
                                @if($existing_specification_file)
                                    <div class="d-flex gap-3 align-items-center mb-2">
                                        <a href="{{ $existing_specification_file['url'] }}" target="_blank" rel="noopener">{{ $existing_specification_file['original_name'] ?: __('marketplace::products.download') }}</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeSpecificationFile" data-editor-mutation>@lang('marketplace::products.remove')</button>
                                    </div>
                                @endif
                                @include('marketplace::livewire.product.partials.upload-field', [
                                    'uploadId' => 'editor-specification-file', 'uploadModel' => 'specification_file',
                                    'uploadAccept' => 'application/pdf',
                                    'uploadLabel' => __('marketplace::products.specification_file'),
                                    'uploadHint' => __('marketplace::products.file_hint'),
                                    'uploadSelected' => $specification_file ? $specification_file->getClientOriginalName() : '',
                                ])
                            </div>
                        </div>
                    </section>
                </div>

                <div class="editor-card editor-footer">
                    <span class="editor-muted" data-editor-save-state role="status" aria-live="polite">{{ __($saved ? 'marketplace::products.saved' : 'marketplace::products.ready') }}</span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" wire:click="saveDraft" wire:loading.attr="disabled" wire:target="saveDraft,publish">@lang('marketplace::products.draft')</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="publish,saveDraft">
                            <span wire:loading.remove wire:target="publish,saveDraft">@lang('marketplace::products.publish')</span>
                            <span wire:loading wire:target="publish,saveDraft"><span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::main.actions.wait') }}</span>
                        </button>
                    </div>
                </div>
            </main>

            <aside class="editor-sidebar editor-card">
                <div class="editor-card-body">
                    <h4>@lang('marketplace::products.completion')</h4>
                    @php($completion = $this->completion)
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <span class="editor-muted">{{ __('marketplace::products.required_count', $completion) }}</span>
                        <strong class="text-primary fs-22">{{ $completion['percent'] }}%</strong>
                    </div>
                    <div class="editor-progress" role="progressbar" aria-label="{{ __('marketplace::products.completion') }}" aria-valuenow="{{ $completion['percent'] }}" aria-valuemin="0" aria-valuemax="100"><span style="width:{{ $completion['percent'] }}%"></span></div>
                    <p class="editor-muted">@lang('marketplace::products.optional_hint')</p>
                    <ul class="editor-checks">
                        @foreach(['category_hint', 'template_hint', 'empty_hint', 'language_hint'] as $hint)<li><i class="ri-check-line"></i>{{ __('marketplace::products.' . $hint) }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn btn-outline-primary w-100" wire:click="$set('showPreview', true)" wire:loading.attr="disabled"><i class="ri-eye-line me-1"></i>@lang('marketplace::products.preview')</button>
                    @if($status !== 'active')<p class="editor-muted mt-3 mb-0">@lang('marketplace::products.draft_hint')</p>@endif
                </div>
            </aside>
        </div>
    </form>

    @if($showPreview)
        @include('marketplace::livewire.product.partials.preview')
    @endif

    <div id="editor-leave-dialog" class="editor-preview editor-leave-dialog" role="dialog" aria-modal="true"
        aria-labelledby="editor-leave-title" aria-describedby="editor-leave-description" hidden wire:ignore.self>
        <div class="editor-preview-content">
            <div class="editor-card-heading">
                <h4 id="editor-leave-title">@lang('marketplace::products.leave_title')</h4>
                <button type="button" class="btn btn-sm btn-light btn-icon" data-editor-stay aria-label="{{ __('admin::main.actions.close') }}"><i class="ri-close-line"></i></button>
            </div>
            <div class="editor-card-body">
                <span class="editor-leave-icon" aria-hidden="true"><i class="ri-draft-line"></i></span>
                <p id="editor-leave-description" class="mb-0">@lang('marketplace::products.leave_description')</p>
            </div>
            <div class="editor-card-heading border-top justify-content-end">
                <button type="button" class="btn btn-light border" data-editor-stay>@lang('marketplace::products.stay')</button>
                <button type="button" class="btn btn-primary" data-editor-confirm-leave>@lang('marketplace::products.leave_without_saving')</button>
            </div>
        </div>
    </div>

    @once
        @push('js')
            <script>
                (function () {
                    let dirty = false;
                    let previewWasOpen = false;
                    let previousFocus = null;
                    let pendingNavigation = null;
                    const unsavedText = @json(__('marketplace::products.unsaved'));
                    function confirmNavigation(action, reload = false) {
                        const dialog = document.getElementById('editor-leave-dialog');
                        if (!dialog) return;
                        pendingNavigation = action;
                        dialog.querySelector('[data-editor-confirm-leave]').textContent = reload
                            ? @json(__('marketplace::products.reload_without_saving'))
                            : @json(__('marketplace::products.leave_without_saving'));
                        dialog.hidden = false;
                        updateStatus();
                        dialog.querySelector('[data-editor-stay]').focus();
                    }
                    function stayInEditor() {
                        pendingNavigation = null;
                        const dialog = document.getElementById('editor-leave-dialog');
                        if (dialog) dialog.hidden = true;
                        updateStatus();
                    }
                    function markDirty(event) {
                        if (!event.target.closest('#merchant-product-editor form')) return;
                        dirty = true;
                        updateStatus();
                    }
                    function updateStatus() {
                        const status = document.querySelector('[data-editor-save-state]');
                        if (status && dirty) status.textContent = unsavedText;
                        const preview = document.querySelector('#editor-leave-dialog:not([hidden])')
                            || document.querySelector('#merchant-product-editor [role="dialog"]:not([hidden])');
                        if (preview && !previewWasOpen) {
                            previousFocus = document.activeElement;
                            preview.querySelector('button')?.focus();
                        } else if (!preview && previewWasOpen && previousFocus?.isConnected) {
                            previousFocus.focus();
                        }
                        previewWasOpen = Boolean(preview);
                    }
                    document.addEventListener('keydown', function (event) {
                        const editor = document.getElementById('merchant-product-editor');
                        if (dirty && editor && (event.key === 'F5' || ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'r'))) {
                            event.preventDefault();
                            confirmNavigation(function () { window.location.reload(); }, true);
                            return;
                        }
                        if (event.key === 'Escape') {
                            const leaveDialog = document.getElementById('editor-leave-dialog');
                            if (leaveDialog && !leaveDialog.hidden) {
                                event.preventDefault();
                                stayInEditor();
                                return;
                            }
                            document.querySelectorAll('#merchant-product-editor .editor-multiselect[open]').forEach(function (menu) {
                                menu.removeAttribute('open');
                                menu.querySelector('summary').focus();
                            });
                        }
                        const preview = document.querySelector('#editor-leave-dialog:not([hidden])')
                            || document.querySelector('#merchant-product-editor [role="dialog"]:not([hidden])');
                        if (!preview || event.key !== 'Tab') return;
                        const items = preview.querySelectorAll('button, a[href], input, select, textarea, [tabindex="0"]');
                        const first = items[0], last = items[items.length - 1];
                        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
                    });
                    document.addEventListener('input', markDirty);
                    document.addEventListener('change', markDirty);
                    document.addEventListener('click', function (event) {
                        document.querySelectorAll('#merchant-product-editor .editor-multiselect[open]').forEach(function (menu) {
                            if (!menu.contains(event.target)) menu.removeAttribute('open');
                        });
                        if (event.target.closest('#merchant-product-editor .editor-image button, #merchant-product-editor [data-editor-mutation]')) {
                            dirty = true;
                        }
                    });
                    document.addEventListener('click', function (event) {
                        const editor = document.getElementById('merchant-product-editor');
                        if (!editor) return;
                        if (event.target.closest('[data-editor-stay]') || event.target.id === 'editor-leave-dialog') {
                            event.preventDefault();
                            stayInEditor();
                            return;
                        }
                        if (event.target.closest('[data-editor-confirm-leave]')) {
                            event.preventDefault();
                            const navigate = pendingNavigation;
                            if (navigate) {
                                dirty = false;
                                stayInEditor();
                                navigate();
                            }
                            return;
                        }
                        if (!dirty || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                        const anchor = event.target.closest('a[href]');
                        if (!anchor || anchor.hasAttribute('download') || (anchor.target && anchor.target !== '_self')) return;
                        const url = new URL(anchor.href, window.location.href);
                        if (!['http:', 'https:'].includes(url.protocol)) return;
                        if (url.hash && url.origin === window.location.origin && url.pathname === window.location.pathname && url.search === window.location.search) return;
                        event.preventDefault();
                        confirmNavigation(function () { window.location.assign(url.href); });
                    }, true);
                    window.addEventListener('product-editor-saved', function () {
                        dirty = false;
                        stayInEditor();
                    });
                    if (window.Livewire) Livewire.hook('message.processed', updateStatus);
                })();
            </script>
        @endpush
    @endonce
</div>
