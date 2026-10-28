<div class="query-builder-wrapper">
    <div class="mb-4">
        <label class="form-label small fw-bold text-primary">
            <i class="ri-settings-3-line me-1"></i>{{ __('admin::lang.Query Mode') }}
        </label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" id="mode-preset-{{ $tabKey }}" wire:model.live="mode" value="preset">
            <label class="btn btn-outline-success btn-sm" for="mode-preset-{{ $tabKey }}">
                <i class="ri-list-check me-1"></i>{{ __('admin::lang.Preset Template') }}
            </label>

            <input type="radio" class="btn-check" id="mode-custom-{{ $tabKey }}" wire:model.live="mode" value="custom">
            <label class="btn btn-outline-primary btn-sm" for="mode-custom-{{ $tabKey }}">
                <i class="ri-filter-3-line me-1"></i>{{ __('admin::lang.Custom Query') }}
            </label>

            <input type="radio" class="btn-check" id="mode-expert-{{ $tabKey }}" wire:model.live="mode" value="expert">
            <label class="btn btn-outline-danger btn-sm" for="mode-expert-{{ $tabKey }}">
                <i class="ri-code-s-slash-line me-1"></i>{{ __('admin::lang.Expert Mode') }}
            </label>
        </div>
    </div>

    @if($mode === 'preset')
        <div class="preset-mode card bg-light border-success">
            <div class="card-body p-3">
                <h6 class="text-success mb-3">
                    <i class="ri-list-check me-1"></i>{{ __('admin::lang.Select Preset Template') }}
                </h6>

                <div class="mb-3">
                    <label class="form-label small">{{ __('admin::lang.Template') }}</label>
                    <select class="form-select form-select-sm" wire:model.live="query.preset">
                        @foreach($presetOptions as $preset)
                            <option value="{{ $preset->value }}">{{ $preset->label() }}</option>
                        @endforeach
                    </select>
                    @if(!empty($query['preset']))
                        <small class="text-muted d-block mt-1">
                            <i class="ri-information-line me-1"></i>
                            {{ ProductQueryPresetEnum::from($query['preset'])->description() }}
                        </small>
                    @endif
                </div>

                @if(isset($query['preset']))
                    @if(in_array($query['preset'], ['latest', 'trending']))
                        <div class="mb-2">
                            <label class="form-label small">{{ __('admin::lang.Days') }}</label>
                            <input type="number" class="form-control form-control-sm"
                                   wire:model="query.config.days"
                                   min="1" max="365" placeholder="30">
                        </div>
                    @elseif($query['preset'] === 'best_selling')
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small">{{ __('admin::lang.Min Sales') }}</label>
                                <input type="number" class="form-control form-control-sm"
                                       wire:model="query.config.min_sales"
                                       min="1" placeholder="10">
                            </div>
                            <div class="col-6">
                                <label class="form-label small">{{ __('admin::lang.Period') }}</label>
                                <select class="form-select form-select-sm" wire:model="query.config.period">
                                    <option value="all_time">{{ __('admin::lang.All Time') }}</option>
                                    <option value="last_month">{{ __('admin::lang.Last Month') }}</option>
                                    <option value="last_week">{{ __('admin::lang.Last Week') }}</option>
                                </select>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    @if($mode === 'custom')
        <div class="custom-mode card bg-light border-primary">
            <div class="card-body p-3">
                <h6 class="text-primary mb-3">
                    <i class="ri-filter-3-line me-1"></i>{{ __('admin::lang.Custom Filters & Sorting') }}
                </h6>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label small mb-0 fw-bold">
                            <i class="ri-filter-line me-1"></i>{{ __('admin::lang.Filters') }}
                        </label>
                        <button type="button" class="btn btn-sm btn-primary" wire:click="addFilter">
                            <i class="ri-add-line"></i> {{ __('admin::lang.Add Filter') }}
                        </button>
                    </div>

                    @if(!empty($query['filters']))
                        @foreach($query['filters'] as $index => $filter)
                            <div class="card mb-2 border" wire:key="filter-{{ $index }}">
                                <div class="card-body p-2">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label small mb-1">{{ __('admin::lang.Field') }}</label>
                                            <select class="form-select form-select-sm" wire:model="query.filters.{{ $index }}.field">
                                                @foreach($filterFields as $field)
                                                    <option value="{{ $field->value }}">{{ $field->label() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small mb-1">{{ __('admin::lang.Condition') }}</label>
                                            <select class="form-select form-select-sm" wire:model="query.filters.{{ $index }}.condition">
                                                @foreach($conditions as $condition)
                                                    <option value="{{ $condition->value }}">{{ $condition->label() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small mb-1">{{ __('admin::lang.Value') }}</label>
                                            <input type="text" class="form-control form-control-sm"
                                                   wire:model="query.filters.{{ $index }}.value"
                                                   placeholder="{{ __('admin::lang.Enter value') }}">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-danger w-100"
                                                    wire:click="removeFilter({{ $index }})"
                                                    title="{{ __('admin::lang.Remove') }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info py-2 px-3 small mb-0">
                            <i class="ri-information-line me-1"></i>{{ __('admin::lang.No filters added. Click Add Filter to start') }}
                        </div>
                    @endif
                </div>

                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label small mb-0 fw-bold">
                            <i class="ri-sort-asc me-1"></i>{{ __('admin::lang.Sorting') }}
                        </label>
                        <button type="button" class="btn btn-sm btn-success" wire:click="addSort">
                            <i class="ri-add-line"></i> {{ __('admin::lang.Add Sort') }}
                        </button>
                    </div>

                    @if(!empty($query['sort']))
                        @foreach($query['sort'] as $index => $sort)
                            <div class="card mb-2 border" wire:key="sort-{{ $index }}">
                                <div class="card-body p-2">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label small mb-1">{{ __('admin::lang.Sort By') }}</label>
                                            <select class="form-select form-select-sm" wire:model="query.sort.{{ $index }}.field">
                                                @foreach($sortFields as $field)
                                                    <option value="{{ $field->value }}">{{ $field->label() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small mb-1">{{ __('admin::lang.Order') }}</label>
                                            <select class="form-select form-select-sm" wire:model="query.sort.{{ $index }}.order">
                                                <option value="asc">{{ __('admin::lang.Ascending') }} (A → Z, 0 → 9)</option>
                                                <option value="desc">{{ __('admin::lang.Descending') }} (Z → A, 9 → 0)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-danger w-100"
                                                    wire:click="removeSort({{ $index }})"
                                                    title="{{ __('admin::lang.Remove') }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning py-2 px-3 small mb-0">
                            <i class="ri-alert-line me-1"></i>{{ __('admin::lang.No sorting rules. Default sorting will be used') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($mode === 'expert')
        <div class="expert-mode card bg-light border-danger">
            <div class="card-body p-3">
                <h6 class="text-danger mb-3">
                    <i class="ri-code-s-slash-line me-1"></i>{{ __('admin::lang.Expert Mode - Advanced Logic') }}
                </h6>

                <div class="alert alert-danger py-2 px-3 small mb-3">
                    <i class="ri-alert-line me-1"></i>
                    <strong>{{ __('admin::lang.Warning') }}:</strong> {{ __('admin::lang.Expert mode allows complex queries with multiple condition groups') }}
                </div>

                <button type="button" class="btn btn-sm btn-danger mb-3" wire:click="addGroup">
                    <i class="ri-add-line"></i> {{ __('admin::lang.Add Group') }}
                </button>

                @if(!empty($query['groups']))
                    @foreach($query['groups'] as $groupIndex => $group)
                        <div class="card border-danger mb-3" wire:key="group-{{ $groupIndex }}">
                            <div class="card-header bg-danger-transparent d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold">
                                    {{ __('admin::lang.Group') }} {{ $groupIndex + 1 }}
                                    @if($groupIndex > 0)
                                        <span class="badge bg-warning ms-2">{{ $group['logic'] }}</span>
                                    @endif
                                </span>
                                <div>
                                    @if($groupIndex > 0)
                                        <select class="form-select form-select-sm d-inline-block w-auto me-2"
                                                wire:model="query.groups.{{ $groupIndex }}.logic">
                                            <option value="AND">AND</option>
                                            <option value="OR">OR</option>
                                        </select>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger"
                                            wire:click="removeGroup({{ $groupIndex }})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <button type="button" class="btn btn-sm btn-primary mb-2"
                                        wire:click="addGroupFilter({{ $groupIndex }})">
                                    <i class="ri-add-line"></i> {{ __('admin::lang.Add Condition') }}
                                </button>

                                @if(!empty($group['filters']))
                                    @foreach($group['filters'] as $filterIndex => $filter)
                                        <div class="card bg-white mb-2" wire:key="group-{{ $groupIndex }}-filter-{{ $filterIndex }}">
                                            <div class="card-body p-2">
                                                <div class="row g-2 align-items-end">
                                                    <div class="col-md-3">
                                                        <label class="form-label small mb-1">{{ __('admin::lang.Field') }}</label>
                                                        <select class="form-select form-select-sm"
                                                                wire:model="query.groups.{{ $groupIndex }}.filters.{{ $filterIndex }}.field">
                                                            @foreach($filterFields as $field)
                                                                <option value="{{ $field->value }}">{{ $field->label() }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small mb-1">{{ __('admin::lang.Condition') }}</label>
                                                        <select class="form-select form-select-sm"
                                                                wire:model="query.groups.{{ $groupIndex }}.filters.{{ $filterIndex }}.condition">
                                                            @foreach($conditions as $condition)
                                                                <option value="{{ $condition->value }}">{{ $condition->label() }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label small mb-1">{{ __('admin::lang.Value') }}</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                               wire:model="query.groups.{{ $groupIndex }}.filters.{{ $filterIndex }}.value">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-danger w-100"
                                                                wire:click="removeGroupFilter({{ $groupIndex }}, {{ $filterIndex }})">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    <div class="mt-3">
        <button type="button" class="btn btn-info btn-sm w-100" wire:click="previewQuery">
            <i class="ri-eye-line me-1"></i>{{ __('admin::lang.Preview Results') }}
        </button>
    </div>

    @if($showPreview && $previewProducts)
        <div class="preview-results mt-3 card border-info">
            <div class="card-header bg-info-transparent py-2">
                <h6 class="mb-0 text-info">
                    <i class="ri-eye-line me-1"></i>{{ __('admin::lang.Preview') }}: {{ $previewProducts->count() }} {{ __('admin::lang.products') }}
                </h6>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="small">{{ __('admin::lang.Product') }}</th>
                                <th class="small">{{ __('admin::lang.Price') }}</th>
                                <th class="small">{{ __('admin::lang.Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previewProducts as $product)
                                <tr>
                                    <td class="small">{{ $product->getTranslation('title', app()->getLocale()) }}</td>
                                    <td class="small">{{ $product->default_price ?? 'N/A' }}</td>
                                    <td class="small">
                                        <span class="badge badge-sm bg-success">{{ $product->status->value }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

@push('css')
<style>
.query-builder-wrapper .card {
    transition: all 0.3s ease;
}
.query-builder-wrapper .card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.btn-check:checked + .btn-outline-success {
    background-color: #28a745;
    color: white;
}
.btn-check:checked + .btn-outline-primary {
    background-color: #007bff;
    color: white;
}
.btn-check:checked + .btn-outline-danger {
    background-color: #dc3545;
    color: white;
}
</style>
@endpush
