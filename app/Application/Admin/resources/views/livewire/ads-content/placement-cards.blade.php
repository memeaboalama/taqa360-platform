<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs border-0" role="tablist">
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

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary {{ $viewMode === 'grid' ? 'active' : '' }}" wire:click="toggleViewMode('grid')">
                <i class="fe fe-grid"></i>
            </button>
            <button type="button" class="btn btn-outline-primary {{ $viewMode === 'table' ? 'active' : '' }}" wire:click="toggleViewMode('table')">
                <i class="fe fe-list"></i>
            </button>
        </div>
    </div>

    @if(count($placements) > 0)
        @if($viewMode === 'grid')
            <div class="row g-3">
                @foreach($placements as $placement)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="card border-0 shadow-sm hover-content-card" wire:click="goToPlacement({{ $placement->id }})" style="cursor: pointer; min-height: 300px;">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="text-center mb-3">
                                    <div class="avatar avatar-xl bg-primary-transparent rounded mx-auto mb-3">
                                        <i class="fe fe-{{
                                            match($placement->type->value) {
                                                'slider' => 'image',
                                                'carousel' => 'package',
                                                'grid_banners' => 'grid',
                                                'product_tabs' => 'layers',
                                                'product_slider' => 'shopping-bag',
                                                default => 'file-text'
                                            }
                                        }} tx-24 text-primary"></i>
                                    </div>
                                    <h6 class="mb-1 fw-bold">{{ $placement->getTranslation('name', app()->getLocale()) }}</h6>
                                    <small class="text-muted">{{ $placement->type->label() }}</small>
                                </div>

                                <div class="border-bottom mb-3"></div>

                                <div class="mb-3 text-center">
                                    <code class="bg-light px-3 py-2 rounded">{{ $placement->key }}</code>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded text-center">
                                            <div class="text-muted small mb-1">{{ __('admin::lang.Items') }}</div>
                                            <h5 class="mb-0 text-primary">{{ $placement->items_count }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 bg-light rounded text-center">
                                            <div class="text-muted small mb-1">{{ __('admin::lang.Max') }}</div>
                                            <h5 class="mb-0">{{ $placement->settings['slider_config']['max_items'] ?? '-' }}</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mb-3 flex-grow-1">
                                    <small class="text-muted d-block mb-1">{{ __('admin::lang.Dimensions') }}</small>
                                    <span class="fw-semibold">{{ $placement->settings['display']['devices']['desktop']['width'] ?? 'N/A' }} × {{ $placement->settings['display']['devices']['desktop']['height'] ?? 'N/A' }}</span>
                                </div>

                                <div class="mt-auto">
                                    <button class="btn btn-primary w-100"
                                            wire:loading.attr="disabled"
                                            wire:target="goToPlacement({{ $placement->id }})">
                                        <span wire:loading.remove wire:target="goToPlacement({{ $placement->id }})">
                                            <i class="fe fe-layers me-2"></i>{{ __('admin::lang.Manage Content') }}
                                        </span>
                                        <span wire:loading wire:target="goToPlacement({{ $placement->id }})">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                            {{ __('admin::lang.Loading...') }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-nowrap border-bottom">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('admin::lang.Name') }}</th>
                            <th>{{ __('admin::lang.Key') }}</th>
                            <th>{{ __('admin::lang.Type') }}</th>
                            <th>{{ __('admin::lang.Dimensions') }}</th>
                            <th>{{ __('admin::lang.Content Items') }}</th>
                            <th>{{ __('admin::lang.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($placements as $placement)
                            <tr style="cursor: pointer;" wire:click="goToPlacement({{ $placement->id }})">
                                <td>{{ $placement->getTranslation('name', app()->getLocale()) }}</td>
                                <td><code>{{ $placement->key }}</code></td>
                                <td>{{ $placement->type->label() }}</td>
                                <td>{{ $placement->settings['display']['devices']['desktop']['width'] ?? 'N/A' }}x{{ $placement->settings['display']['devices']['desktop']['height'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info rounded-pill">{{ $placement->items_count }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                            wire:loading.attr="disabled"
                                            wire:target="goToPlacement({{ $placement->id }})">
                                        <span wire:loading.remove wire:target="goToPlacement({{ $placement->id }})">
                                            <i class="fe fe-edit me-1"></i>{{ __('admin::lang.Manage') }}
                                        </span>
                                        <span wire:loading wire:target="goToPlacement({{ $placement->id }})">
                                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                            {{ __('admin::lang.Loading...') }}
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="text-center p-5 border rounded bg-light">
            <i class="fe fe-layers fs-50 text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('admin::lang.No active placements found for this page') }}</h5>
        </div>
    @endif
</div>

@push('css')
<style>
    .hover-content-card {
        transition: all 0.3s ease;
    }
    .hover-content-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush
