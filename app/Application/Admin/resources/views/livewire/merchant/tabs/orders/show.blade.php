<div class="row">
    <div class="col-xl-1"></div>
    <div class="col-xl-7">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title"> Order No - <span class="text-primary">#{{ $order->id }}</span>
                            <span
                                class="mx-2 badge {{ $order->status?->color() }}">{{ $order->status?->trans() }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">VAT</th>
                                    <th scope="col">Merchant</th>
                                    <th scope="col">Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orderServices as $service)

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="mb-1 fs-14 fw-semibold"><a
                                                            href="javascript:void(0);">{{ $service?->service_name }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ number_format($service?->service_total, 2) }} SAR</span>
                                        </td>


                                        <td><a href="javascript:void(0);"
                                               class="text-primary">{{ number_format($service?->vat, 2) }}
                                                SAR</a></td>


                                        <td><a href="javascript:void(0);"
                                               class="text-info">{{ $service?->merchant?->company_name }} </a></td>

                                        <td> {{ number_format($service?->total_after_vat, 2) }} SAR</td>
                                    </tr>

                                @endforeach


                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">Total Items :</div>
                                    </td>
                                    <td>
                                        {{ $orderServices->count() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">Sub Total :</div>
                                    </td>
                                    <td>
                                        {{ number_format($orderServices->sum('service_total'), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">V.A.T :</div>
                                    </td>
                                    <td>
                                        {{ number_format($orderServices->sum('vat'), 2) }}
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">Total Price :</div>
                                    </td>
                                    <td>
                                        <span
                                            class="fs-16 fw-semibold text-primary">{{ number_format($orderServices->sum('total_after_vat'), 2) }}</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            @if($order->client_lat)
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

    <div class="col-xl-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title"> User Details</div>
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
                            {{--                            <div><span--}}
                            {{--                                    class="badge bg-info-transparent">Last Login :{{ \Carbon\Carbon::parse($orderUser->last_login)->format('j Y F H:m') }}</span>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="d-flex align-items-center justify-content-between mb-3"><span
                                    class="fs-14 fw-bold">User address :</span>

                            </div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">address : </span>{{ $address->address }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Country : </span>{{ $address->country->title }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">City : </span>{{ $address->city->title }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">State : </span>{{ $address->distract->title }}
                            </p>
                        </div>
                        {{--                        <div class="p-3 border-bottom border-block-end-dashed">--}}
                        {{--                            <div class="mb-3"><span class="fs-14 fw-semibold">Send updates to :</span></div>--}}
                        {{--                            <p class="mb-2 text-muted"><span class="fw-semibold text-default">Phone : </span>--}}
                        {{--                                (555)-0123-1210 </p>--}}
                        {{--                            <p class="mb-0 text-muted"><span class="fw-semibold text-default">Email : </span>--}}
                        {{--                                jsontaylor2134@gmail.com </p></div>--}}
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">Order Summary</span></div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Ordered Date</span> {{ \Carbon\Carbon::parse($order->date)->format('j Y F H:m') }}
                            </p>
                            <p class="mb-2 text-muted"><span class="fw-semibold text-default">Ordered Time</span>
                                {{ \Carbon\Carbon::parse($order->from)->format('g:i A') }}
                                - {{ \Carbon\Carbon::parse($order->to)->format('g:i A') }} </p>

                        </div>
                    </div>
                    <div class="card-footer">
                        <p class="mb-0 text-muted">
                            <span class="fw-semibold text-default me-2">Payment Method</span>
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
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Merchant Details</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                            <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">

                                <img src="{{ $order->merchant?->logo }}" alt="logo"/>
                                </span></div>
                            <div class="flex-fill">
                                <p class="mb-0 fw-bold">{{ $order->merchant?->company_name }}</p>
                                <p class="mb-0 text-muted fs-12">{{ $order->phone }}</p>
                                <p class="mb-0 text-muted fs-12">{{ $orderUser->email }}</p>
                            </div>
                            <div><span
                                    class="badge bg-info">{{  $order->merchant?->status }}</span>
                            </div>
                        </div>

                        {{--                        <div class="p-3 border-bottom border-block-end-dashed">--}}
                        {{--                            <div class="mb-3"><span class="fs-14 fw-semibold">Send updates to :</span></div>--}}
                        {{--                            <p class="mb-2 text-muted"><span class="fw-semibold text-default">Phone : </span>--}}
                        {{--                                (555)-0123-1210 </p>--}}
                        {{--                            <p class="mb-0 text-muted"><span class="fw-semibold text-default">Email : </span>--}}
                        {{--                                jsontaylor2134@gmail.com </p></div>--}}
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">Information</span></div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Phone</span> {{ $order->merchant?->phone_number }}
                            </p>

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Country</span> {{ $order->merchant?->country->title }}
                            </p>

                            @if($order->merchant?->last_logged_in_at)
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default">Last Active</span> {{ \Carbon\Carbon::parse($order->merchant?->last_logged_in_at)->format('j Y F g:i A') }}
                                </p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    <div class="col-xl-6">--}}
    {{--        <div class="card custom-card">--}}
    {{--            <div class="card-header">--}}
    {{--                <div class="card-title"> Order Tracking</div>--}}
    {{--                <div class="ms-auto text-success">#SPK1218153635</div>--}}
    {{--            </div>--}}
    {{--            <div class="card-body">--}}
    {{--                <div class="order-track">--}}
    {{--                    <div class="accordion" id="basicAccordion">--}}
    {{--                        <div class="accordion-item border-0 bg-transparent">--}}
    {{--                            <div class="accordion-header" id="headingOne"><a class="px-0 pt-0" href="javascript:void(0)"--}}
    {{--                                                                             role="button" data-bs-toggle="collapse"--}}
    {{--                                                                             data-bs-target="#basicOne"--}}
    {{--                                                                             aria-expanded="true"--}}
    {{--                                                                             aria-controls="basicOne">--}}
    {{--                                    <div class="d-flex mb-0">--}}
    {{--                                        <div class="me-2"><span class="avatar avatar-md avatar-rounded"> <img--}}
    {{--                                                    src="{{ asset('assets/vendor') }}/images/ecommerce/png/32.png"--}}
    {{--                                                    alt=""> </span></div>--}}
    {{--                                        <div class="flex-fill"><p class="fw-semibold mb-0 fs-14">Order Placed</p><span--}}
    {{--                                                class="fs-11 text-success">Nov 03, 2022</span></div>--}}
    {{--                                    </div>--}}
    {{--                                </a></div>--}}
    {{--                            <div id="basicOne" class="accordion-collapse collapse show border-top-0"--}}
    {{--                                 aria-labelledby="headingOne" data-bs-parent="#basicAccordion">--}}
    {{--                                <div class="accordion-body pt-0 ps-5">--}}
    {{--                                    <div class="fs-11"><p class="mb-0">Order placed successfully by <a--}}
    {{--                                                href="javascript:void(0);" class="font-weight-semibold text-primary">Sansa--}}
    {{--                                                Taylor</a></p><span class="text-muted op-5">Nov 03, 2022, 15:36</span>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="accordion" id="basicAccordion1">--}}
    {{--                        <div class="accordion-item border-0 bg-transparent">--}}
    {{--                            <div class="accordion-header" id="headingTwo"><a class="px-0 pt-0" href="javascript:void(0)"--}}
    {{--                                                                             role="button" data-bs-toggle="collapse"--}}
    {{--                                                                             data-bs-target="#basicTwo"--}}
    {{--                                                                             aria-expanded="true"--}}
    {{--                                                                             aria-controls="basicTwo">--}}
    {{--                                    <div class="d-flex mb-0">--}}
    {{--                                        <div class="me-2"><span class="avatar avatar-md avatar-rounded"> <img--}}
    {{--                                                    src="{{ asset('assets/vendor') }}/images/ecommerce/png/33.png"--}}
    {{--                                                    alt=""> </span></div>--}}
    {{--                                        <div class="flex-fill"><p class="fw-semibold mb-0 fs-14">Picked</p><span--}}
    {{--                                                class="fs-12">Nov 03, 15:10</span></div>--}}
    {{--                                    </div>--}}
    {{--                                </a></div>--}}
    {{--                            <div id="basicTwo" class="accordion-collapse show collapse border-top-0"--}}
    {{--                                 aria-labelledby="headingTwo" data-bs-parent="#basicAccordion1">--}}
    {{--                                <div class="accordion-body pt-0 ps-5">--}}
    {{--                                    <div class="fs-11"><p class="mb-0">Your order has been picked up by <span--}}
    {{--                                                class="font-weight-semibold">Smart Good Services</span></p><span--}}
    {{--                                            class="text-muted op-5">Nov 03, 2022, 15:36</span></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="accordion" id="basicAccordion2">--}}
    {{--                        <div class="accordion-item border-0 bg-transparent">--}}
    {{--                            <div class="accordion-header" id="headingThree"><a class="px-0 pt-0"--}}
    {{--                                                                               href="javascript:void(0)" role="button"--}}
    {{--                                                                               data-bs-toggle="collapse"--}}
    {{--                                                                               data-bs-target="#basicThree"--}}
    {{--                                                                               aria-expanded="true"--}}
    {{--                                                                               aria-controls="basicThree">--}}
    {{--                                    <div class="d-flex mb-0">--}}
    {{--                                        <div class="me-2"><span class="avatar avatar-md avatar-rounded"> <img--}}
    {{--                                                    src="{{ asset('assets/vendor') }}/images/ecommerce/png/34.png"--}}
    {{--                                                    alt=""> </span></div>--}}
    {{--                                        <div class="flex-fill"><p class="fw-semibold mb-0 fs-14">Shipping</p><span--}}
    {{--                                                class="fs-12">Nov 03, 15:10</span></div>--}}
    {{--                                    </div>--}}
    {{--                                </a></div>--}}
    {{--                            <div id="basicThree" class="accordion-collapse show collapse border-top-0"--}}
    {{--                                 aria-labelledby="headingThree" data-bs-parent="#basicAccordion2">--}}
    {{--                                <div class="accordion-body pt-0 ps-5">--}}
    {{--                                    <div class="fs-11 mb-3"><p class="mb-0">Arrived USA <span--}}
    {{--                                                class="font-weight-semibold">SGS Warehouse</span></p><span--}}
    {{--                                            class="text-muted op-5">Nov 03, 2022, 15:36</span></div>--}}
    {{--                                    <div class="fs-11 mb-3"><p class="mb-0">picked up by <span--}}
    {{--                                                class="font-weight-semibold">SGS Agent</span> and on the way to--}}
    {{--                                            Hyderabad</p><span class="text-muted op-5">Nov 03, 2022, 15:36</span></div>--}}
    {{--                                    <div class="fs-11"><p class="mb-0">Arrived in Hyderabad and expected Delivery is--}}
    {{--                                            <span class="font-weight-semibold">Apr 16, 2022</span></p><span--}}
    {{--                                            class="text-muted op-5">Nov 03, 2022, 15:36</span></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="accordion" id="basicAccordion3">--}}
    {{--                        <div class="accordion-item border-0 bg-transparent next-step">--}}
    {{--                            <div class="accordion-header" id="headingFour"><a class="collapsed px-0 pt-0"--}}
    {{--                                                                              href="javascript:void(0)" role="button"--}}
    {{--                                                                              data-bs-toggle="collapse"--}}
    {{--                                                                              data-bs-target="#basicFour"--}}
    {{--                                                                              aria-expanded="true"--}}
    {{--                                                                              aria-controls="basicFour">--}}
    {{--                                    <div class="d-flex mb-2">--}}
    {{--                                        <div class="me-2"><span--}}
    {{--                                                class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary border"><i--}}
    {{--                                                    class="fe fe-package fs-12"></i></span></div>--}}
    {{--                                        <div class="flex-fill"><p class="fw-semibold mb-0 fs-14">Out For Delivery</p>--}}
    {{--                                            <span class="text-muted fs-12">Nov 03, 15:10 (expected)</span></div>--}}
    {{--                                    </div>--}}
    {{--                                </a></div>--}}
    {{--                            <div id="basicFour" class="accordion-collapse collapse border-top-0"--}}
    {{--                                 aria-labelledby="headingFour" data-bs-parent="#basicAccordion3">--}}
    {{--                                <div class="accordion-body pt-0 ps-5">--}}
    {{--                                    <div class="fs-11"><p class="mb-0">Your order is out for devlivery</p><span--}}
    {{--                                            class="text-muted op-5">Nov 03, 2022, 15:36 (expected)</span></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="accordion" id="basicAccordion4">--}}
    {{--                        <div class="accordion-item border-0 bg-transparent next-step">--}}
    {{--                            <div class="accordion-header" id="headingFive"><a class="collapsed px-0 pt-0"--}}
    {{--                                                                              href="javascript:void(0)" role="button"--}}
    {{--                                                                              data-bs-toggle="collapse"--}}
    {{--                                                                              aria-expanded="true">--}}
    {{--                                    <div class="d-flex mb-2">--}}
    {{--                                        <div class="me-2"><span--}}
    {{--                                                class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary border"><i--}}
    {{--                                                    class="fe fe-package fs-12"></i></span></div>--}}
    {{--                                        <div class="flex-fill"><p class="fw-semibold mb-0 fs-14">Delivered</p><span--}}
    {{--                                                class="fs-12 text-muted">Nov 03, 18:42</span></div>--}}
    {{--                                    </div>--}}
    {{--                                </a></div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>



@push('js')
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}&callback=initMap"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0&callback=initMap"></script>
    <script>
        function initialize() {
            let fromLocation;
            let toLocation;

            @if($order?->client_lat)
                fromLocation = new google.maps.LatLng('{{ $order?->client_lat }}', '{{ $order?->client_long }}');
            @endif

            let mapOptions = {
                center: fromLocation,
                zoom: 8
            };

            let map = new google.maps.Map(document.getElementById('map'), mapOptions);

            let fromMarker = new google.maps.Marker({
                position: fromLocation,
                map: map,
                title: 'From Location'
            });

            let toMarker = new google.maps.Marker({
                position: toLocation,
                map: map,
                title: 'To Location'
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endpush
