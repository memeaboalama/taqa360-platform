@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " .  __('admin::home.Show Sales agent'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-md-3">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                        <span class="avatar avatar-md avatar-rounded bg-success">
                            <i class="ti ti-hash fs-16"></i> </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div><p class="fw-bold mb-0">{{ __('admin::home.Total Orders') }}</p>
                                        <h4 class="fw-semibold mt-1">{{ $stats['totalOrders'] ?? 0  }}</h4>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                        <span class="avatar avatar-md avatar-rounded bg-success">
                            <i class="ti ti-wallet fs-16"></i> </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div><p class="fw-bold mb-0">{{ __('admin::home.Total Collected') }}</p>
                                        <h4 class="fw-semibold mt-1"> {{ $stats['totalAmount'] ?? 0.00  }} <span style="font-size: 14px">{{ __('admin::home.SAR') }}</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                        <span class="avatar avatar-md avatar-rounded bg-success">
                            <i class="ti ti-wallet fs-16"></i> </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div><p class="fw-bold mb-0">{{__('admin::home.Total commission')}}</p>
                                        <h4 class="fw-semibold mt-1"> {{ $stats['totalCommission'] ?? 0.00  }} <span style="font-size: 14px">{{ __('admin::home.SAR') }}</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                        <span class="avatar avatar-md avatar-rounded bg-success">
                            <i class="ti ti-wallet fs-16"></i> </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div><p class="fw-bold mb-0">{{__('admin::home.Due to Sales Agent' )}}</p>
                                        <h4 class="fw-semibold mt-1"> {{ $stats['totalCommission'] ?? 0.00  }} <span style="font-size: 14px">{{ __('admin::home.SAR') }}</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                        <span class="avatar avatar-md avatar-rounded bg-primary">
                            <i class="ti ti-hash fs-16"></i> </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div><p class="fw-bold mb-0">{{__('admin::home.Number of coupons')}}</p>
                                        <h4 class="fw-semibold mt-1">{{ $stats['totalPromocodes'] ?? 0  }}</h4>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-top justify-content-between">
                            <div>
                        <span class="avatar avatar-md avatar-rounded bg-primary">
                            <i class="ti ti-wallet fs-16"></i> </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div><p class="fw-bold mb-0">{{__('admin::home.Number of users')}}</p>
                                        <h4 class="fw-semibold mt-1"> {{ $stats['distinctUsersCount'] ?? 0.00  }} <span style="font-size: 14px">{{ __('admin::home.SAR') }}</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        
           
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <div
                            class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                            <div>
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab"
                                    role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                                data-bs-target="#info-tab-pane" type="button" role="tab"
                                                aria-controls="info-tab-pane" aria-selected="false"><i
                                                class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.Information') }}
                                        </button>
                                    </li>
                                    @if($hasFiles)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="files-tab" data-bs-toggle="tab"
                                                    data-bs-target="#files-tab-pane" type="button" role="tab"
                                                    aria-controls="files-tab-pane" aria-selected="false"><i
                                                    class="ri-file-list-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.Files') }}
                                            </button>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel"
                                     aria-labelledby="info-tab" wire:ignore.self>
                                    <livewire:admin::sales-agent.tabs.info :id="$id"/>
                                </div>

                                @if($hasFiles)
                                    <div class="tab-pane fade" id="files-tab-pane" role="tabpanel"
                                         aria-labelledby="files-tab" wire:ignore.self>
                                        <livewire:admin::sales-agent.tabs.files :id="$id"/>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{{--        @push('js')--}}
{{--            <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj1qLmrodsYHebNZUltIb20_FrZn7DVs4&callback=initMap"></script>--}}
{{--    @endpush--}}
@endsection
