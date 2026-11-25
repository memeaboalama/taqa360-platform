<form wire:submit.prevent="save">
    <div class="editor-header d-flex align-items-start justify-content-between">
        <div>
            <h2>@lang('admin::home.' . ($mode === 'edit' ? 'specification_edit_template' : ($mode === 'copy' ? 'specification_copy_template' : 'specification_new_template')))</h2>
            <div class="editor-meta">
                <span class="badge {{ $status === 'active' ? 'bg-success-transparent' : ($status === 'rejected' ? 'bg-danger-transparent' : 'bg-warning-transparent') }}">
                    @lang('admin::home.specification_status_' . $status)
                </span>
                <span title="{{ __('admin::home.specification_template_code_hint') }}">{{ __('admin::home.specification_template_code') }}: {{ $code ?: '—' }}</span>
                <span>@lang('admin::home.specification_unsaved_changes')</span>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin:vars.specifications') }}" class="btn btn-light border">@lang('admin::home.Back')</a>
            <button type="button" class="btn btn-light border" wire:click="togglePreview">
                <i class="ri-eye-line me-1"></i>@lang('admin::home.specification_preview')
            </button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">@lang('admin::home.specification_save_changes') <i class="ri-check-line ms-1"></i></span>
                <span wire:loading wire:target="save">@lang('admin::home.Wait') <span class="spinner-border spinner-border-sm"></span></span>
            </button>
        </div>
    </div>

    <nav class="editor-tabs" aria-label="@lang('admin::home.specification_editor_tabs')">
        <button type="button" class="editor-tab {{ $activeTab === 'settings' ? 'active' : '' }}" wire:click="$set('activeTab', 'settings')">
            @lang('admin::home.specification_settings_tab')
        </button>
        <button type="button" class="editor-tab {{ $activeTab === 'categories' ? 'active' : '' }}" wire:click="$set('activeTab', 'categories')">
            @lang('admin::home.specification_categories_tab') <span class="editor-tab-count">{{ count($categorySelections) }}</span>
        </button>
        <button type="button" class="editor-tab {{ $activeTab === 'fields' ? 'active' : '' }}" wire:click="$set('activeTab', 'fields')">
            @lang('admin::home.specification_fields_tab') <span class="editor-tab-count">{{ count($properties) }}</span>
        </button>
    </nav>

    @error('publishRequirements')
        <div class="alert alert-danger py-2">{{ $message }}</div>
    @enderror

    @if($activeTab === 'settings')
        <section class="editor-card">
            <div class="editor-card-header">
                <strong>@lang('admin::home.specification_template_data')</strong>
                <span class="badge bg-light text-primary" title="{{ __('admin::home.specification_template_code_hint') }}">{{ __('admin::home.specification_template_code') }}: {{ $code ?: '—' }}</span>
            </div>
            <div class="editor-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">@lang('admin::home.specification_name_ar') <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nameAr') is-invalid @enderror" wire:model="nameAr">
                        @error('nameAr')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('admin::home.specification_name_en') <span class="text-danger">*</span></label>
                        <input type="text" dir="ltr" class="form-control @error('nameEn') is-invalid @enderror" wire:model.defer="nameEn">
                        @error('nameEn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">@lang('admin::home.specification_description')</label>
                        <textarea class="form-control" wire:model.defer="description"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('admin::home.specification_family')</label>
                        <input type="text" class="form-control" wire:model="family">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('admin::home.specification_template_status')</label>
                        <select class="form-select" wire:model="status">
                            <option value="inactive">@lang('admin::home.specification_status_inactive')</option>
                            <option value="active">@lang('admin::home.specification_status_active')</option>
                            <option value="draft">@lang('admin::home.specification_status_draft')</option>
                            <option value="rejected">@lang('admin::home.specification_status_rejected')</option>
                        </select>
                    </div>
                </div>
                <div class="editor-note mt-4">
                    <i class="ri-information-line me-1"></i>
                    @lang('admin::home.specification_draft_help')
                </div>
            </div>
        </section>
    @elseif($activeTab === 'fields')
        <div class="row g-3">
            <div class="col-lg-8">
                <section class="editor-card">
                    <div class="editor-card-header">
                        <div>
                            <strong>@lang('admin::home.specification_fields_heading')</strong>
                            <div class="text-muted small mt-1">@lang('admin::home.specification_fields_help')</div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" wire:click="addProperty">
                            @lang('admin::home.specification_add_field') <i class="ri-add-line ms-1"></i>
                        </button>
                    </div>
                    <div class="editor-card-body">
                        <p class="text-muted small mb-3"><i class="ri-drag-move-2-line me-1"></i>@lang('admin::home.specification_drag_fields_help')</p>
                        <div data-specification-sortable wire:loading.class="field-list-saving" wire:target="reorderProperties">
                        @forelse($properties as $index => $property)
                            <div class="field-list-row mb-3" data-field-key="{{ $property['field_key'] }}" wire:key="specification-property-{{ $property['field_key'] }}">
                                <div class="field-list-details">
                                    <button type="button" class="field-drag-handle btn" aria-label="{{ __('admin::home.specification_drag_field') }}" title="{{ __('admin::home.specification_drag_field') }}">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
                                            <circle cx="8" cy="5" r="2.1"/><circle cx="16" cy="5" r="2.1"/>
                                            <circle cx="8" cy="12" r="2.1"/><circle cx="16" cy="12" r="2.1"/>
                                            <circle cx="8" cy="19" r="2.1"/><circle cx="16" cy="19" r="2.1"/>
                                        </svg>
                                    </button>
                                    <div>
                                        <strong>{{ $property['name_ar'] }}</strong>
                                        <div class="field-list-meta" dir="auto">
                                            <span dir="ltr">{{ $property['name_en'] }}</span>
                                            <span>{{ $propertyInputTypes[$property['input_type'] ?? 'short_text'] ?? '' }}</span>
                                            <span title="{{ __('admin::home.specification_field_column_span') }}">{{ $property['column_span'] ?? (($property['input_type'] ?? '') === 'long_text' ? 12 : 6) }} / 12</span>
                                            @if(!empty($property['unit']))
                                                <span>{{ $property['unit'] }}</span>
                                            @endif
                                            @if(!empty($property['group']))
                                                <span>{{ $property['group'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="field-list-actions">
                                    <button type="button" class="btn btn-sm btn-light" wire:click="removeProperty({{ $index }})"
                                            aria-label="@lang('admin::home.specification_delete_field')" title="@lang('admin::home.specification_delete_field')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" wire:click="editProperty({{ $index }})"
                                            aria-label="@lang('admin::home.specification_edit_field')" title="@lang('admin::home.specification_edit_field')">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" wire:click="movePropertyDown({{ $index }})"
                                            @disabled($index === count($properties) - 1)
                                            aria-label="@lang('admin::home.specification_move_field_down')" title="@lang('admin::home.specification_move_field_down')">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" wire:click="movePropertyUp({{ $index }})"
                                            @disabled($index === 0)
                                            aria-label="@lang('admin::home.specification_move_field_up')" title="@lang('admin::home.specification_move_field_up')">
                                        <i class="ri-arrow-up-s-line"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="preview-placeholder">@lang('admin::home.specification_empty_fields')</div>
                        @endforelse
                        </div>
                        <div class="editor-note mt-3">
                            <i class="ri-links-line me-1"></i>
                            <strong>@lang('admin::home.specification_shared_fields_title')</strong>
                            <div>@lang('admin::home.specification_shared_fields_help')</div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-4">
                <aside class="preview-card">
                    <div class="preview-card-header">
                        <strong>@lang('admin::home.specification_quick_preview')</strong>
                        <i class="ri-eye-line"></i>
                    </div>
                    <div class="preview-card-body">
                        <small class="text-muted">@lang('admin::home.specification_preview_product')</small>
                        <div class="preview-product d-flex align-items-center gap-2 mt-2">
                            <span class="avatar avatar-md bg-light"><i class="ri-box-3-line"></i></span>
                            <div>
                                <div class="fw-semibold small">{{ $nameAr ?: __('admin::home.specification_preview_product_name') }}</div>
                                <small class="text-muted">{{ $family ?: __('admin::home.specification_family') }}</small>
                            </div>
                        </div>
                        @forelse($properties as $property)
                            @if(trim($property['name_ar'] ?? '') !== '')
                                <div class="quick-preview-field mb-3">
                                    <label class="form-label mb-1">
                                        {{ app()->getLocale() === 'ar' ? $property['name_ar'] : $property['name_en'] }}
                                        @if(!empty($property['is_required']))<span class="text-danger">*</span>@endif
                                    </label>
                                    @switch($property['input_type'] ?? 'short_text')
                                        @case('long_text')
                                            <textarea class="form-control form-control-sm" rows="2" disabled placeholder="@lang('admin::home.specification_preview_value')"></textarea>
                                            @break
                                        @case('select')
                                        @case('multiselect')
                                            @include('admin::livewire.specification.partials.choice-preview')
                                            @break
                                        @case('boolean')
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" disabled>@lang('admin::home.specification_preview_yes')</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" disabled>@lang('admin::home.specification_preview_no')</button>
                                            </div>
                                            @break
                                        @case('attachment')
                                            <button type="button" class="btn btn-light border btn-sm w-100 text-start" disabled>
                                                <i class="ri-attachment-2 me-1"></i>@lang('admin::home.specification_preview_attachment')
                                            </button>
                                            @break
                                        @case('number_range')
                                            <div class="d-flex gap-2">
                                                <input type="number" class="form-control form-control-sm" disabled placeholder="@lang('admin::home.specification_preview_min')">
                                                <input type="number" class="form-control form-control-sm" disabled placeholder="@lang('admin::home.specification_preview_max')">
                                            </div>
                                            @break
                                        @default
                                            <input type="{{ ($property['input_type'] ?? '') === 'date' ? 'date' : (in_array($property['input_type'] ?? '', ['decimal', 'integer'], true) ? 'number' : 'text') }}"
                                                   @if(($property['input_type'] ?? '') === 'decimal') step="any" @elseif(($property['input_type'] ?? '') === 'integer') step="1" @endif
                                                   class="form-control form-control-sm" disabled placeholder="@lang('admin::home.specification_preview_value')">
                                    @endswitch
                                    @if(!empty($property['unit']))
                                        <small class="text-muted">{{ $property['unit'] }}</small>
                                    @endif
                                </div>
                            @endif
                        @empty
                            <div class="preview-placeholder">@lang('admin::home.specification_empty_preview')</div>
                        @endforelse
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" wire:click="togglePreview">
                            <i class="ri-eye-line me-1"></i>@lang('admin::home.specification_preview_merchant_form')
                        </button>
                    </div>
                </aside>
            </div>
        </div>
    @else
        <section class="editor-card">
            <div class="editor-card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="spec-stat-card">
                            <div class="spec-stat-value">{{ count($categorySelections) }}</div>
                            <div class="spec-stat-label">@lang('admin::home.specification_direct_links')</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-stat-card">
                            <div class="spec-stat-value">{{ number_format($selectedSmallCategoryCount) }}</div>
                            <div class="spec-stat-label">@lang('admin::home.specification_small_categories_using_template')</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="spec-stat-card">
                            <div class="spec-stat-value">3</div>
                            <div class="spec-stat-label">@lang('admin::home.specification_category_levels')</div>
                        </div>
                    </div>
                </div>

                <div class="editor-note mb-3"><i class="ri-node-tree me-1"></i>@lang('admin::home.specification_category_link_help')</div>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
                    <input type="search" class="form-control" wire:model.debounce.300ms="categorySearch" maxlength="100"
                           placeholder="@lang('admin::home.specification_category_search')">
                </div>

                <div class="d-flex flex-column gap-2">
                    @forelse($categories as $category)
                        @php
                            $categoryState = $categoryStates['category'][$category->id] ?? 'unchecked';
                            $categorySelected = $categoryState === 'checked';
                        @endphp
                        <div class="tree-row">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($category->subcategories_count > 0)
                                        <button type="button" class="btn btn-sm btn-link text-muted p-0" wire:click="toggleCategoryExpanded({{ $category->id }})" aria-label="@lang('admin::home.specification_expand_category')">
                                            <i class="ri-{{ in_array($category->id, $expandedCategoryIds, true) ? 'arrow-down-s-line' : 'arrow-left-s-line' }}"></i>
                                        </button>
                                    @else
                                        <span class="d-inline-block" style="width: 20px"></span>
                                    @endif
                                    <input class="form-check-input m-0" type="checkbox" wire:click="toggleCategory('category', {{ $category->id }})" @checked($categorySelected) x-data x-effect="$el.indeterminate = @js($categoryState === 'partial')" aria-checked="{{ $categoryState === 'partial' ? 'mixed' : ($categorySelected ? 'true' : 'false') }}">
                                    <div>
                                        <div class="tree-label">{{ $category->title }}</div>
                                        <div class="tree-code">{{ $category->slug }}</div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    @if($category->subcategories_count > 0)
                                        <span class="badge bg-light text-muted">{{ $category->subcategories_count }} @lang('admin::home.specification_subcategories_count')</span>
                                    @endif
                                    @if($categorySelected)
                                        <span class="badge bg-primary-transparent">@lang('admin::home.specification_linked')</span>
                                    @endif
                                </div>
                            </div>

                            @if(in_array($category->id, $expandedCategoryIds, true))
                                <div class="tree-children mt-3 d-flex flex-column gap-2">
                                    @foreach($subcategoriesByCategory[$category->id] ?? [] as $subcategory)
                                        @php
                                            $subcategoryState = $categoryStates['subcategory'][$subcategory->id] ?? 'unchecked';
                                            $subcategorySelected = $subcategoryState === 'checked';
                                        @endphp
                                        <div class="tree-row">
                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($subcategory->child_categories_count > 0)
                                                        <button type="button" class="btn btn-sm btn-link text-muted p-0" wire:click="toggleSubcategoryExpanded({{ $subcategory->id }})">
                                                            <i class="ri-{{ in_array($subcategory->id, $expandedSubcategoryIds, true) ? 'arrow-down-s-line' : 'arrow-left-s-line' }}"></i>
                                                        </button>
                                                    @else
                                                        <span class="d-inline-block" style="width: 20px"></span>
                                                    @endif
                                                    <input class="form-check-input m-0" type="checkbox" wire:click="toggleCategory('subcategory', {{ $subcategory->id }})" @checked($subcategorySelected) x-data x-effect="$el.indeterminate = @js($subcategoryState === 'partial')" aria-checked="{{ $subcategoryState === 'partial' ? 'mixed' : ($subcategorySelected ? 'true' : 'false') }}">
                                                    <div>
                                                        <div class="tree-label fw-normal">{{ $subcategory->title }}</div>
                                                        <div class="tree-code">{{ $subcategory->slug }}</div>
                                                    </div>
                                                </div>
                                                @if($subcategory->child_categories_count > 0)
                                                    <span class="badge bg-light text-muted">{{ $subcategory->child_categories_count }} @lang('admin::home.specification_child_categories_count')</span>
                                                @endif
                                            </div>

                                            @if(in_array($subcategory->id, $expandedSubcategoryIds, true))
                                                <div class="tree-children mt-3 d-flex flex-column gap-2">
                                                    @foreach($childCategoriesBySubcategory[$subcategory->id] ?? [] as $childCategory)
                                                        @php
                                                            $childCategoryState = $categoryStates['child_category'][$childCategory->id] ?? 'unchecked';
                                                            $childCategorySelected = $childCategoryState === 'checked';
                                                        @endphp
                                                        <label class="d-flex align-items-center gap-2 py-1">
                                                            <input class="form-check-input m-0" type="checkbox" wire:click="toggleCategory('child_category', {{ $childCategory->id }})" @checked($childCategorySelected)>
                                                            <span class="tree-label fw-normal">{{ $childCategory->title }}</span>
                                                        </label>
                                                    @endforeach
                                                    @if($hasMoreChildCategories[$subcategory->id] ?? false)
                                                        <button type="button" class="btn btn-sm btn-outline-primary align-self-start" wire:click="loadMoreChildCategories({{ $subcategory->id }})">
                                                            @lang('admin::home.specification_load_more')
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($hasMoreSubcategories[$category->id] ?? false)
                                        <button type="button" class="btn btn-sm btn-outline-primary align-self-start" wire:click="loadMoreSubcategories({{ $category->id }})">
                                            @lang('admin::home.specification_load_more')
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="preview-placeholder">@lang('admin::home.specification_no_categories')</div>
                    @endforelse
                </div>

                @if($hasMoreCategories)
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="loadMoreCategories" wire:loading.attr="disabled" wire:target="loadMoreCategories">
                            @lang('admin::home.specification_load_more')
                        </button>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if($showPropertyModal)
        <div class="field-modal-backdrop @if(app()->getLocale() === 'ar') field-modal-backdrop--from-left @else field-modal-backdrop--from-right @endif" wire:click.self="closePropertyModal">
            <section class="field-modal" role="dialog" aria-modal="true" aria-labelledby="field-modal-title">
                <header class="field-modal-header">
                    <div>
                        <h3 id="field-modal-title">
                            @lang($editingPropertyIndex === null ? 'admin::home.specification_add_new_field' : 'admin::home.specification_edit_field')
                        </h3>
                        <p>@lang('admin::home.specification_field_modal_help')</p>
                    </div>
                    <button type="button" class="btn btn-link text-muted p-1" wire:click="closePropertyModal"
                            aria-label="@lang('admin::home.specification_close_field_modal')">
                        <i class="ri-close-line fs-5"></i>
                    </button>
                </header>

                <div class="field-modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('admin::home.specification_field_name_ar') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('propertyForm.name_ar') is-invalid @enderror"
                                   wire:model.defer="propertyForm.name_ar" placeholder="@lang('admin::home.specification_field_name_ar_placeholder')">
                            @error('propertyForm.name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('admin::home.specification_field_name_en') <span class="text-danger">*</span></label>
                            <input type="text" dir="ltr" class="form-control @error('propertyForm.name_en') is-invalid @enderror"
                                   wire:model.defer="propertyForm.name_en" placeholder="@lang('admin::home.specification_field_name_en_placeholder')">
                            @error('propertyForm.name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('admin::home.specification_input_type') <span class="text-danger">*</span></label>
                            <select class="form-select @error('propertyForm.input_type') is-invalid @enderror" wire:model="propertyForm.input_type">
                                @foreach($propertyInputTypes as $type => $label)
                                    <option value="{{ $type }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('propertyForm.input_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('admin::home.specification_field_unit')</label>
                            <input type="text" class="form-control @error('propertyForm.unit') is-invalid @enderror"
                                   wire:model.defer="propertyForm.unit" placeholder="@lang('admin::home.specification_field_unit_placeholder')">
                            @error('propertyForm.unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="property-column-span">@lang('admin::home.specification_field_column_span') <span class="text-danger">*</span></label>
                            <select id="property-column-span" class="form-select @error('propertyForm.column_span') is-invalid @enderror" wire:model="propertyForm.column_span">
                                @foreach(range(2, 12) as $columns)<option value="{{ $columns }}">{{ $columns }} / 12</option>@endforeach
                            </select>
                            <small class="text-muted d-block mt-2">@lang('admin::home.specification_field_column_span_help')</small>
                            @error('propertyForm.column_span')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @if(in_array($propertyForm['input_type'] ?? null, ['select', 'multiselect'], true))
                            <div class="col-12">
                                <label class="form-label">@lang('admin::home.specification_field_options') <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('propertyForm.options_text') is-invalid @enderror"
                                          rows="3" wire:model.defer="propertyForm.options_text"
                                          placeholder="@lang('admin::home.specification_field_options_placeholder')"></textarea>
                                @error('propertyForm.options_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label">@lang('admin::home.specification_field_group')</label>
                            <input type="text" class="form-control @error('propertyForm.group') is-invalid @enderror"
                                   wire:model.defer="propertyForm.group" placeholder="@lang('admin::home.specification_field_group_placeholder')">
                            @error('propertyForm.group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">@lang('admin::home.specification_field_merchant_guidance')</label>
                            <textarea class="form-control @error('propertyForm.merchant_guidance') is-invalid @enderror"
                                      rows="3" wire:model.defer="propertyForm.merchant_guidance"
                                      placeholder="@lang('admin::home.specification_field_merchant_guidance_placeholder')"></textarea>
                            @error('propertyForm.merchant_guidance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 mt-3">
                            <h4 class="field-visibility-heading">@lang('admin::home.specification_field_visibility')</h4>
                            @foreach([
                                'is_required' => ['specification_field_required', 'specification_field_required_help'],
                                'show_in_summary' => ['specification_field_show_in_summary', 'specification_field_show_in_summary_help'],
                                'filterable' => ['specification_field_filterable', 'specification_field_filterable_help'],
                                'is_conditional' => ['specification_field_conditional', 'specification_field_conditional_help'],
                            ] as $toggle => $labels)
                                <label class="field-visibility-row" for="property-form-{{ $toggle }}">
                                    <span>
                                        <strong>@lang('admin::home.' . $labels[0])</strong>
                                        <small>@lang('admin::home.' . $labels[1])</small>
                                        @if($toggle === 'is_conditional' && collect($properties)->filter(fn ($field, $fieldIndex) => $fieldIndex !== $editingPropertyIndex)->isEmpty())
                                            <small class="text-warning">@lang('admin::home.specification_field_conditional_requires_other_field')</small>
                                        @endif
                                        @if($toggle === 'is_conditional')
                                            @error('propertyForm.is_conditional')<small class="text-danger">{{ $message }}</small>@enderror
                                        @endif
                                    </span>
                                    <span class="form-check form-switch m-0">
                                        <input id="property-form-{{ $toggle }}" class="form-check-input" type="checkbox" role="switch"
                                               wire:model="propertyForm.{{ $toggle }}"
                                               @disabled($toggle === 'is_conditional' && collect($properties)->filter(fn ($field, $fieldIndex) => $fieldIndex !== $editingPropertyIndex)->isEmpty())>
                                    </span>
                                </label>
                                @if($toggle === 'is_conditional' && ($propertyForm['is_conditional'] ?? false))
                                    <div class="conditional-config">
                                        <div>
                                            <label class="form-label">@lang('admin::home.specification_condition_depends_on') <span class="text-danger">*</span></label>
                                            <select class="form-select @error('propertyForm.condition_field_key') is-invalid @enderror"
                                                    wire:model.defer="propertyForm.condition_field_key">
                                                <option value="">@lang('admin::home.specification_condition_choose_field')</option>
                                                @foreach($properties as $fieldIndex => $field)
                                                    @if($fieldIndex !== $editingPropertyIndex && !empty($field['field_key']))
                                                        <option value="{{ $field['field_key'] }}">{{ app()->getLocale() === 'ar' ? $field['name_ar'] : $field['name_en'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('propertyForm.condition_field_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            @if(!collect($properties)->filter(fn ($field, $fieldIndex) => $fieldIndex !== $editingPropertyIndex)->isNotEmpty())
                                                <small class="text-muted d-block mt-1">@lang('admin::home.specification_condition_no_fields')</small>
                                            @endif
                                        </div>
                                        <div class="row g-3 mt-1">
                                            <div class="col-md-6">
                                                <label class="form-label">@lang('admin::home.specification_condition_operator') <span class="text-danger">*</span></label>
                                                <select class="form-select @error('propertyForm.condition_operator') is-invalid @enderror"
                                                        wire:model="propertyForm.condition_operator">
                                                    @foreach($conditionOperators as $operator => $label)
                                                        <option value="{{ $operator }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('propertyForm.condition_operator')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            @if(($propertyForm['condition_operator'] ?? 'equals') !== 'filled')
                                                <div class="col-md-6">
                                                    <label class="form-label">@lang('admin::home.specification_condition_value') <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('propertyForm.condition_value') is-invalid @enderror"
                                                           wire:model.defer="propertyForm.condition_value"
                                                           placeholder="@lang('admin::home.specification_condition_value_placeholder')">
                                                    @error('propertyForm.condition_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <footer class="field-modal-footer">
                    <span>@lang('admin::home.specification_field_modal_footer_help')</span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light border" wire:click="closePropertyModal">@lang('admin::home.cancel')</button>
                        <button type="button" class="btn btn-primary" wire:click="saveProperty" wire:loading.attr="disabled" wire:target="saveProperty">
                            @lang($editingPropertyIndex === null ? 'admin::home.specification_add_to_template' : 'admin::home.specification_save_field_changes')
                        </button>
                    </div>
                </footer>
            </section>
        </div>
    @endif

    @if($showMerchantPreview)
        <div class="merchant-preview-backdrop" wire:click.self="closeMerchantPreview">
            <section class="merchant-preview-modal" role="dialog" aria-modal="true" aria-labelledby="merchant-preview-title">
                <header class="merchant-preview-header">
                    <div>
                        <h3 id="merchant-preview-title">@lang('admin::home.specification_preview_merchant_form')</h3>
                        <p>{{ $nameAr ?: __('admin::home.specification_new_template') }}</p>
                    </div>
                    <button type="button" class="btn btn-link text-muted p-1" wire:click="closeMerchantPreview"
                            aria-label="@lang('admin::home.specification_close_preview')">
                        <i class="ri-close-line fs-5"></i>
                    </button>
                </header>
                <div class="merchant-preview-body">
                    @if($properties === [])
                        <div class="preview-placeholder">@lang('admin::home.specification_empty_preview')</div>
                    @else
                        @foreach($properties as $property)
                            @if(trim($property['name_ar'] ?? '') !== '')
                                <div class="merchant-preview-field" style="--field-columns:{{ max(2, min(12, (int) ($property['column_span'] ?? (($property['input_type'] ?? '') === 'long_text' ? 12 : 6)))) }}" wire:key="merchant-preview-{{ $property['field_key'] }}">
                                    <label class="form-label mb-2">
                                        {{ app()->getLocale() === 'ar' ? $property['name_ar'] : $property['name_en'] }}
                                        @if(!empty($property['is_required']))<span class="text-danger">*</span>@endif
                                    </label>
                                    @if(!empty($property['merchant_guidance']))
                                        <small class="merchant-preview-guidance">{{ $property['merchant_guidance'] }}</small>
                                    @endif
                                    @if(!empty($property['is_conditional']))
                                        @php($conditionSource = collect($properties)->firstWhere('field_key', $property['condition_field_key'] ?? null))
                                        @if($conditionSource)
                                            <small class="merchant-preview-condition">
                                                <i class="ri-git-branch-line me-1"></i>
                                                @lang('admin::home.specification_field_conditional'):
                                                {{ app()->getLocale() === 'ar' ? $conditionSource['name_ar'] : $conditionSource['name_en'] }}
                                                · {{ $conditionOperators[$property['condition_operator'] ?? ''] ?? '' }}
                                                @if(($property['condition_operator'] ?? '') !== 'filled')
                                                    · {{ $property['condition_value'] ?? '' }}
                                                @endif
                                            </small>
                                        @endif
                                    @endif
                                    @switch($property['input_type'] ?? 'short_text')
                                        @case('short_text')
                                            <input type="text" class="form-control" disabled placeholder="@lang('admin::home.specification_preview_value')">
                                            @break
                                        @case('decimal')
                                            <input type="number" step="any" class="form-control" disabled placeholder="@lang('admin::home.specification_preview_value')">
                                            @break
                                        @case('integer')
                                            <input type="number" step="1" class="form-control" disabled placeholder="@lang('admin::home.specification_preview_value')">
                                            @break
                                        @case('select')
                                        @case('multiselect')
                                            @include('admin::livewire.specification.partials.choice-preview')
                                            @break
                                        @case('boolean')
                                            <div class="merchant-preview-boolean d-flex gap-2">
                                                <button type="button"
                                                        class="btn btn-outline-secondary flex-fill" disabled>
                                                    @lang('admin::home.specification_preview_yes')
                                                </button>
                                                <button type="button"
                                                        class="btn btn-outline-secondary flex-fill" disabled>
                                                    @lang('admin::home.specification_preview_no')
                                                </button>
                                            </div>
                                            @break
                                        @case('long_text')
                                            <textarea class="form-control" rows="3" disabled placeholder="@lang('admin::home.specification_preview_value')"></textarea>
                                            @break
                                        @case('attachment')
                                            <input type="file" class="form-control" disabled>
                                            @break
                                        @case('number_range')
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="number" class="form-control" disabled placeholder="@lang('admin::home.specification_preview_min')">
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" class="form-control" disabled placeholder="@lang('admin::home.specification_preview_max')">
                                                </div>
                                            </div>
                                            @break
                                        @case('date')
                                            <input type="date" class="form-control" disabled>
                                            @break
                                    @endswitch
                                    @if(!empty($property['unit']))
                                        <small class="merchant-preview-unit">{{ $property['unit'] }}</small>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </section>
        </div>
    @endif
</form>
