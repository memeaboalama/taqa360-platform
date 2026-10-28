<div>
    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-1"></div>
        <div class="col-xl-7">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="card-title"> @lang('company::home.Order No - ')<span class="text-primary">#{{ $order->id }}</span> <span
                                    class="mx-2 badge bg-success">{{ $order->status }}</span></div>
                            <div>
                                <button class="btn btn-primary btn-wave btn-sm waves-effect waves-light"
                                        onclick="javascript:window.print();"><i
                                        class="ri-printer-line me-1 align-middle d-inline-block"></i>@lang('company::home.Print')
                                </button>
                            </div>
                        </div>
                        @if(isset($provider_sub_services))
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                        <tr>
                                            <th scope="col">@lang('company::home.Sub Service Title')</th>
                                            <th scope="col">@lang('company::home.Sub Service Price')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3"><span
                                                            class="avatar avatar-xxl bg-secondary">
                                                        {{ \Str::substr($provider_sub_services?->title,0,2) }}
                                                    </span>
                                                    </div>
                                                    <div>
                                                        <div class="mb-1 fs-14 fw-semibold">
                                                            <a href="javascript:void(0);">{{ $provider_sub_services?->title }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fs-15 fw-semibold">{{ number_format($provider_sub_services?->price, 2) }}</span>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">
                                                <div class="fw-semibold">@lang('company::home.Sub Total :')</div>
                                            </td>
                                            <td>
                                                {{ number_format($order->total_order, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">
                                                <div class="fw-semibold">@lang('company::home.V.A.T :')</div>
                                            </td>
                                            <td>
                                                {{ number_format($order->vat, 2) }}
                                            </td>
                                        </tr>


                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">
                                                <div class="fw-semibold">@lang('company::home.Total Price :')</div>
                                            </td>
                                            <td>
                                                <span class="fs-16 fw-semibold">{{ number_format($order->total_after_vat, 2) }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if(isset($towingOrderDetails))
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table text-nowrap">
                                        <thead>
                                        <tr>
                                            <th scope="col">@lang('company::home.Towing Type Title')</th>
                                            <th scope="col">@lang('company::home.Total Distance')</th>
                                            <th scope="col">@lang('company::home.Total Time in Mins')</th>
                                            <th scope="col">@lang('company::home.Total Price')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3"><span
                                                            class="avatar avatar-xxl bg-secondary">
                                                        {{ \Str::substr($towingOrderDetails?->towing_type_title,0,2) }}
                                                    </span>
                                                    </div>
                                                    <div>
                                                        <div class="mb-1 fs-14 fw-semibold">
                                                            <a href="javascript:void(0);">{{ $towingOrderDetails?->towing_type_title }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fs-15 fw-semibold">{{ $towingOrderDetails?->total_distance_in_km }}</span>
                                            </td>

                                            <td>
                                                <span class="fs-15 fw-semibold">{{ $towingOrderDetails?->total_time_in_minutes }}</span>
                                            </td>
                                            <td>
                                                <span class="fs-15 fw-semibold">{{ number_format($towingOrderDetails?->total_price, 2) }}</span>
                                            </td>

                                        </tr>


                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2">
                                                <div class="fw-semibold">@lang('company::home.Total Price :')</div>
                                            </td>
                                            <td>
                                                <span class="fs-16 fw-semibold">{{ number_format($towingOrderDetails->total_price, 2) }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.User Details')
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                                <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">
                                {{ \Str::substr(ucfirst($order->user?->name),0,1) }}
                                </span></div>
                                <div class="flex-fill"><p class="mb-0">{{ $order->user?->name }}</p>
                                    <p class="mb-0 text-muted fs-12">{{ $order->user?->email }}</p>
                                    <p class="mb-0 text-muted fs-12">{{ $order->user?->phone_number }}</p>
                                </div>
                            </div>
                            <div class="p-3 border-bottom border-block-end-dashed">
                                <div class="d-flex align-items-center justify-content-between mb-3"><span
                                        class="fs-14 fw-bold">@lang('company::home.User address :')</span>

                                </div>
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default"> @lang('company::home.address :')</span>{{ $address?->address }}
                                </p>
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default"> @lang('company::home.Country :')</span>{{ $address?->country->title }}
                                    Street</p>
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default"> @lang('company::home.City :')</span>{{ $address?->city->title }}
                                </p>
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default"> @lang('company::home.State :')</span>{{ $address?->distract->title }}
                                </p>
                            </div>
                            <div class="p-3 border-bottom border-block-end-dashed">
                                <div class="mb-3"><span class="fs-14 fw-bold">@lang('company::home.Order Summary')</span></div>
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default">@lang('company::home.Ordered Date')</span> {{ \Carbon\Carbon::parse($order->date)->format('j Y F H:m') }}
                                </p>
                                <p class="mb-2 text-muted"><span class="fw-semibold text-default">@lang('company::home.Ordered Time')</span>
                                    {{ \Carbon\Carbon::parse($order->from)->format('g:i A') }}
                                    - {{ \Carbon\Carbon::parse($order->to)->format('g:i A') }} </p>

                            </div>
                        </div>
                        <div class="card-footer">
                            <p class="mb-0 text-muted">
                                <span class="fw-semibold text-default me-2">@lang('company::home.Payment Method')</span>
                                <img width="50px"
                                     src="{{ asset('images/paymentMethods/'.$order->payment_method.'.svg') }}"
                                     alt="{{ $order->payment_method }}"
                                />
                                <span class="mx-2">
                                {{ $order->payment_method }}
                            </span>
                            </p>
                        </div>
                    </div>
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('company::home.Provider Details')
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                                <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">
                                {{ \Str::substr(ucfirst($provider?->name),0,1) }}
                                </span></div>
                                <div class="flex-fill"><p class="mb-0">{{ $provider?->name }}</p>
                                    <p class="mb-0 text-muted fs-12">{{ $provider?->email }}</p>
                                    <p class="mb-0 text-muted fs-12">{{ $provider?->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!--End::row-1 -->
</div>
