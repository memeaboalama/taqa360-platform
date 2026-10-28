<div class="row">
    <div class="col-xl-1"></div>
    <div class="col-xl-7">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title"> @lang('admin::home.Order No') - <span
                                class="text-primary">#{{ $order->id }}</span>
                            <span
                                class="mx-2 badge {{ $order->status?->color() }}">{{ $order->status?->trans() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('admin::home.item')</th>
                                    <th scope="col">@lang('admin::home.qty')</th>
                                    <th scope="col">@lang('admin::home.Price')</th>
                                    <th scope="col">@lang('admin::home.Merchant')</th>
                                    <th scope="col">@lang('admin::home.Total Price')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orderItems as $item)

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="mb-1 fs-14 fw-semibold"><a
                                                            href="javascript:void(0);">{{ $item?->item_name }}</a>

                                                        <br>
                                                        <sapn
                                                            class="text-info"> {{implode(',',$item->addonName)}}</sapn>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ $item->qty }}</span>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ number_format($item?->item?->default_price, 2) }} @lang('admin::home.SAR')</span>
                                        </td>


                                        <td><a href="javascript:void(0);"
                                               class="text-info">{{ $item?->merchant?->company_name }} </a></td>

                                        <td> {{ number_format($item?->item_total, 2) }} @lang('admin::home.SAR')</td>
                                    </tr>

                                @endforeach


                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">@lang('admin::home.Total Items') :</div>
                                    </td>
                                    <td>
                                        {{ $orderItems->sum('qty') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">@lang('admin::home.Sub Total') :</div>
                                    </td>
                                    <td>
                                        {{ number_format($order->subtotal, 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">@lang('admin::home.VAT') :</div>
                                    </td>
                                    <td>
                                        {{ number_format($order->vat, 2) }}
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">@lang('admin::home.Total Price') :</div>
                                    </td>
                                    <td>
                                        <span
                                            class="fs-16 fw-semibold text-primary">{{ number_format($order->total_after_vat, 2) }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            @if($order?->address?->latitude??$order->client_lat)
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="card-title"> Location</div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <div id="map" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-xl-3 hide-from-print">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title"> @lang('admin::home.User Details')</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                            <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">
                                {{ \Str::substr(ucfirst($orderUser->name),0,1) }}
                                </span></div>
                            <div class="flex-fill"><p class="mb-0">{{ $orderUser->name }}</p>
                                <p class="mb-0 text-muted fs-12">{{ $orderUser->email }}</p>
                                <p class="mb-0 text-muted fs-12">{{ $orderUser->phone_number }}</p>
                            </div>
                        </div>
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="d-flex align-items-center justify-content-between mb-3"><span
                                    class="fs-14 fw-bold">@lang('admin::home.User address') :</span>

                            </div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.address') : </span>
                                <a href="https://maps.google.com/?q={{ $address?->address }}" target="_blank"
                                   style="color:#0b5ee7">{{ $address?->address }}</a>
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Country') : </span>{{ $address?->country->title }}
                                Street</p>
                        </div>
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">@lang('admin::home.Order Summary')</span>
                            </div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Ordered Date')</span> {{ \Carbon\Carbon::parse($order->created_at)->format('Y m d - h:i') }}
                            </p>


                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Ordered Duration')</span>
                                {{ \Carbon\Carbon::parse($order->from)->format('g:i A') }}
                                - {{ \Carbon\Carbon::parse($order->to)->format('g:i A') }} </p>

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Scheduled date')</span> {{ \Carbon\Carbon::parse($order->date)->format('Y m d - h:i') }}
                            </p>

                        </div>
                    </div>
                    <div class="card-footer">
                        <p class="mb-0 text-muted">
                            <span class="fw-semibold text-default me-2">Payment Method</span>
                            <img width="50px"
                                 src="{{asset('images/paymentMethods/'.$order?->payment_method.'.png')}}"
                                 alt="{{ $order?->payment_method }}"
                            />
                            <span class="mx-2">
                                {{ $order->payment_method }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js')
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0"></script>
    <script>
        function initialize() {
            let fromLocation;
            let toLocation;


            fromLocation = new google.maps.LatLng('{{ $order?->branch?->latitude }}', '{{ $order?->branch?->longitude }}');


            toLocation = new google.maps.LatLng('{{ $order?->address?->latitude??$order->client_lat }}', '{{ $order?->address?->longitude?? $order->client_long }}');


            let mapOptions = {
                center: fromLocation,
                zoom: 8
            };

            let map = new google.maps.Map(document.getElementById('map'), mapOptions);

            let fromMarker = new google.maps.Marker({
                position: fromLocation,
                map: map,
                title: 'From Location',
                icon: {
                    url: "{{ asset('images/map/restaurant.png') }}",
                    scaledSize: new google.maps.Size(50, 50),
                    origin: new google.maps.Point(0,0),
                    anchor: new google.maps.Point(0, 0)
                }
            });

            let toMarker = new google.maps.Marker({
                position: toLocation,
                map: map,
                title: 'To Location',
                icon: {
                    url: "{{ asset('images/map/location-pin.png') }}",
                    scaledSize: new google.maps.Size(50, 50),
                    origin: new google.maps.Point(0,0),
                    anchor: new google.maps.Point(0, 0)
                }
            });


        }

        google.maps.event.addDomListener(window, 'load', initialize);


    </script>
@endpush
