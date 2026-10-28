@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . " | " . __('admin::home.showDriver'))

@section('page-header')
    <a href="{{ url('admin/drivers/register') }}" class="d-inline-flex align-items-center text-muted me-2"
       title="{{ __('admin::home.Back') }}">
        <i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ri-arrow-right-line' : 'ri-arrow-left-line' }} fs-20"></i>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-7 col-xl-7">
            <div class="card custom-card">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">{{ __('admin::home.Information') }}</h5>
                </div>
                <div class="card-body p-4">
                    <livewire:admin::driver.tabs.register :id="$id" />
                </div>
            </div>
        </div>
        <div class="col-xxl-5 col-xl-5">
            <div class="card custom-card">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">{{ __('admin::home.Files') }}</h5>
                </div>
                <div class="card-body p-4">
                    <livewire:admin::driver.tabs.files-register :id="$id" />
                </div>
            </div>
        </div>
    </div>
@endsection