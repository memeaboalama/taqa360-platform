@extends('admin::layout.layout')

@section('title', __('admin::lang.Ads Content Management'))

@section('content')
    <div class="main-container container-fluid">
        <div class="inner-body">
            <div class="page-header">
                <div>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin:home') }}">{{ __('admin::lang.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('admin::lang.Ads Content') }}</li>
                    </ol>
                </div>
            </div>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <livewire:admin::ads-content.page-selector />

                            <hr class="my-4">
                            <livewire:admin::ads-content.placement-cards />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
