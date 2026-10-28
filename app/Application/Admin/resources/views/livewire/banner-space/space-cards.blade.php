<div>
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

        <div class="d-flex gap-2">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary {{ $viewMode === 'grid' ? 'active' : '' }}" wire:click="toggleViewMode('grid')">
                    <i class="fe fe-grid"></i>
                </button>
                <button type="button" class="btn btn-outline-primary {{ $viewMode === 'table' ? 'active' : '' }}" wire:click="toggleViewMode('table')">
                    <i class="fe fe-list"></i>
                </button>
            </div>
            <button class="btn btn-primary" wire:click="openCreateModal"
                    onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('createSpaceModal')).show()">
                <i class="fe fe-plus me-2"></i>{{ __('admin::home.Add New') }}
            </button>
        </div>
    </div>

    @if(count($placements) > 0)
        @if($viewMode === 'grid')
        <div class="row g-3">
            @foreach($placements as $placement)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card border-0 shadow hover-card" style="min-height: 300px;">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-start mb-3">
                                <div class="avatar avatar-lg bg-primary-transparent text-primary rounded me-3 flex-shrink-0">
                                    <i class="fe fe-{{
                                        match($placement->type->value) {
                                            'slider' => 'image',
                                            'carousel' => 'package',
                                            'grid_banners' => 'grid',
                                            'product_tabs' => 'layers',
                                            'product_slider' => 'shopping-bag',
                                            default => 'settings'
                                        }
                                    }} tx-20"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $placement->getTranslation('name', app()->getLocale()) }}</h6>
                                    <span class="badge bg-light text-muted border-0 px-2 py-1">{{ $placement->type->label() }}</span>
                                </div>
                            </div>

                            <div class="border-bottom mb-3"></div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <small class="text-muted d-block mb-1">{{ __('admin::lang.Items') }}</small>
                                                <h5 class="mb-0 text-primary">{{ $placement->banners_count ?? 0 }}</h5>
                                            </div>
                                            <i class="fe fe-layers tx-24 text-primary opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <small class="text-muted d-block mb-1">{{ __('admin::lang.Order') }}</small>
                                                <h5 class="mb-0 text-dark">{{ $placement->sort_order ?? 0 }}</h5>
                                            </div>
                                            <i class="fe fe-hash tx-24 text-muted opacity-25"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 {{ $placement->status->value === 'active' ? 'bg-success-transparent' : 'bg-secondary-transparent' }}">
                                    <span class="fw-semibold {{ $placement->status->value === 'active' ? 'text-success' : 'text-secondary' }}">
                                        <i class="fe fe-{{ $placement->status->value === 'active' ? 'check-circle' : 'x-circle' }} me-1"></i>
                                        {{ $placement->status->label() }}
                                    </span>
                                    <span class="badge {{ $placement->status->value === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $placement->location)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-auto pt-2">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary flex-grow-1" wire:click="openUpdateModal({{ $placement->id }})" wire:ignore
                                            data-space-edit data-label="{{ __('admin::lang.Edit') }}">
                                        <i class="fe fe-edit me-1"></i>{{ __('admin::lang.Edit') }}
                                    </button>
                                    <button class="btn btn-light border" wire:click="copyPlacement({{ $placement->id }})" title="{{ __('admin::lang.Copy') }}">
                                        <i class="fe fe-copy"></i>
                                    </button>
                                    <button class="btn btn-light border text-danger" wire:click="openDeleteModal({{ $placement->id }})" title="{{ __('admin::lang.Delete') }}">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('admin::lang.Space Name') }}</th>
                        <th>{{ __('admin::lang.Placement Type') }}</th>
                        <th>{{ __('admin::lang.Items') }}</th>
                        <th>{{ __('admin::lang.Order') }}</th>
                        <th>{{ __('admin::home.status') }}</th>
                        <th class="text-center">{{ __('admin::home.Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($placements as $index => $placement)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $placement->getTranslation('name', app()->getLocale()) }}</td>
                            <td><span class="badge bg-light text-muted border-0 px-2 py-1">{{ $placement->type->label() }}</span></td>
                            <td>{{ $placement->banners_count ?? 0 }}</td>
                            <td>{{ $placement->sort_order ?? 0 }}</td>
                            <td>
                                <span class="badge {{ $placement->status->value === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $placement->status->label() }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <button class="btn btn-sm btn-primary" wire:click="openUpdateModal({{ $placement->id }})" wire:ignore
                                            title="{{ __('admin::lang.Edit') }}" data-space-edit>
                                        <i class="fe fe-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light border" wire:click="copyPlacement({{ $placement->id }})" title="{{ __('admin::lang.Copy') }}">
                                        <i class="fe fe-copy"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light border text-danger" wire:click="openDeleteModal({{ $placement->id }})" title="{{ __('admin::lang.Delete') }}">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <style>
            .hover-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid #e9ecef;
            }
            .hover-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
                border-color: var(--primary) !important;
            }
        </style>
    @else
        <div class="text-center p-5 border rounded bg-light">
            <i class="fe fe-layers fs-50 text-muted mb-3"></i>
            <h5 class="text-muted">{{ __('admin::lang.No Placements Found') }}</h5>
            <p class="text-muted mb-4">{{ __('admin::lang.Create your first placement to get started') }}</p>
            <button class="btn btn-primary" wire:click="openCreateModal"
                    onclick="bootstrap.Modal.getOrCreateInstance(document.getElementById('createSpaceModal')).show()">
                <i class="fe fe-plus me-2"></i>{{ __('admin::home.Add New') }}
            </button>
        </div>
    @endif
</div>

<style>
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-5px); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', initSpaceEditLoading);
    document.addEventListener('livewire:load', initSpaceEditLoading);

    function initSpaceEditLoading() {
        if (window.__spaceEditLoadingBound) return;
        window.__spaceEditLoadingBound = true;

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-space-edit]');
            if (!btn || btn.disabled) return;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('disabled');
            var label = btn.getAttribute('data-label') || '';
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>' + label;
        });

        function resetSpaceEditButtons() {
            document.querySelectorAll('[data-space-edit]').forEach(function(btn) {
                if (btn.dataset.originalHtml) {
                    btn.innerHTML = btn.dataset.originalHtml;
                    delete btn.dataset.originalHtml;
                }
                btn.disabled = false;
                btn.classList.remove('disabled');
            });
        }

        document.addEventListener('shown.bs.modal', function(e) {
            if (e.target && e.target.id === 'updateSpaceModal') {
                setTimeout(resetSpaceEditButtons, 100);
            }
        });

        document.addEventListener('hidden.bs.modal', function(e) {
            if (e.target && e.target.id === 'updateSpaceModal') {
                resetSpaceEditButtons();
            }
        });
    }
</script>
