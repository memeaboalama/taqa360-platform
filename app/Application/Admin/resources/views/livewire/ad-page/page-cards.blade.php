<div class="page-cards-container">
    <div class="row row-sm">
        @foreach($pages as $page)
            <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
                <div class="card custom-card page-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    <div class="status-line {{ $page->status->value === 'active' ? 'bg-primary' : 'bg-secondary' }}"></div>

                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="d-flex align-items-center">
                                <div class="page-icon-wrapper me-3">
                                    @if($page->key === 'home')
                                        <i class="fe fe-home text-primary fs-24"></i>
                                    @elseif($page->key === 'category')
                                        <i class="fe fe-grid text-info fs-24"></i>
                                    @elseif($page->key === 'product')
                                        <i class="fe fe-shopping-bag text-warning fs-24"></i>
                                    @elseif($page->key === 'cart')
                                        <i class="fe fe-shopping-cart text-success fs-24"></i>
                                    @else
                                        <i class="fe fe-file-text text-purple fs-24"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">{{ $page->name }}</h5>
                                    <span class="badge bg-light text-muted border rounded-pill px-3">
                                        {{ $page->key }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" wire:click="$dispatch('openUpdateModal', { id: {{ $page->id }} })"
                                    {{ $page->status->value === 'active' ? 'checked' : '' }}
                                    style="cursor: pointer;">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="p-3 rounded bg-light border border-dashed text-center">
                                    <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px;">{{ __('admin::lang.Total Spaces') }}</small>
                                    <span class="fw-bold text-dark fs-16">{{ $page->placements_count }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded bg-light border border-dashed text-center">
                                    <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px;">{{ __('admin::lang.Active Spaces') }}</small>
                                    <span class="fw-bold text-success fs-16">{{ $page->active_placements_count }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-outline-light text-dark border-2 flex-grow-1 fw-bold hover-primary"
                                    wire:click="$dispatch('openUpdateModal', { id: {{ $page->id }} })">
                                <i class="fe fe-settings me-2"></i>{{ __('admin::lang.Settings') }}
                            </button>
                            <a href="{{ route('admin:ad-management.placements.index', $page->id) }}"
                                class="btn btn-primary flex-grow-1 fw-bold shadow-sm">
                                <i class="fe fe-layers me-2"></i>{{ __('admin::lang.Manage Spaces') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-xl-4 col-lg-6 col-md-12 mb-4">
            <button class="card custom-card h-100 w-100 border-2 border-dashed bg-transparent d-flex align-items-center justify-content-center p-5 page-add-card"
                    wire:click="$dispatch('openCreateModal')">
                <div class="text-center">
                    <div class="icon-circle mb-3 mx-auto bg-light text-primary">
                        <i class="fe fe-plus fs-24"></i>
                    </div>
                    <h6 class="fw-bold text-primary mb-1">{{ __('admin::lang.Add New Page') }}</h6>
                    <small class="text-muted">{{ __('admin::lang.Register a new page for ads') }}</small>
                </div>
            </button>
        </div>
    </div>
</div>

@push('css')
<style>
    .page-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        border-color: transparent !important;
    }

    .status-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .page-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .page-card:hover .page-icon-wrapper {
        background: #e8e5ff;
        transform: scale(1.1);
    }

    .hover-primary:hover {
        background-color: #6259ca !important;
        border-color: #6259ca !important;
        color: white !important;
    }

    .page-add-card {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .page-add-card:hover {
        background-color: #f8f7ff !important;
        border-color: #6259ca !important;
    }

    .page-add-card:hover .icon-circle {
        background-color: #6259ca !important;
        color: white !important;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
</style>
@endpush
