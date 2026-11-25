<div>
    <div class="spec-page-heading d-flex align-items-center justify-content-between">
        <div>
            <h2>@lang('admin::home.Specifications')</h2>
            <p>@lang('admin::home.specification_templates_subtitle')</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-light border btn-sm" wire:click="export" wire:loading.attr="disabled" wire:target="export">
                <i class="ri-download-line me-1"></i>@lang('admin::home.specification_export')
            </button>
            @AdminHasRole(['create_specifications'])
            <a href="{{ route('admin:vars.specifications.create') }}" class="btn btn-primary btn-sm">
                @lang('admin::home.Create New Specification') <i class="ri-add-line ms-1"></i>
            </a>
            @endAdminHasRole
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="spec-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="spec-stat-value">{{ number_format($stats['total']) }}</div>
                    <div class="spec-stat-label">@lang('admin::home.specification_total_templates')</div>
                </div>
                <span class="spec-stat-icon"><i class="ri-list-check"></i></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="spec-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="spec-stat-value">{{ number_format($stats['published']) }}</div>
                    <div class="spec-stat-label">@lang('admin::home.specification_published_templates')</div>
                </div>
                <span class="spec-stat-icon is-green"><i class="ri-check-line"></i></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="spec-stat-card d-flex align-items-center justify-content-between">
                <div>
                    <div class="spec-stat-value">{{ number_format($stats['subcategories']) }}</div>
                    <div class="spec-stat-label">@lang('admin::home.specification_subcategories')</div>
                </div>
                <span class="spec-stat-icon is-amber"><i class="ri-node-tree"></i></span>
            </div>
        </div>
    </div>

    <div class="spec-table-card">
        <div class="spec-toolbar d-flex flex-wrap justify-content-end gap-2">
            <select class="form-select" style="max-width: 180px" wire:model="status" aria-label="@lang('admin::home.status')">
                <option value="">@lang('admin::home.specification_all_statuses')</option>
                <option value="active">@lang('admin::home.specification_status_active')</option>
                <option value="inactive">@lang('admin::home.specification_status_inactive')</option>
                <option value="draft">@lang('admin::home.specification_status_draft')</option>
                <option value="rejected">@lang('admin::home.specification_status_rejected')</option>
            </select>
            <select class="form-select" style="max-width: 180px" wire:model="categoryId" aria-label="@lang('admin::home.specification_categories')">
                <option value="">@lang('admin::home.specification_all_categories')</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                @endforeach
            </select>
            <div class="input-group" style="max-width: 255px">
                <span class="input-group-text bg-white border-end-0"><i class="ri-search-line"></i></span>
                <input type="search" class="form-control border-start-0" wire:model.debounce.300ms="search"
                       placeholder="@lang('admin::home.specification_search_placeholder')" aria-label="@lang('admin::home.specification_search_placeholder')">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table spec-table">
                <thead>
                    <tr>
                        <th>@lang('admin::home.Name')</th>
                        <th>@lang('admin::home.specification_properties')</th>
                        <th>@lang('admin::home.specification_categories')</th>
                        <th>@lang('admin::home.status')</th>
                        <th class="text-center">@lang('admin::home.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                        @php
                            $templateStatus = $template->getRawOriginal('status') ?: 'unknown';
                        @endphp
                        <tr wire:key="specification-{{ $template->id }}">
                            @php $linkedCategoryCount = $template->categories_count + $template->category_links_count; @endphp
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="spec-template-icon"><i class="ri-list-check"></i></span>
                                    <div class="min-w-0">
                                        <div class="spec-template-name">{{ $template->name }}</div>
                                        <div class="spec-template-description" title="{{ $template->description }}">{{ $template->description ?: '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($template->properties_count) }} @lang('admin::home.specification_fields_suffix')</td>
                            <td>
                                @if($linkedCategoryCount)
                                    <span title="{{ $template->categories->pluck('title')->implode(', ') }}">
                                        {{ number_format($linkedCategoryCount) }} @lang('admin::home.specification_categories_suffix')
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="spec-status is-{{ $templateStatus }}">
                                    @if($templateStatus === 'unknown')
                                        @lang('admin::home.specification_status_unknown')
                                    @else
                                        @lang('admin::home.specification_status_' . $templateStatus)
                                    @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-2">
                                    @AdminHasRole(['create_specifications'])
                                    <a href="{{ route('admin:vars.specifications.copy', $template->id) }}" class="spec-action"
                                       title="@lang('admin::home.specification_duplicate')">
                                        <i class="ri-file-copy-line"></i>
                                    </a>
                                    @endAdminHasRole
                                    @AdminHasRole(['edit_specifications'])
                                    <a href="{{ route('admin:vars.specifications.edit', $template->id) }}" class="spec-action"
                                       title="@lang('admin::home.Edit')">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    @endAdminHasRole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="spec-empty-state">@lang('admin::home.specification_no_results')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="spec-table-footer">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">@lang('admin::home.specification_rows_per_page')</span>
                <select class="form-select form-select-sm" style="width: auto" wire:model="perPage" aria-label="@lang('admin::home.specification_rows_per_page')">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">
                    @lang('admin::home.specification_showing')
                    {{ $templates->firstItem() ?? 0 }}–{{ $templates->lastItem() ?? 0 }}
                    @lang('admin::home.specification_of')
                    {{ number_format($templates->total()) }}
                </span>
                {{ $templates->links() }}
            </div>
        </div>
    </div>
</div>
