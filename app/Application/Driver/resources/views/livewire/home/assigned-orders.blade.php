<div wire:poll.5s.keep-alive>
    <div class="card custom-card">
        <div class="card-header bg-white justify-content-between">
            <div class="card-title">{{ __('driver::home.orders') }}</div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">{{ __('driver::home.Records Per Page') }}</span>
                <select class="form-select form-select-sm" style="width:auto;" wire:model="perPage">
                    @foreach([10, 25, 50, 100] as $count)
                        <option value="{{ $count }}">{{ $count }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            @include('driver::livewire.home.order.table.filter')

            @if($recentOrders->count())
                <div class="table-responsive">
                    <table class="table text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('driver::home.Order No') }}</th>
                                <th>{{ __('driver::home.userName') }}</th>
                                <th>{{ __('driver::home.Total Price') }}</th>
                                <th>{{ __('driver::home.status') }}</th>
                                <th>{{ __('driver::home.date') }}</th>
                                <th>{{ __('driver::home.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr wire:key="driver-order-{{ $order->id }}">
                                    <td><span class="fw-semibold text-primary">#{{ $order->code }}</span></td>
                                    <td>{{ $order->user?->name }}</td>
                                    <td>{{ number_format($order->total_after_vat, 2) }} SAR</td>
                                    <td>
                                        <span class="badge {{ $order->status_color }}">
                                            {{ $order->status ? __("driver::home.order_status_" . (is_object($order->status) ? $order->status->value : $order->status)) : '-' }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @php
                                            $statusVal = is_object($order->status) ? $order->status->value : $order->status;
                                            $acceptedByDriver = $order->isAcceptedByDriver((int) $driver->id);
                                        @endphp
                                        <a href="javascript:void(0);" class="btn btn-sm btn-info-transparent rounded-pill"
                                           style="width:1.75rem;height:1.75rem;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;"
                                           title="{{ __('driver::home.View') }}"
                                           onclick="Livewire.emit('showOrderDetails', {{ $order->id }})"><i class="ri-eye-line" style="margin:0"></i></a>
                                        @if(in_array($statusVal, [
                                            \App\Application\Marketplace\Enums\MerchantOrderStatuses::UPCOMING->value,
                                            \App\Application\Marketplace\Enums\MerchantOrderStatuses::READY->value,
                                        ], true) && !$acceptedByDriver)
                                            <button type="button" class="btn btn-sm btn-success"
                                                    wire:click="acceptOrder({{ $order->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="acceptOrder({{ $order->id }})">
                                                <i class="ri-check-line me-1"></i> {{ __('driver::home.accept_order') }}
                                            </button>
                                        @elseif($statusVal === \App\Application\Marketplace\Enums\MerchantOrderStatuses::CURRENT->value && $acceptedByDriver)
                                            {{-- السائق قبل الطلب والتاجر يجهّزه --}}
                                            <span class="badge bg-warning text-dark"><i class="ri-time-line me-1"></i> {{ __('driver::home.preparing_at_merchant') }}</span>
                                        @elseif($statusVal === \App\Application\Marketplace\Enums\MerchantOrderStatuses::READY->value && $acceptedByDriver)
                                            {{-- الطلب جاهز والسائق قبله → ابدأ التوصيل --}}
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    wire:click="startDelivery({{ $order->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="startDelivery({{ $order->id }})">
                                                <i class="ri-truck-line me-1"></i> {{ __('driver::home.start_delivery') }}
                                            </button>
                                        @elseif($statusVal === 'on-way')
                                            @if(\App\Domain\MarketPlace\Models\MerchantOrder::isCashOnDeliveryMethod($order->payment_method))
                                                <button type="button" class="btn btn-sm btn-success"
                                                        onclick="Livewire.emit('showOrderDetails', {{ $order->id }})">
                                                    <i class="ri-checkbox-circle-line me-1"></i> {{ __('driver::home.confirm_delivery_and_collection') }}
                                                </button>
                                                @if(\App\Domain\MarketPlace\Models\MerchantOrder::resolvePaymentStatus($order->payment, 'unpaid', $order->payment_method) !== 'paid')
                                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                                            onclick="Livewire.emit('showCollectionFailure', {{ $order->id }})">
                                                        {{ __('driver::home.collection_failed') }}
                                                    </button>
                                                @endif
                                            @elseif(\App\Domain\MarketPlace\Models\MerchantOrder::resolvePaymentStatus($order->payment, 'unpaid', $order->payment_method) === 'paid')
                                                <button type="button" class="btn btn-sm btn-success"
                                                        onclick="Livewire.emit('showOrderDetails', {{ $order->id }})">
                                                    <i class="ri-checkbox-circle-line me-1"></i> {{ __('driver::home.confirm_delivery') }}
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-success"
                                                        wire:click="completeOrder({{ $order->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="completeOrder({{ $order->id }})">
                                                    <i class="ri-checkbox-circle-line me-1"></i> {{ __('driver::home.order_status_completed') }}
                                                </button>
                                            @endif
                                        @elseif($statusVal === 'completed')
                                            <button type="button" class="btn btn-sm btn-success" disabled>
                                                <i class="ri-checkbox-circle-line me-1"></i> {{ __('driver::home.order_status_completed') }}
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $recentOrders->links('pagination::bootstrap-5') }}
            @else
                <p class="text-muted mb-0">{{ __('driver::home.no_orders') }}</p>
            @endif
        </div>
    </div>
</div>
