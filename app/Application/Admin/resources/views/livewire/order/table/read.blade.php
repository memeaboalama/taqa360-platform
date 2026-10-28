<div class="row">
    <div class="col-xl-1"></div>
    <div class="col-xl-7">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title"> @lang('admin::home.Order No') - <span
                                class="text-primary">#{{ $order->code }}</span>
                            @php
                                $statusEnum = is_string($order->status) ? (\App\Application\Marketplace\Enums\MerchantOrderStatuses::tryFrom($order->status)) : $order->status;
                            @endphp
                            <span
                                class="mx-2 badge {{ $statusEnum?->color() }}">{{ $statusEnum?->trans() }}</span>
                            <span
                                class="mx-2 badge bg-info"> {{ __('admin::home.' . $order?->type_service_name()) }}</span>

                        </div>
                        <div>
                            @php
                                $currentMarkEnum = is_string($mark) ? (\App\Application\Marketplace\Enums\MerchantOrderMarks::tryFrom($mark) ?? \App\Application\Marketplace\Enums\MerchantOrderMarks::WHITE) : $mark;
                            @endphp
                            <select class="form-control mx-2 badge {{ $currentMarkEnum?->color() }}" wire:change="$emit('updateMark')" wire:model.defer="mark">
                                @foreach($marks as $value)
                                @php
                                    $itemEnum = is_string($value) ? (\App\Application\Marketplace\Enums\MerchantOrderMarks::tryFrom($value) ?? \App\Application\Marketplace\Enums\MerchantOrderMarks::WHITE) : $value;
                                @endphp
                                <option value="{{ $itemEnum?->value ?? $value }}" class="mx-2 badge {{ $itemEnum?->color() }}"><span>{{ $itemEnum?->trans() }}</span></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row col-xl-12">
                            <div class="col-xl-10">
                                <!-- <label for="admin_note" class="form-label text-black">{{ __('admin::home.Admin Note') }} </label> -->
                                <textarea wire:change="$emit('updateNote')" class="form-control text-black bg-gray-200"
                                    rows="1" placeholder="{{ __('admin::home.Admin Note') }}" wire:model.defer="admin_note"></textarea>
                            </div>
                            <div class="col-xl-2">
                                <button type="button" class="btn btn-success" wire:click="updateNote"
                                    wire:loading.attr="disabled" wire:target="updateNote">
                                    <span class="indicator-label" wire:loading.class="d-none" wire:target="updateNote">
                                        @lang('admin::home.Save')
                                    </span>
                                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="updateNote">
                                        @lang('admin::home.Wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                        <!-- @AdminHasRole(['show invoice_orders'])
                        <div>
                            <a href="{{route('admin:merchant.orders.pdf',$order->id)}}" target="_blank"
                               class="btn btn-primary btn-wave btn-sm waves-effect waves-light hide-from-print"
                            ><i
                                    class=" me-1 align-middle d-inline-block"></i>Invoice
                            </a>
                        </div>
                        @endAdminHasRole -->

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
                                                        <sapn class="text-info"> {{implode(',',$item->addonName)}}</sapn>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ $item->qty }}</span>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ number_format($item?->itemPrice, 2) }} @lang('admin::home.SAR')</span>
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
                                            <div class="fw-semibold">@lang('admin::home.delivery_fee') :</div>
                                        </td>
                                        <td>
                                            {{ number_format($order->delivery_fee, 2) }}
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
                                    <tr style="{{$order->discount == 0 ? 'display:none': ''}}">
                                        <td colspan="2"></td>
                                        <td colspan="2">
                                            <div class="fw-semibold">@lang('admin::home.Discount') :</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fs-14 fw-semibold">{{ number_format($order->discount, 2) }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr style="{{$order->from_wallet == 0 ? 'display:none': ''}}">
                                        <td colspan="2"></td>
                                        <td colspan="2">
                                            <div class="fw-semibold">@lang('admin::home.Paid From wallet') :</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fs-14 fw-semibold">{{ number_format($order->from_wallet, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr style="{{$order->from_merchant_balance == 0  ? 'display:none': ''}}">
                                        <td colspan="2"></td>
                                        <td colspan="2">
                                            <div class="fw-semibold">@lang('admin::home.Paid From Merchant Balance') :</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fs-14 fw-semibold">{{ number_format($order->from_merchant_balance, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">
                                            <div class="fw-semibold">@lang('admin::home.Total Price') :</div>
                                        </td>
                                        <td>
                                            <span
                                                class="fs-16 fw-semibold text-primary">{{ number_format($order->total_after_vat, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            @if($order->client_lat??$address->latitude??$order?->addressInOrder?->latitude)
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
                            <div class="flex-fill">
                                <p class="mb-0">{{ $orderUser->name }}</p>
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
                                @if($address)
                                <a href="https://maps.google.com/?q={{ $address?->address }}" target="_blank"
                                    style="color:#0b5ee7">{{ $address?->address }}</a>
                                @else
                                <a href="https://maps.google.com/?q={{ $order?->addressInOrder?->address??$address?->address }}" target="_blank"
                                    style="color:#0b5ee7">{{ $order?->addressInOrder?->address??$address?->address }}</a>
                                @endif
                            </p>
                            @if($address)
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Country') : </span>{{ $address?->country->title }}
                            </p>
                            @else
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Country') : </span>{{ $order?->addressInOrder?->title ?? $address?->country->title }}
                            </p>
                            @endif

                        </div>
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">@lang('admin::home.Order Summary')</span>
                            </div>

                            <p class="mb-2 text-danger"><span
                                    class="fw-semibold text-default">@lang('admin::home.Order Note')</span> {{ $order->note }}
                            </p>

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Ordered Date')</span> {{ \Carbon\Carbon::parse($order->created_at)->format('Y m d - h:i') }}
                            </p>


                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Ordered Duration')</span>
                                {{ \Carbon\Carbon::parse($order->from)->format('g:i A') }}
                                - {{ \Carbon\Carbon::parse($order->to)->format('g:i A') }}
                            </p>

                            @if($order->schedulable)
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Scheduled date')</span> {{ \Carbon\Carbon::parse($order->date)->format('Y m d - h:i') }}
                            </p>
                            @endif

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('admin::home.Distance between Customer and Branch')</span> {{ $order?->delivery_distance }}
                            </p>

                        </div>

                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-2">
                                @if($order->driver)
                                    <span class="fs-14 fw-bold">تم تعيين السائق:</span>
                                    <span class="fw-semibold text-success">{{" ".$driver?->name." "}}</span>
                                    <img src="{{ asset('assets/vendor') }}/images/green.png" width="20" style="margin: 5px" alt="Driver Assigned" class="desktop-logo">
                                    <p class="mb-2 text-muted mt-1"><span class="fw-semibold text-default">المسافة بين السائق والفرع:</span> {{ $order?->driverBranchDistance }}</p>
                                @else
                                    <span class="fs-14 fw-bold">تعيين سائق:</span>
                                    <img src="{{ asset('assets/vendor') }}/images/red.png" width="20" style="margin: 5px" alt="No Driver Assigned" class="desktop-logo">
                                    <span class="text-danger fs-12">(لم يتم الإسناد بعد)</span>
                                @endif
                            </div>
                            @php
                                $statusEnumForDriver = is_string($order->status) ? (\App\Application\Marketplace\Enums\MerchantOrderStatuses::tryFrom($order->status)) : $order->status;
                                $statusValForDriver = $statusEnumForDriver?->value ?? (is_string($order->status) ? $order->status : '');
                            @endphp
                            @if($statusValForDriver !== 'on-way' && $statusValForDriver !== 'completed' && $statusValForDriver !== 'previous' && $statusValForDriver !== 'cancelled')
                            <div class="mt-3">
                                <label class="form-label fw-bold text-dark mb-1">
                                    {{ $order->driver ? 'تغيير السائق المسند:' : 'إسناد سائق للطلب:' }}
                                </label>
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" wire:model.defer="selected_driver_id">
                                        <option value="">-- اختر السائق --</option>
                                        @foreach($drivers as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->phone }})</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="assignDriver" type="button" class="btn btn-primary btn-sm text-nowrap"
                                            wire:loading.attr="disabled" wire:target="assignDriver">
                                        <span wire:loading.class="d-none" wire:target="assignDriver">{{ $order->driver ? 'تغيير السائق' : 'تأكيد الإسناد' }}</span>
                                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="assignDriver">
                                            @lang('admin::home.Wait')
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if($order->gift)
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">Order is a gift to</span>
                            </div>

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Receipt name</span> {{ $order->client_name }}
                            </p>

                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Receipt phone</span> {{ $order->client_phone }}
                            </p>
                            @if($order->gift_confidential)
                            <p class="mb-2 text-muted text-center"><span
                                    class="fw-semibold text-success ">The content and the price of the gift is confidential and are not disclosed to the friend</span>
                            </p>
                            @else
                            <p class="mb-2 text-muted text-center"><span
                                    class="fw-semibold text-danger">The content and the price of the gift is not confidential and are disclosed to the friend</span>
                            </p>
                            @endif
                            @if(!$order->ask_receipt)
                            <p class="mb-2 text-muted">
                                <span class="fw-semibold text-default">Receipt Location</span>
                            <p class="mb-2 text-muted text-center"><span
                                    class="fw-semibold text-danger">Customer don't know the location of the friend and asked the receipt for the location</span>
                            </p>
                            </p>
                            @endif

                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <p class="mb-0 text-muted">
                            <span class="fw-semibold text-default me-2">Payment Method</span>
                            @if($payment?->source_company)
                            <img width="50px"
                                src="{{asset('images/paymentMethods/'.$payment?->source_company.'.png')}}"
                                alt="{{ $payment?->source_company}}" />
                            <span class="mx-2">
                                {{ $payment?->source_company}}
                            </span>
                            @else
                            <img width="50px"
                                src="{{asset('images/paymentMethods/'.$order?->payment_method.'.png')}}"
                                alt="{{ $order?->payment_method }}" />
                            <span class="mx-2">
                                {{ $order?->payment_method }}
                            </span>

                            @endif
                        </p>
                        <br>
@php
    $payTransactionId = $payment?->payment_id ?: ($order?->code ? '#' . $order->code : '—');
    $orderAmount = $order?->total_after_vat ?: ($order?->total_after_discount ?: ($order?->subtotal ?: 0));
    $paymentMethodKey = strtolower(trim((string) ($order?->payment_method ?? '')));
    $resolvedPaymentStatus = is_string($payment?->status ?? null) ? strtolower(trim($payment->status)) : null;
    if ($resolvedPaymentStatus === 'success') {
        $resolvedPaymentStatus = 'paid';
    }
    $isCodOrder = in_array($paymentMethodKey, ['cod','cash','cash_on_delivery','cashondelivery'], true);
    $payStatus = $resolvedPaymentStatus ?: (($payment && (float) ($payment->amount ?? 0) > 0) ? 'paid' : ($order?->payment_status ?: null));
    if ($isCodOrder && !in_array($payStatus, ['paid', 'pending', 'failed', 'initiated', 'partially_paid', 'partial'], true)) {
        $payStatus = 'paid';
    }
    $payLabel = match($payStatus) {
                                'paid' => __('admin::home.Payment Status paid'),
                                'initiated' => __('admin::home.Payment initiated'),
                                'failed' => __('admin::home.Payment failed'),
                                'pending' => __('ecommerce::ecommerce.payment_pending'),
                                'unpaid' => __('ecommerce::ecommerce.unpaid'),
                                'partially_paid', 'partial' => 'مدفوع جزئياً',
                                default => ($order?->payment_label ?: ($payStatus ?: __('ecommerce::ecommerce.unpaid'))),
                            };
                            $payBadgeClass = match($payStatus) {
                                'paid' => 'bg-success',
                                'initiated', 'pending' => 'bg-warning',
                                'failed' => 'bg-danger',
                                'partially_paid', 'partial' => 'bg-info',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <p class="mb-2 text-muted"><span
                                class="fw-semibold text-default">@lang('admin::home.Payment Transaction Number')</span> {{ $payTransactionId }}
                        </p>
                        <p class="mb-2 text-muted"><span
                                class="fw-semibold text-default">@lang('admin::home.Amount Paid')</span>
                            @if(!empty($payment?->amountFormat))
                                {{ $payment->amountFormat }}
                            @elseif(!empty($payment?->amount))
                                {{ number_format($payment->amount, 2) }} @lang('admin::home.SAR')
                            @else
                                {{ number_format($orderAmount, 2) }} @lang('admin::home.SAR')
                            @endif
                        </p>
                        <p class="mb-2 text-muted"><span
                                class="fw-semibold text-default">@lang('admin::home.Payment Status')</span> <span class="badge {{ $payBadgeClass }}">{{ $payLabel }}</span>
                        </p>
                        <p class="mb-2 text-muted"><span
                                class="fw-semibold text-default">@lang('admin::home.RefundpaymentAmountLast')</span> {{ $order?->refundpayment?->sum('amount') }}
                        </p>
                        @if($payment)
                        <button type="submit" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createRefundPayment">
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('admin::home.RefundpaymentType toWallet')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('admin::home.Wait')
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <livewire:admin::refundpayment.modal.create :order="$order" /> --}}

@push('js')
<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{config('services.google_maps.api_key')}}&callback=initMap"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0&callback=initMap"></script>
<script>
    function initialize() {
        let fromLocation;
        let toLocation;


        fromLocation = new google.maps.LatLng('{{ $order?->branch?->latitude }}', '{{ $order?->branch?->longitude }}');


        toLocation = new google.maps.LatLng('{{ $order->client_lat?? $address?->latitude??$order?->addressInOrder?->latitude }}', '{{  $order->client_long?? $address?->longitude??$order?->addressInOrder?->longitude }}');
        // toLocation = new google.maps.LatLng('{{ $order->client_lat?? $address?->latitude}}', '{{  $order->client_long??$address?->longitude }}');


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
                origin: new google.maps.Point(0, 0),
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
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(0, 0)
            }
        });


    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endpush
