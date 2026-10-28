@extends('admin::layout.layout')

@section('title', __('admin::lang.Advertising Pages'))

@section('content')
    <div class="main-container container-fluid">
        <div class="inner-body">
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">{{ __('admin::lang.Ad Management') }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin:home') }}">{{ __('admin::lang.home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('admin::lang.Advertising Pages') }}</li>
                    </ol>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="mb-4">
                        <h6 class="tx-14 text-muted mb-1">{{ __('admin::lang.Configuration') }}</h6>
                        <p class="mb-0 text-muted">{{ __('admin::lang.Manage advertising pages and their placements') }}</p>
                    </div>

                    <livewire:admin::ad-page.page-cards />
                </div>
            </div>
        </div>
    </div>

    <livewire:admin::ad-page.modal.create />
    <livewire:admin::ad-page.modal.update />
    <livewire:admin::ad-page.modal.delete />
@endsection
