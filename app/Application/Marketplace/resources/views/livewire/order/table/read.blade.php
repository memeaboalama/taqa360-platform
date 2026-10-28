<div class="row">
    <div class="col-xl-1"></div>
    <div class="col-xl-7">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title"> @lang('marketplace::home.Order No') -
                            <span class="text-primary">#{{ $order->id }}</span>
                            @php
                                $statusEnum = is_string($order->status) ? (\App\Application\Marketplace\Enums\MerchantOrderStatuses::tryFrom($order->status)) : $order->status;
                                $statusVal = $statusEnum?->value ?? (is_string($order->status) ? $order->status : '');
                            @endphp
                            <span class="mx-2 badge {{ $statusEnum?->color() }}">{{ $statusEnum?->trans() }}</span>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            @if(in_array($statusVal, ['pending', 'started-work', 'confirmed']))
                                <button wire:click="$emit('cancelOrder', {{ $order->id }})" class="btn btn-danger btn-sm fw-bold">
                                    <i class="ri-close-circle-line me-1"></i> إلغاء الطلب
                                </button>
                            @endif

                            @if($statusVal !== 'ready' && $statusVal !== 'on-way' && $statusVal !== 'completed' && $statusVal !== 'previous' && $statusVal !== 'cancelled')
                                <button wire:click="markAsReady" class="btn btn-success btn-sm fw-bold"
                                        wire:loading.attr="disabled" wire:target="markAsReady">
                                    <span wire:loading.class="d-none" wire:target="markAsReady">
                                        <i class="ri-check-line me-1"></i> تغيير الحالة إلى: جاهز للتوصيل
                                    </span>
                                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="markAsReady">
                                        @lang('marketplace::home.Wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            @elseif($statusVal === 'ready')
                                <span class="badge bg-success p-2 fs-13"><i class="ri-checkbox-circle-line me-1"></i> جاهز للتوصيل (بانتظار استلام الموصل)</span>
                            @elseif($statusVal === 'cancelled')
                                <span class="badge bg-danger p-2 fs-13"><i class="ri-close-circle-line me-1"></i> تم إلغاء الطلب</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table text-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('marketplace::home.item')</th>
                                    <th scope="col">@lang('marketplace::home.Price')</th>
                                    <th scope="col">@lang('marketplace::home.qty')</th>
                                    <th scope="col">@lang('marketplace::home.Total Price')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderItems as $item)

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <div class="mb-1 fs-14 fw-semibold"><a
                                                            href="javascript:void(0);">{{ $item?->item_name }}</a>
                                                        <br>
                                                        @foreach($item->addons as $addon)
                                                            <span class="text-info">
                                                                {{ $addon['name'] }} : {{ $addon['price'] }} SAR
                                                            </span>
                                                            <br>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ number_format($item?->item?->default_price, 2) }} @lang('marketplace::home.SAR')</span>
                                        </td>

                                        <td><span
                                                class="fs-15 fw-semibold">{{ $item?->qty }}</span>
                                        </td>

                                        <td><a href="javascript:void(0);"
                                               class="text-primary">{{ number_format($item?->subtotal, 2) }}
                                                @lang('marketplace::home.SAR')</a></td>
                                    </tr>

                                @endforeach


<tr>
    <td colspan="2"></td>
    <td colspan="2">
        <div class="fw-semibold">@lang('marketplace::home.Total Items') :</div>
    </td>
    <td>
        {{ $order->orderItems->sum('qty') }}
    </td>
</tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">
                                        <div class="fw-semibold">@lang('marketplace::home.Sub Total') :</div>
                                    </td>
                                    <td>
                                        {{ number_format($order->subtotal, 2) }}
                                    </td>
                                </tr>

<tr>
    <td colspan="2"></td>
    <td colspan="2">
        <div class="fw-semibold">@lang('marketplace::home.VAT') :</div>
    </td>
    <td>
        {{ number_format($order->vat, 2) }}
    </td>
</tr>

<tr>
    <td colspan="2"></td>
    <td colspan="2">
        <div class="fw-semibold">الخصم :</div>
    </td>
    <td>
        {{ number_format($order->discount, 2) }} SAR
    </td>
</tr>


<tr>
    <td colspan="2"></td>
    <td colspan="2">
        <div class="fw-semibold">@lang('marketplace::home.Total Price') :</div>
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
        </div>
    </div>

    <div class="col-xl-3">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title"> @lang('marketplace::home.User Details')</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                            <div class="me-2"><span class="avatar avatar-lg avatar-rounded bg-primary">
                                {{ \Str::substr(ucfirst($orderUser->name),0,1) }}
                                </span></div>
                            <div class="flex-fill"><p class="mb-0">{{ $orderUser->name }}</p>
                            </div>
                        </div>
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="d-flex align-items-center justify-content-between mb-3"><span
                                    class="fs-14 fw-bold">@lang('marketplace::home.User address') :</span>

                            </div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('marketplace::home.address') : </span>{{ $address?->address }}
                            </p>
<p class="mb-2 text-muted"><span
        class="fw-semibold text-default">@lang('marketplace::home.Country') : </span>{{ $address?->country?->title }}
</p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('marketplace::home.City') : </span>{{ $address?->city?->title }}
                            </p>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">@lang('marketplace::home.District') : </span>{{ $address?->distract?->title }}
                            </p>
                        </div>
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">@lang('marketplace::home.Order Summary')</span></div>
                            <p class="mb-2 text-danger"><span
                                    class="fw-semibold text-default">@lang('marketplace::home.Order Note')</span> {{ $order->note }}
                            </p>
<p class="mb-2 text-muted"><span
        class="fw-semibold text-default">@lang('marketplace::home.Ordered Date')</span> {{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}
                            </p>
                            <p class="mb-2 text-muted"><span class="fw-semibold text-default">@lang('marketplace::home.Ordered Time')</span>
                                من {{ \Carbon\Carbon::parse($order->from)->format('H:i') }}
                                إلى {{ \Carbon\Carbon::parse($order->to)->format('H:i') }} </p>

                        </div>

                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="d-flex align-items-center flex-wrap">
                                @if($order->driver)
                                    <img src="{{ asset('assets/vendor') }}/images/green.png" width="24" class="me-2" alt="Green Circle">
                                    <span class="fs-14 fw-bold me-1">تم تعيين السائق:</span>
                                    <span class="fw-semibold text-success fs-14">({{ $order->driver->name }})</span>
                                @else
                                    <img src="{{ asset('assets/vendor') }}/images/red.png" width="24" class="me-2" alt="Red Circle">
                                    <span class="fs-14 fw-bold me-1">تعيين السائق:</span>
                                    <span class="text-danger fs-12">(لم يتم الإسناد بعد)</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <p class="mb-0 text-muted">
                            <span class="fw-semibold text-default me-2">@lang('marketplace::home.Payment Method')</span>
                            @if($order?->payment?->source_company)
                            <img width="50px"
                                src="{{asset('images/paymentMethods/'.$order?->payment?->source_company.'.png')}}"
                                alt="{{ $order?->payment?->source_company}}"
                                />
                            <span class="mx-2">
                                {{ $order?->payment?->source_company}}
                            </span>
                            @else
                            <img width="50px"
                                src="{{asset('images/paymentMethods/'.$order?->payment_method.'.png')}}"
                                alt="{{ $order?->payment_method }}"
                                />
                            <span class="mx-2">
                                {{ $order?->payment_method }}
                            </span>

                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<livewire:marketplace::order.modal.cancel />
