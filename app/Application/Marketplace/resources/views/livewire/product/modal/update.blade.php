<div>
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/filepond/filepond.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/filepond-plugin-image-edit/filepond-plugin-image-edit.min.css')}}">

    <div class="modal fade" id="updateProduct" wire:ignore.self data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 720px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">{{ __('admin::main.actions.update') }} {{ __('admin::main.lang.product') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>

                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card custom-card">
                                    <div class="card-body p-0">
                                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                                            <div>
                                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist" wire:ignore>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="basicProductInfo-tab" data-bs-toggle="tab"
                                                            data-bs-target="#basicProductInfo-tab-pane" type="button" role="tab"
                                                            aria-controls="basicProductInfo-tab-pane" aria-selected="true">
                                                            <i class="ri-bill-line me-1 align-middle d-inline-block"></i>
                                                            {{ __('admin::main.home.Item Basic Info') }}
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="productImages-tab" data-bs-toggle="tab"
                                                            data-bs-target="#productImages-tab-pane" type="button" role="tab" tabindex="-1"
                                                            aria-controls="productImages-tab-pane" aria-selected="false">
                                                            <i class="ri-image-line me-1 align-middle d-inline-block"></i>
                                                            {{ __('admin::main.home.Item Images') }}
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" id="productPrices-tab" data-bs-toggle="tab"
                                                            data-bs-target="#productPrices-tab-pane" type="button" role="tab" tabindex="-1"
                                                            aria-controls="productPrices-tab-pane" aria-selected="false">
                                                            <i class="ri-price-tag-3-line me-1 align-middle d-inline-block"></i>
                                                            {{ __('admin::main.home.Item Prices') }}
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane active show fade p-0 border-0" id="basicProductInfo-tab-pane"
                                    role="tabpanel" aria-labelledby="basicProductInfo-tab" tabindex="0" wire:ignore.self>
                                    <div class="row gx-5">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                            <div class="card custom-card shadow-none mb-0">
                                                <div class="card-body p-0">
                                                    <div class="row gy-3">
                                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                        @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                        <div class="{{ $localeCode === 'ar' ? 'col-xl-6' : 'col-xl-5' }}">
                                                            <label for="update-title-{{$localeCode}}" class="form-label text-black">
                                                                {{ __('admin::main.fields.title') }} {{$properties['native']}} <span class="text-danger">*</span>
                                                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-update-title-{{$localeCode}}"
                                                                    onclick="translateLocalizedField('update-title-{{$localeCode}}','update-title-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                                    title="{{ __('admin::main.actions.translate') }}"
                                                                    style="cursor: pointer;">
                                                                    <i class="ri-refresh-line"></i>
                                                                </button>
                                                            </label>
                                                            <input type="text" class="form-control @error(" title." . $localeCode) is-invalid @enderror"
                                                                id="update-title-{{$localeCode}}"
                                                                placeholder="{{ __('admin::main.fields.title') }}"
                                                                wire:model.defer="title.{{$localeCode}}"
                                                                onblur="translateLocalizedField('update-title-{{$localeCode}}','update-title-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)">
                                                            @error("title." . $localeCode)
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        @endforeach

                                                        <div class="col-xl-1">
                                                            <label for="product-active" class="form-label text-black">
                                                                {{ __('admin::main.fields.status') }}
                                                            </label>
                                                            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                                <input id="product-active"
                                                                    type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                                <label for="product-active" class="label-success"
                                                                    wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                            </div>
                                                        </div>

                                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                        @php($otherLocale = $localeCode === 'ar' ? 'en' : 'ar')
                                                        <div class="col-xl-6">
                                                            <label for="update-description-{{$localeCode}}" class="form-label text-black">
                                                                {{ __('admin::main.fields.description') }} {{$properties['native']}} <span class="text-danger">*</span>
                                                                <button type="button" class="p-0 border-0 bg-transparent text-primary align-middle ms-1" id="translate-update-description-{{$localeCode}}"
                                                                    onclick="translateLocalizedField('update-description-{{$localeCode}}','update-description-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',true)"
                                                                    title="{{ __('admin::main.actions.translate') }}"
                                                                    style="cursor: pointer;">
                                                                    <i class="ri-refresh-line"></i>
                                                                </button>
                                                            </label>
                                                            <textarea class="form-control @error(" description." . $localeCode) is-invalid @enderror"
                                                                id="update-description-{{$localeCode}}"
                                                                placeholder="{{ __('admin::main.fields.description') }}"
                                                                wire:model.defer="description.{{$localeCode}}"
                                                                rows="3"
                                                                onblur="translateLocalizedField('update-description-{{$localeCode}}','update-description-{{$otherLocale}}','{{$localeCode}}','{{$otherLocale}}',false)"></textarea>
                                                            @error("description." . $localeCode)
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        @endforeach

                                                        <div class="col-md-4">
                                                            <label for="main_category_id" class="form-label text-black">
                                                                {{ __('admin::main.fields.category') }} <span class="text-danger">*</span>
                                                            </label>
                                                            <select class="form-control @error('main_category_id') is-invalid @enderror"
                                                                wire:model="main_category_id">
                                                                <option value="">-- {{ __('admin::main.actions.select') }} --</option>
                                                                @foreach($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('main_category_id')
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="sub_category_id" class="form-label text-black">
                                                                {{ __('admin::main.fields.subcategory') }}
                                                            </label>
                                                            <select class="form-control @error('sub_category_id') is-invalid @enderror"
                                                                wire:model="sub_category_id"
                                                                {{ !$subcategories->count() ? 'disabled' : '' }}>
                                                                <option value="">-- {{ __('admin::main.actions.select') }} --</option>
                                                                @foreach($subcategories as $subcategory)
                                                                <option value="{{ $subcategory->id }}">{{ $subcategory->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('sub_category_id')
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="child_category_id" class="form-label text-black">
                                                                {{ __('admin::main.fields.child_category') }}
                                                            </label>
                                                            <select class="form-control @error('child_category_id') is-invalid @enderror"
                                                                wire:model="child_category_id"
                                                                {{ !$childCategories->count() ? 'disabled' : '' }}>
                                                                <option value="">-- {{ __('admin::main.actions.select') }} --</option>
                                                                @foreach($childCategories as $childCategory)
                                                                <option value="{{ $childCategory->id }}">{{ $childCategory->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('child_category_id')
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-xl-6">
                                                            <label for="default_price" class="form-label text-black">
                                                                {{ __('admin::main.fields.default_price') }} <span class="text-danger">*</span>
                                                            </label>
<input type="number" class="form-control @error('default_price') is-invalid @enderror"
min="0" step="0.01"
id="default_price"
placeholder="{{ __('admin::main.fields.default_price') }}"
wire:model.defer="default_price">
@error('default_price')
<span class="text-danger d-block mt-2">{{ $message }}</span>
@enderror
</div>

<div class="col-xl-4">
<label for="stock" class="form-label text-black">
{{ __('admin::main.fields.stock') }}
</label>
<input type="number" class="form-control @error('stock') is-invalid @enderror"
min="0" step="1"
id="stock"
placeholder="{{ __('admin::main.fields.stock') }}"
wire:model.defer="stock">
<small class="form-text text-muted mt-1">{{ __('admin::main.fields.stock_hint') }}</small>
@error('stock')
<span class="text-danger d-block mt-2">{{ $message }}</span>
@enderror
</div>

                                                        <!-- <div class="col-xl-4">
                                                            <label for="calories" class="form-label text-black">
                                                                {{ __('admin::main.fields.calories') }}
                                                            </label>
                                                            <input type="number" class="form-control @error('calories') is-invalid @enderror"
                                                                   min="0"
                                                                   id="calories"
                                                                   placeholder="{{ __('admin::main.fields.calories') }}"
                                                                   wire:model.defer="calories">
                                                            @error('calories')
                                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-xl-4">
                                                            <label for="preparing_time" class="form-label text-black">
                                                                {{ __('admin::main.fields.preparing_time') }}
                                                            </label>
                                                            <input type="number" class="form-control @error('preparing_time') is-invalid @enderror"
                                                                   min="0"
                                                                   id="preparing_time"
                                                                   placeholder="{{ __('admin::main.fields.preparing_time') }}"
                                                                   wire:model.defer="preparing_time">
                                                            @error('preparing_time')
                                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div> -->

                                                        <div class="col-xl-6">
                                                            <label for="image" class="form-label text-black">
                                                                {{ __('admin::main.fields.image') }}
                                                            </label>
                                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                                id="image"
                                                                wire:model.defer="image">
                                                            @error('image')
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        @if ($image)
                                                        <div class="col-xl-12">
                                                            <div class="mt-2 d-flex justify-content-center">
                                                                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" width="200">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if($image_path)
                                                        <div class="col-xl-12">
                                                            <label class="form-label text-black">{{ __('admin::main.home.current_image') }}</label>
                                                            <div class="mt-2 d-flex justify-content-center">
                                                                <img src="{{ $image_path }}" class="img-thumbnail" width="200">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        <!-- <div class="col-xl-6">
                                                            <label for="addition_group_ids" class="form-label text-black">
                                                                {{ __('admin::main.home.additions_groups') }}
                                                            </label>
                                                            <br>
                                                            @foreach ($addition_groups as $addition_group)
                                                            <input class="form-check-input mt-2"
                                                                id="addition_group_ids{{ $addition_group->id }}"
                                                                type="checkbox"
                                                                wire:model="addition_group_ids"
                                                                value="{{ $addition_group->id }}">
                                                            <label class="form-check-label mt-2"
                                                                for="addition_group_ids{{ $addition_group->id }}"
                                                                style="cursor: pointer">
                                                                {{ $addition_group->title }}
                                                            </label>
                                                            <br>
                                                            @endforeach
                                                            @error("addition_group_ids")
                                                            <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                            @enderror
                                                        </div> -->


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="productImages-tab-pane"
                                    role="tabpanel" aria-labelledby="productImages-tab" tabindex="1">
                                    <div class="row gx-5">
                                        <div class="col-xl-10">
                                            @if(count($existing_files) > 0)
                                            <div class="mb-4">
                                                <h6 class="mb-3">{{ __('admin::main.home.existing_images') }}</h6>
                                                <div class="row">
                                                    @foreach($existing_files as $index => $file)
                                                    <div class="col-md-3 mb-3 position-relative">
                                                        <div class="card">
                                                            <img src="{{ $file['url'] }}"
                                                                class="card-img-top"
                                                                alt="{{ $file['original_name'] }}"
                                                                style="height: 150px; object-fit: cover;">
                                                            <div class="card-body text-center p-2">
                                                                <small class="text-muted d-block mb-2">{{ \Illuminate\Support\Str::limit($file['original_name'], 20) }}</small>
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                    wire:click="removeExistingFile({{ $index }})"
                                                                    wire:confirm="{{ __('admin::main.messages.are_you_sure_delete_image') }}">
                                                                    <i class="ri-delete-bin-line"></i> {{ __('admin::main.actions.delete') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif

                                            <div class="mb-3">
                                                <h6 class="mb-3">{{ __('admin::main.home.add_new_images') }}</h6>
                                                <input type="file"
                                                    class="form-control"
                                                    wire:model="additional_images"
                                                    accept="image/*">
                                                @error('additional_images.*')
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            @if($additional_images)
                                            <div class="row">
                                                @foreach($additional_images as $index => $image)
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <img src="{{ $image->temporaryUrl() }}"
                                                            class="card-img-top"
                                                            style="height: 150px; object-fit: cover;">
                                                        <div class="card-body text-center p-2">
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                wire:click="removeAdditionalImage({{ $index }})">
                                                                <i class="ri-delete-bin-line"></i> {{ __('admin::main.actions.delete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="productPrices-tab-pane"
                                    role="tabpanel" aria-labelledby="productPrices-tab" tabindex="2" wire:ignore.self>
                                    <div class="row gy-3 mt-2">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <table class="table">
                                                    <thead>
                                                        <th>{{ __('admin::main.home.servicer type') }}</th>
                                                        <th>{{ __('admin::main.home.Default Price') }}</th>
                                                        <th>{{ __('admin::main.home.status') }}</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($item_prices as $index => $item_price)
                                                        <tr>
                                                            <input wire:model.defer="item_prices.{{$index}}.type_service_id" class="form-control" type="hidden" />
                                                            <td>
                                                                <input value="{{ $item_prices[$index]['type_service_name'] }}" class="form-control" disabled />
                                                            </td>
                                                            <td>
                                                                <input wire:model.defer="item_prices.{{$index}}.price" class="form-control" type="number" min="0" step="0.01" />
                                                            </td>
                                                            <td>
                                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                                    <input id="{{'active'.$index}}"
                                                                        type="checkbox" {{ $item_prices[$index]['status'] == "active"  ? 'checked' : '' }}>
                                                                    <label for="{{'active'.$index}}" class="label-success"
                                                                        wire:click="updateStatusRow('{{ $index }}')" wire:ignore.self></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store, image">
                                {{ __('admin::main.actions.close') }}
                            </button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store, image">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store, image">
                                    {{ __('admin::main.actions.update') }}
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="store, image">
                                    {{ __('admin::main.actions.wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        initUpdateModal();
    });

    function initUpdateModal() {
        const updateModal = document.getElementById('updateProduct');

        if (!updateModal) return;

        const tabTriggers = updateModal.querySelectorAll('[data-bs-toggle="tab"]');
        const tabPanes = updateModal.querySelectorAll('.tab-pane');

        tabTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('data-bs-target');
                const targetPane = updateModal.querySelector(targetId);

                if (targetPane) {
                    tabPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    tabTriggers.forEach(tab => {
                        tab.classList.remove('active');
                    });

                    targetPane.classList.add('show', 'active');
                    this.classList.add('active');
                }
            });
        });

        updateModal.addEventListener('show.bs.modal', function() {
            setTimeout(() => {
                const firstTab = updateModal.querySelector('#basicProductInfo-tab');
                const firstPane = updateModal.querySelector('#basicProductInfo-tab-pane');

                if (firstTab && firstPane) {
                    tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
                    tabTriggers.forEach(tab => tab.classList.remove('active'));

                    firstTab.classList.add('active');
                    firstPane.classList.add('show', 'active');
                }
            }, 100);
        });
    }

    document.addEventListener('livewire:load', function() {
        initUpdateModal();
    });

    document.addEventListener('livewire:update', function() {
        setTimeout(initUpdateModal, 100);
        setTimeout(reapplyActiveUpdateTab, 100);
    });

    function reapplyActiveUpdateTab() {
        const updateModal = document.getElementById('updateProduct');
        if (!updateModal) return;

        const activeTab = updateModal.querySelector('.nav-tabs .nav-link.active');
        if (!activeTab) return;

        const targetId = activeTab.getAttribute('data-bs-target');
        const targetPane = updateModal.querySelector(targetId);
        if (!targetPane) return;

        updateModal.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));
        targetPane.classList.add('show', 'active');
    }

    window.addEventListener('openModal', function(event) {
        if (event.detail === '#updateProduct') {
            setTimeout(initUpdateModal, 200);
        }
    });
</script>