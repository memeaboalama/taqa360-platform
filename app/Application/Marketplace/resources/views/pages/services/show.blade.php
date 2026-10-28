@extends('marketplace::layout.layout')
@section('title',"Merchant |" . __('marketplace::home.show'))

@section('content')
<div class="row">
    <div class="col-xl-12">
        <!-- Start:: row-3 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            {{ $service->title }}
                        </div>
                        {{-- <div class="prism-toggle">
                                <button
                                    class="btn btn-primary-light m-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#createAddons">@lang('marketplace::home.Add New Addons Category')<i class="bi bi-plus-lg ms-2"></i></button>
                            </div> --}}
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3 nav-justified nav-style-1 d-sm-flex d-block" role="tablist" wire:ignore>
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#home1-justified" aria-selected="false">@lang('marketplace::home.Service Details')</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" href="#about1-justified" aria-selected="false">@lang('marketplace::home.Service Addons Category')</a>
                            </li> --}}

                        </ul>
                        <div class="tab-content">
                            <livewire:marketplace::service.tabs.update :service="$service" />
                            {{-- <livewire:marketplace::service.tabs.addons :service="$service" :addon_categories="$addon_categories" /> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: row-3 -->

    </div>
</div>
@endsection
