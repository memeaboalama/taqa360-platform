@extends('admin::layout.layout')
@section('title', __('admin::home.Drivers'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
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


{{--                                    <li class="nav-item" role="presentation">--}}
{{--                                        <button class="nav-link" id="orders-tab" data-bs-toggle="tab"--}}
{{--                                                data-bs-target="#orders-tab-pane" type="button" role="tab"--}}
{{--                                                aria-controls="orders-tab-pane" aria-selected="true"><i--}}
{{--                                                class="ri-file-3-fill me-1 align-middle d-inline-block"></i>Orders--}}
{{--                                        </button>--}}
{{--                                    </li>--}}

                                </ul>
                            </div>
                            <div>

                                <div class="progress progress-xs progress-animate">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="30"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel"
                                     aria-labelledby="info-tab">
                                    <livewire:admin::provider.tabs.info.info :provider_id="$id"/>
                                </div>


{{--                                <div class="tab-pane fade" id="orders-tab-pane" role="tabpanel"--}}
{{--                                     aria-labelledby="orders-tab">--}}
{{--                                    <livewire:admin::provider.tabs.orders.all :provider_id="$id"/>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
