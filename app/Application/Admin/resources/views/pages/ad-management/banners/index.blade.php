@extends('admin::layout.layout')

@section('title', __('admin::lang.Ad Banners') . ' - ' . $placement->name)

@section('content')
    <div class="main-container container-fluid">
        <div class="inner-body">
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">{{ __('admin::lang.Ad Banners') }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin:home') }}">{{ __('admin::lang.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin:ad-management.pages.index') }}">{{ __('admin::lang.Advertising Pages') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin:ad-management.placements.index', $placement->page_id) }}">{{ __('admin::lang.Ad Placements') }}</a></li>
                        <li class="breadcrumb-item active">{{ $placement->name }}</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <button class="btn ripple btn-primary" wire:click="$dispatch('openCreateModal', { placementId: {{ $placement->id }} })">
                        <i class="fe fe-plus me-2"></i>{{ __('admin::home.Add New') }}
                    </button>
                </div>
            </div>

            <div class="row row-sm mb-3">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">{{ __('admin::lang.Placement Type') }}</p>
                                    <h6 class="mb-0"><i class="bi {{ $placement->type->icon() }} me-2"></i>{{ $placement->type->label() }}</h6>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">{{ __('admin::lang.Location') }}</p>
                                    <h6 class="mb-0">{{ ucfirst($placement->location) }}</h6>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">{{ __('admin::home.Status') }}</p>
                                    <h6 class="mb-0"><span class="badge badge-{{ $placement->status === \App\Support\Enum\StatusEnum::ACTIVE ? 'success' : 'secondary' }}">{{ $placement->status->value }}</span></h6>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">{{ __('admin::lang.Total Banners') }}</p>
                                    <h6 class="mb-0">{{ $placement->banners()->count() }} / {{ $placement->max_banners ?? '∞' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-header border-bottom-0 pb-0">
                            <ul class="nav nav-tabs nav-tabs-header mb-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#active-banners" role="tab" aria-selected="true">
                                        <i class="fe fe-eye me-1"></i> {{ __('admin::lang.Active Banners') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#history-banners" role="tab" aria-selected="false">
                                        <i class="fe fe-clock me-1"></i> {{ __('admin::lang.History') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="active-banners" role="tabpanel">
                                    <livewire:admin::ad-banner.table.all :placementId="$placement->id" />
                                </div>
                                <div class="tab-pane" id="history-banners" role="tabpanel">
                                    <livewire:admin::ad-banner.table.history :placementId="$placement->id" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:admin::ad-banner.modal.create />
    <livewire:admin::ad-banner.modal.update />
    <livewire:admin::ad-banner.modal.delete />
    <livewire:admin::ad-banner.modal.reactivate />
@endsection
