@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . ' | ' . __('admin::home.showMerchant'))

@push('css')
    <style>
        #pending-merchant-review #pendingMerchantTab .nav-link {
            color: rgb(var(--primary-rgb)) !important;
        }

        #pending-merchant-review #pendingMerchantTab .nav-link.active {
            background-color: rgb(var(--primary-rgb)) !important;
            border-color: rgb(var(--primary-rgb)) !important;
            color: #fff !important;
        }
    </style>
@endpush

@section('content')
    <div id="pending-merchant-review" class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mb-1">{{ __('admin::home.showMerchant') }}</div>
                    <div class="text-muted">{{ $merchant->company_name ?: $merchant->name }}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ url('admin/merchants/pending') }}" class="btn btn-primary-light">
                        {{ __('admin::home.pending') }}
                    </a>
                    @if(!$merchant->merchantActive())
                        <button type="button" class="btn btn-success"
                                onclick="Livewire.emit('activateMerchant', {{ $merchant->id }})">
                            {{ __('admin::home.activate') }}
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-3" id="pendingMerchantTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-info-tab" data-bs-toggle="tab"
                                data-bs-target="#pending-info-pane" type="button" role="tab"
                                aria-controls="pending-info-pane" aria-selected="true">
                            {{ __('admin::home.merchantInfo') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-files-tab" data-bs-toggle="tab"
                                data-bs-target="#pending-files-pane" type="button" role="tab"
                                aria-controls="pending-files-pane" aria-selected="false">
                            {{ __('admin::home.files') }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pendingMerchantTabContent">
                    <div class="tab-pane fade show active" id="pending-info-pane" role="tabpanel"
                         aria-labelledby="pending-info-tab">
                        <livewire:admin::merchant.tabs.info.info :merchant_id="$merchant->id"/>
                    </div>
                    <div class="tab-pane fade" id="pending-files-pane" role="tabpanel"
                         aria-labelledby="pending-files-tab">
                        <livewire:admin::merchant.tabs.info.files :merchant_id="$merchant->id"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:admin::merchant.modal.activate :redirect-after-activation="true"/>
@endsection
