@extends('admin::layout.layout')

@section('title', __('admin::lang.Ad Placement Settings'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">
                <i class="fe fe-settings me-2"></i>{{ __('admin::lang.Ad Placements Settings') }}
            </h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin:home') }}">{{ __('admin::lang.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin:ad-management.pages.index') }}">{{ __('admin::lang.Advertising Pages') }}</a></li>
                <li class="breadcrumb-item active">{{ __('admin::lang.Placement Settings') }}</li>
            </ol>
        </div>
    </div>

    <livewire:admin::ad-placement.settings-panel :pageId="$page->id" />

    <livewire:admin::ad-placement.modal.create />
    <livewire:admin::ad-placement.modal.update />
    <livewire:admin::ad-placement.modal.delete />
@endsection
