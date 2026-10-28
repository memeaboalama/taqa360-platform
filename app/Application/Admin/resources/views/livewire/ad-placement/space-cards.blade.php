<div class="placement-settings-table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs border-0 mb-0" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($activeTab === 'all') active @endif"
                        wire:click="setTab('all')"
                        type="button">
                    <i class="ri-layout-grid-line me-1"></i>{{ __('admin::lang.All') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($activeTab === 'banners') active @endif"
                        wire:click="setTab('banners')"
                        type="button">
                    <i class="ri-image-line me-1"></i>{{ __('admin::lang.Banners & Sliders') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($activeTab === 'products') active @endif"
                        wire:click="setTab('products')"
                        type="button">
                    <i class="ri-shopping-bag-line me-1"></i>{{ __('admin::lang.Products & Categories') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($activeTab === 'brands') active @endif"
                        wire:click="setTab('brands')"
                        type="button">
                    <i class="ri-store-line me-1"></i>{{ __('admin::lang.Brands & Merchants') }}
                </button>
            </li>
        </ul>

        <button class="btn btn-primary" wire:click="$dispatch('openCreateModal', { pageId: {{ $pageId }} })">
            <i class="ri-add-line me-1"></i>{{ __('admin::lang.Add New Placement') }}
        </button>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 fw-semibold">
                {{ __('admin::lang.Placements List') }}
                <span class="badge bg-primary-transparent text-primary ms-2">{{ count($placements) }}</span>
            </h5>
        </div>
    @if(count($placements) > 0)
        <div class="row row-sm">
            @foreach($placements as $placement)
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4" wire:key="placement-card-{{ $placement->id }}">
                    <div class="card custom-card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
                        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                @php
                                    $typeIcon = match($placement->type->value) {
                                        'slider' => 'ri-slideshow-line',
                                        'carousel' => 'ri-carousel-line',
                                        'grid_banners' => 'ri-grid-line',
                                        'product_tabs' => 'ri-folder-line',
                                        'product_slider' => 'ri-shopping-bag-line',
                                        'product_sidebar' => 'ri-layout-right-line',
                                        default => 'ri-image-line'
                                    };
                                @endphp
                                <i class="{{ $typeIcon }} fs-5 me-2"></i>
                                <span class="badge bg-white text-primary">{{ $placement->type->label() }}</span>
                            </div>
                            <div class="badge bg-{{ $placement->status->value === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($placement->status->value) }}
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title mb-2 fw-bold text-dark">
                                {{ $placement->getTranslation('name', app()->getLocale()) }}
                            </h6>

                            @if($placement->key)
                                <p class="text-muted small mb-3">
                                    <i class="ri-key-line me-1"></i>
                                    <code class="bg-light px-2 py-1 rounded">{{ $placement->key }}</code>
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="text-center flex-fill">
                                    <div class="text-primary fw-bold fs-5">{{ $placement->banners_count ?? 0 }}</div>
                                    <small class="text-muted">{{ __('admin::lang.Banners') }}</small>
                                </div>
                                <div class="vr"></div>
                                <div class="text-center flex-fill">
                                    <div class="text-success fw-bold fs-5">{{ $placement->sort_order ?? 0 }}</div>
                                    <small class="text-muted">{{ __('admin::lang.Order') }}</small>
                                </div>
                                <div class="vr"></div>
                                <div class="text-center flex-fill">
                                    <div class="text-info fw-bold fs-5">
                                        @if($placement->settings && isset($placement->settings['max_items']))
                                            {{ $placement->settings['max_items'] }}
                                        @else
                                            ∞
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ __('admin::lang.Max') }}</small>
                                </div>
                            </div>

                            <div class="small text-muted mb-3">
                                <div class="mb-1">
                                    <i class="ri-map-pin-line me-1"></i>
                                    <strong>{{ __('admin::lang.Location') }}:</strong> {{ ucfirst(str_replace('_', ' ', $placement->location)) }}
                                </div>
                                @if($placement->settings)
                                    <div>
                                            title="{{ __('admin::lang.Edit') }}">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <a href="{{ route('admin:ads-content.manage', $placement->id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="{{ __('admin::lang.Manage Content') }}">
                                        <i class="ri-file-list-line"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5">
            <div class="empty-icon mb-3">
                <i class="ri-settings-3-line fs-48 text-muted"></i>
            </div>
            <h5 class="fw-semibold mb-2">{{ __('admin::lang.No Placements Found') }}</h5>
            <p class="text-muted mb-4">{{ __('admin::lang.Get started by creating your first ad placement') }}</p>
            <button class="btn btn-primary" wire:click="$dispatch('openCreateModal', { pageId: {{ $pageId }} })">
                <i class="ri-add-line me-1"></i>{{ __('admin::lang.Add New Placement') }}
            </button>
        </div>
    @endif
</div>

@push('css')
<style>
    .placement-settings-table-container {
        position: relative;
    }

    .placement-type-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(var(--primary-rgb), 0.02);
    }

    .bg-primary-transparent {
        background-color: rgba(var(--primary-rgb), 0.1) !important;
        color: rgb(var(--primary-rgb)) !important;
        border: 1px solid rgba(var(--primary-rgb), 0.2);
    }
</style>
@endpush
