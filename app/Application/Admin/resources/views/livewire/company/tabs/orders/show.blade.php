@php use App\Support\Enum\FileTypesEnum; @endphp
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
                        <div>
                            <button class="btn btn-success btn-wave btn-sm waves-effect waves-light"><i
                                    class="ri-printer-line me-1 align-middle d-inline-block"></i>PDF
                            </button>
                            <button class="btn btn-primary btn-wave btn-sm waves-effect waves-light"
                                    onclick="javascript:window.print();"><i
                                    class="ri-printer-line me-1 align-middle d-inline-block"></i>Print
                            </button>
                        </div>
                    </div>
                    @isset($provider_sub_services)
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
                                            <span
                                                class="fs-15 fw-semibold">{{ number_format($provider_sub_services?->price, 2) }}</span>
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
                                            <span
                                                class="fs-16 fw-semibold">{{ number_format($order->total_after_vat, 2) }}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endisset

                    @isset($towingOrder)
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
                                            <span
                                                class="fs-15 fw-semibold">{{ $towingOrderDetails?->total_distance_in_km }}</span>
                                        </td>

                                        <td>
                                            <span
                                                class="fs-15 fw-semibold">{{ $towingOrderDetails?->total_time_in_minutes }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="fs-15 fw-semibold">{{ number_format($towingOrderDetails?->total_price, 2) }}</span>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">
                                            <div class="fw-semibold">@lang('company::home.Total Price :')</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fs-16 fw-semibold">{{ number_format($towingOrderDetails->total_price, 2) }}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endisset


                </div>
            </div>
        </div>

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


        @if(count($order->getImages(FileTypesEnum::CAR_IMAGE)))
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title"> Images</div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive container">
                        <div class="row">
                            @foreach($order->getImages(FileTypesEnum::CAR_IMAGE) as $key => $image)
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <a href="{{ $image }}" class="glightbox card" data-gallery="gallery1">
                                        <img src="{{ $image }}" alt="image">
                                    </a>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        @endif


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
                                    class="fw-semibold text-default">address : </span>{{ $address?->address }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Country : </span>{{ $address?->country->title }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">City : </span>{{ $address?->city->title }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">State : </span>{{ $address?->distract->title }}
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
                                    class="fw-semibold text-default">Ordered Date</span> {{ \Carbon\Carbon::parse($order->date)->format('j F Y') }}
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
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Provider Details </div>
                        @if(!$provider)
                            <a class="btn btn-success btn-wave btn-sm waves-effect waves-light" wire:click="$emit('assignProvider')">
                                <i class="bi bi-plus-lg me-1 align-middle d-inline-block"></i>Assign Provider
                            </a>
                        @else
                            <a class="btn btn-primary btn-wave btn-sm waves-effect waves-light" wire:click="$emit('assignProvider')">
                                <i class="bi bi-plus-lg me-1 align-middle d-inline-block"></i>Re Assign Provider
                            </a>

                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="container mt-4">
                            @if(!$provider)
                        <div class="alert alert-danger d-flex align-items-center container" role="alert">
                            <svg class="flex-shrink-0 me-2 svg-danger" xmlns="http://www.w3.org/2000/svg"
                                 enable-background="new 0 0 24 24" height="1.5rem" viewBox="0 0 24 24" width="1.5rem"
                                 fill="#000000">
                                <g>
                                    <rect fill="none" height="24" width="24"></rect>
                                </g>
                                <g>
                                    <g>
                                        <g>
                                            <path
                                                d="M15.73,3H8.27L3,8.27v7.46L8.27,21h7.46L21,15.73V8.27L15.73,3z M19,14.9L14.9,19H9.1L5,14.9V9.1L9.1,5h5.8L19,9.1V14.9z"></path>
                                            <rect height="6" width="2" x="11" y="7"></rect>
                                            <rect height="2" width="2" x="11" y="15"></rect>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <div>
                                No Employees assigned, please assign employee.
                            </div>
                        </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                            <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">

                                 {{ ucfirst(Str::substr($provider?->name,0,1)) }}
                                </span></div>
                            <div class="flex-fill">
                                <p class="mb-0 fw-bold">{{ $provider?->name }}</p>
                                {{--                                <p class="mb-0 text-muted fs-12">{{ $order->phone }}</p>--}}
                                <p class="mb-0 text-muted fs-12">{{ $provider?->email }}</p>
                            </div>
                            <div><span
                                    class="badge bg-info">{{  $provider?->status }}</span>
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
                                    class="fw-semibold text-default">Phone</span> {{ $provider?->phone_number }}
                            </p>

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Country</span> {{ $provider?->country->title }}
                            </p>

                            @if($provider->company_id)
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default">Company</span> {{ $provider?->company->name }}
                                </p>
                            @endif

                            @if($order->merchant?->last_logged_in_at)
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default">Last Active</span> {{ \Carbon\Carbon::parse($provider?->last_logged_in_at)->format('j Y F g:i A') }}
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


    <livewire:admin::company.tabs.orders.assign :order_id="$order->id" />
</div>

@push('js')
    <script src="{{ asset('assets/vendor') }}/libs/glightbox/js/glightbox.min.js"></script>
    <script src="{{ asset('assets/vendor') }}/js/gallery.js"></script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0"></script>
    <script>
        function initialize() {
            let fromLocation;
            let toLocation;

            @if($towingOrder)
                toLocation = new google.maps.LatLng('{{ $towingOrder?->pickup_address?->latitude }}', '{{ $towingOrder?->pickup_address?->longitude }}');
            fromLocation = new google.maps.LatLng('{{ $towingOrder?->dropoff_address?->latitude }}', '{{ $towingOrder?->dropoff_address?->longitude }}');
            @else
                fromLocation = new google.maps.LatLng('{{ $order->client_location?->latitude }}', '{{ $order->client_location?->longitude }}');
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
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/libs/glightbox/css/glightbox.min.css">
@endpush
