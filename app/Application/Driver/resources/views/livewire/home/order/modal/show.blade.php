<div>
    <div class="modal fade" id="showOrderDetailsModal" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="showOrderDetailsModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="showOrderDetailsModalLabel">
                        {{ __('driver::home.Order Details') }} -
                        <span class="text-primary">#{{ $order['code'] ?? '' }}</span>
                        @if(!empty($order['status_value']))
                            <span class="ms-2 badge {{ $order['status_color'] }}">{{ $order['status_trans'] }}</span>
                        @endif
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                @if(!empty($order))
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.Product Details') }}</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table text-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('driver::home.item') }}</th>
                                                        <th class="text-center">{{ __('driver::home.qty') }}</th>
                                                        <th class="text-center">{{ __('driver::home.Price') }}</th>
                                                        <th class="text-end">{{ __('driver::home.Total Price') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($items as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="fs-14 fw-semibold">{{ $item['item_name'] }}</div>
                                                                @if(!empty($item['addons']))
                                                                    <span class="text-info fs-12">{{ $item['addons'] }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $item['qty'] }}</td>
                                                            <td class="text-center">{{ number_format($item['price'], 2) }} {{ __('driver::home.SAR') }}</td>
                                                            <td class="text-end">{{ number_format($item['total'], 2) }} {{ __('driver::home.SAR') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-3">-</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="p-3 border-top border-block-end-dashed">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-semibold">{{ __('driver::home.Sub Total') }}:</span>
                                                <span>{{ number_format($order['subtotal'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-semibold">{{ __('driver::home.Delivery Fee') }}:</span>
                                                <span>{{ number_format($order['delivery_fee'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-semibold">{{ __('driver::home.VAT') }}:</span>
                                                <span>{{ number_format($order['vat'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                            @if(!empty($order['discount']))
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-semibold">{{ __('driver::home.Discount') }}:</span>
                                                    <span>-{{ number_format($order['discount'], 2) }} {{ __('driver::home.SAR') }}</span>
                                                </div>
                                            @endif
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-semibold">{{ __('driver::home.Total Price') }}:</span>
                                                <span class="fw-semibold text-primary">{{ number_format($order['total_after_vat'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.User Details') }}</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if(!empty($order['user_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.userName') }} : </span>{{ $order['user_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['user_phone']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Phone Number') }} : </span>{{ $order['user_phone'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['client_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Client Name') }} : </span>{{ $order['client_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['client_phone']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Client Phone') }} : </span>{{ $order['client_phone'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['address']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.address') }} : </span>
                                                <a href="https://maps.google.com/?q={{ $order['address'] }}" target="_blank" style="color:#0b5ee7">{{ $order['address'] }}</a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.Delivery Details') }}</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if(!empty($order['merchant_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Company Name') }} : </span>{{ $order['merchant_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['branch_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Branch Name') }} : </span>{{ $order['branch_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['delivery_distance']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Distance') }} : </span>{{ $order['delivery_distance'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['order_type']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Type') }} : </span>{{ $order['order_type'] }}
                                            </p>
                                        @endif
                                        <p class="mb-2">
                                            <span class="fw-semibold">{{ __('driver::home.Payment Method') }} : </span>{{ $order['payment_label'] ?? $order['payment_method'] ?? '-' }}
                                        </p>
                                        @if(!empty($order['note']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.Order Note') }} : </span>{{ $order['note'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.Order Summary') }}</h6>
                                    </div>
                                    <div class="card-body p-3 d-flex flex-wrap gap-3">
                                        <p class="mb-0">
                                            <span class="fw-semibold">{{ __('driver::home.Ordered Date') }} : </span>{{ $order['created_at'] ?? '-' }}
                                        </p>
                                        @if(!empty($order['from']) && !empty($order['to']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.From') }} : </span>{{ $order['from'] }}
                                                <span class="fw-semibold ms-2">{{ __('driver::home.To') }} : </span>{{ $order['to'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['schedulable']) && !empty($order['date']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.Ordered Date') }} (جدولة) : </span>{{ $order['date'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="modal-body">
                        <p class="text-center text-muted mb-0">-</p>
                    </div>
                @endif

                <div class="modal-footer">
                    @if(in_array($order['status_value'] ?? null, ['pending', 'upcoming']))
                        <form action="{{ route('driver:orders.accept', $order['id']) }}" method="POST" class="d-inline" id="acceptOrderForm">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="ri-check-line me-1"></i> قبول الطلب
                            </button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-color: var(--input-border); color: #6c757d; background-color: transparent;">
                        {{ __('driver::home.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>