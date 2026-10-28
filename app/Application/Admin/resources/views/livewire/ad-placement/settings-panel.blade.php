<div class="settings-panel-container">
    <div class="row g-0">
        <div class="col-md-3 settings-sidebar border-end">
            <div class="p-4 border-bottom bg-light">
                <h6 class="fw-bold mb-1 text-dark">
                    <i class="fe fe-layers me-2 text-primary"></i>{{ __('admin::lang.Advertising Pages') }}
                </h6>
                <small class="text-muted">{{ __('admin::lang.Select a page to configure its placements') }}</small>
            </div>

            <div class="sidebar-menu">
                @foreach($pages as $page)
                    <button wire:click="selectPage({{ $page->id }})"
                            class="sidebar-item {{ $selectedPageId === $page->id ? 'active' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="sidebar-icon me-3">
                                @if($page->key === 'home')
                                    <i class="fe fe-home"></i>
                                @elseif($page->key === 'category')
                                    <i class="fe fe-grid"></i>
                                @elseif($page->key === 'product')
                                    <i class="fe fe-shopping-bag"></i>
                                @elseif($page->key === 'cart')
                                    <i class="fe fe-shopping-cart"></i>
                                @else
                                    <i class="fe fe-file-text"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $page->name }}</div>
                                <small class="text-muted">{{ $page->placements_count }} {{ __('admin::lang.placements') }}</small>
                            </div>
                            @if($selectedPageId === $page->id)
                                <i class="fe fe-chevron-right ms-2"></i>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="col-md-9 settings-main-panel">
            <div class="p-4 border-bottom bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <i class="fe fe-settings me-2 text-primary"></i>
                            {{ $selectedPage ? $selectedPage->name : '' }} {{ __('admin::lang.Settings') }}
                        </h4>
                        <p class="text-muted mb-0">
                            {{ __('admin::lang.Configure ad placements for this page') }}
                        </p>
                    </div>
                    <button class="btn btn-primary" wire:click="$emit('openCreateModal', {{ $selectedPageId }})">
                        <i class="fe fe-plus me-2"></i>{{ __('admin::lang.Add Placement') }}
                    </button>
                </div>
            </div>

            <div class="p-4">
                @if($placements->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fe fe-inbox fs-60 text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted">{{ __('admin::lang.No placements configured') }}</h5>
                        <p class="text-muted">{{ __('admin::lang.Click "Add Placement" to create your first placement') }}</p>
                    </div>
                @else
                    <div class="accordion settings-accordion" id="placementsAccordion">
                        @foreach($placements as $index => $placement)
                            <div class="accordion-item border mb-3 rounded">
                                <h2 class="accordion-header" id="heading{{ $placement->id }}">
                                    <button class="accordion-button {{ $expandedPlacementId === $placement->id ? '' : 'collapsed' }}"
                                            type="button"
                                            wire:click="togglePlacement({{ $placement->id }})">
                                        <div class="d-flex align-items-center w-100">
                                            <div class="placement-type-icon me-3">
                                                @if($placement->type->value === 'slider')
                                                    <i class="fe fe-film text-primary fs-20"></i>
                                                @elseif($placement->type->value === 'carousel')
                                                    <i class="fe fe-image text-info fs-20"></i>
                                                @elseif($placement->type->value === 'grid_banners')
                                                    <i class="fe fe-grid text-warning fs-20"></i>
                                                @else
                                                    <i class="fe fe-box text-secondary fs-20"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark">{{ $placement->name }}</div>
                                                <small class="text-muted">
                                                    <i class="fe fe-map-pin me-1"></i>{{ $placement->location }}
                                                    <span class="mx-2">•</span>
                                                    <span class="badge bg-light text-dark border">{{ $placement->type->label() }}</span>
                                                </small>
                                            </div>
                                            <div class="me-3">
                                                @if($placement->status->value === 'active')
                                                    <span class="badge bg-success-transparent text-success">
                                                        <i class="fe fe-check-circle me-1"></i>{{ __('admin::lang.Active') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-transparent text-secondary">
                                                        <i class="fe fe-slash me-1"></i>{{ __('admin::lang.Inactive') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $placement->id }}" class="accordion-collapse collapse {{ $expandedPlacementId === $placement->id ? 'show' : '' }}">
                                    <div class="accordion-body bg-light">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="setting-item">
                                                    <label class="text-muted small">{{ __('admin::lang.Placement Key') }}</label>
                                                    <div class="fw-semibold">{{ $placement->key ?: '—' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="setting-item">
                                                    <label class="text-muted small">{{ __('admin::lang.Sort Order') }}</label>
                                                    <div class="fw-semibold">{{ $placement->sort_order }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="setting-item">
                                                    <label class="text-muted small">{{ __('admin::lang.Active Banners') }}</label>
                                                    <div class="fw-semibold text-primary">
                                                        <i class="fe fe-image me-1"></i>{{ $placement->banners_count }}
                                                    </div>
                                                </div>
                                            </div>

                                            @if($placement->settings)
                                                @php
                                                    $settings = $placement->settings;
                                                    $sliderConfig = $settings['slider_config'] ?? null;
                                                    $gridConfig = $settings['grid_config'] ?? null;
                                                @endphp

                                                @if($sliderConfig)
                                                    <div class="col-12 mt-3">
                                                        <h6 class="fw-bold mb-2 text-dark">
                                                            <i class="fe fe-sliders me-2"></i>{{ __('admin::lang.Slider Configuration') }}
                                                        </h6>
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <small class="text-muted">{{ __('admin::lang.Max Items') }}:</small>
                                                                <strong class="ms-2">{{ $sliderConfig['max_items'] ?? '—' }}</strong>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">{{ __('admin::lang.Autoplay') }}:</small>
                                                                <strong class="ms-2">{{ ($sliderConfig['autoplay'] ?? false) ? '✓' : '✗' }}</strong>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">{{ __('admin::lang.Speed') }}:</small>
                                                                <strong class="ms-2">{{ $sliderConfig['autoplay_speed'] ?? '—' }}ms</strong>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">{{ __('admin::lang.Loop') }}:</small>
                                                                <strong class="ms-2">{{ ($sliderConfig['loop'] ?? false) ? '✓' : '✗' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                            <button class="btn btn-sm btn-outline-primary flex-grow-1"
                                                    wire:click="$emit('openUpdateModal', {{ $placement->id }})">
                                                <i class="fe fe-edit me-1"></i>{{ __('admin::lang.Edit Settings') }}
                                            </button>
                                            <a href="{{ route('admin:ad-management.placements.show', $placement->id) }}" class="btn btn-sm btn-primary flex-grow-1">
                                                <i class="fe fe-layers me-1"></i>{{ __('admin::lang.Manage Content') }}
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="$emit('openDeleteModal', {{ $placement->id }})">
                                                <i class="fe fe-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .settings-panel-container {
        min-height: calc(100vh - 200px);
    }

    .settings-sidebar {
        background: #f8f9fa;
        max-height: calc(100vh - 150px);
        overflow-y: auto;
    }

    .sidebar-menu {
        padding: 0.5rem 0;
    }

    .sidebar-item {
        width: 100%;
        border: none;
        background: transparent;
        padding: 1rem 1.25rem;
        text-align: left;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        cursor: pointer;
    }

    .sidebar-item:hover {
        background: rgba(98, 89, 202, 0.05);
        border-left-color: rgba(98, 89, 202, 0.3);
    }

    .sidebar-item.active {
        background: white;
        border-left-color: #6259ca;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .sidebar-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #e8e5ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #6259ca;
    }

    .sidebar-item.active .sidebar-icon {
        background: #6259ca;
        color: white;
    }

    .settings-accordion .accordion-button {
        background: white;
        font-size: 0.95rem;
        padding: 1.25rem 1.5rem;
    }

    .settings-accordion .accordion-button:not(.collapsed) {
        background: #f8f9fa;
        color: #6259ca;
        box-shadow: none;
    }

    .settings-accordion .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(98, 89, 202, 0.2);
    }

    .placement-type-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #f0f0f8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .setting-item {
        padding: 0.75rem;
        background: white;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .setting-item label {
        display: block;
        margin-bottom: 0.25rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush
