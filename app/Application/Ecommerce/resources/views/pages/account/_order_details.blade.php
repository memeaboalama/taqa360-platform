@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    /* Reuse Checkout Variables */
    :root {
        --primary: #306FAA;
        --content-bg: #fff;
        --radius: 16px;
        --shadow: 0 4px 20px rgba(0,0,0,0.04);
        --border: 1px solid #eef2f5;
    }

    .checkout-container {
        max-width: 1250px;
        margin: 0 auto;
        padding-bottom: 40px;
        font-family: inherit;
    }

    .checkout-layout {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
    }

    .checkout-main { flex: 1; min-width: 0; }
    .checkout-sidebar { width: 380px; flex-shrink: 0; }

    .section-content-card {
        background: var(--content-bg);
        border: var(--border);
        border-radius: var(--radius);
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 25px;
    }

    .section-header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: #222;
    }

    /* Compact Product Row Design */
    .product-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        gap: 15px;
    }
    .product-row:last-child { border-bottom: none; }

    .product-img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        /* No border here as requested */
        flex-shrink: 0;
    }
    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info { flex: 1; }
    .product-name {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        line-height: 1.4;
        margin-bottom: 2px;
    }

    .product-qty {
        font-size: 13px;
        color: #777;
        margin-right: 20px;
        white-space: nowrap;
    }

    .product-price {
        font-weight: 700;
        font-size: 14px;
        color: #222;
        min-width: 70px;
        text-align: end;
    }

    /* Address Box */
    .address-box {
        background: #f8f9fa;
        padding: 18px;
        border-radius: 12px;
        border: 1px solid #eee;
        font-size: 14px;
    }
    .addr-title { font-weight: 700; display: block; color: #333; margin-bottom: 6px; }
    .addr-phone { font-size: 13px; color: #666; display: block; margin-bottom: 8px; font-family: sans-serif; }
    .address-details { color: #555; line-height: 1.6; }

    /* Info Row (Used for Status & Summary) */
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 14px;
        color: #555;
    }
    .info-row:last-child { margin-bottom: 0; }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: #555;
    }
    .summary-row.total {
        border-top: 1px dashed #ddd;
        padding-top: 15px;
        margin-top: 15px;
        font-weight: 800;
        font-size: 17px;
        color: #000;
    }
    .summary-row.discount { color: #d32f2f; }

    /* Status Badge & Colors */
    .status-badge-lg {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    /* Strict Color Map */
    .bg-pending, .bg-warning, .badge.bg-pending, .badge.bg-warning { background-color: #fff8e1 !important; color: #f57f17 !important; }
    .bg-confirmed, .bg-success, .badge.bg-confirmed, .badge.bg-success, .bg-completed { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-cancelled, .bg-danger, .badge.bg-cancelled, .badge.bg-danger { background-color: #ffebee !important; color: #c62828 !important; }
    .bg-on-way, .bg-primary, .badge.bg-on-way, .badge.bg-primary, .bg-ready { background-color: #e3f2fd !important; color: #1565c0 !important; }
    .bg-secondary, .badge.bg-secondary, .bg-started-work { background-color: #f5f5f5 !important; color: #616161 !important; }
    .bg-draft, .bg-purple-transparent { background-color: #f3e5f5 !important; color: #7b1fa2 !important; }

    /* Cancellation Form Styles */
    .cancel-btn-wrapper { margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee; }

    .cancel-form-container {
        display: none; /* Hidden by default */
        background: #fffafa;
        border: 1px solid #ffebee;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
    .cancel-textarea {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        font-size: 13px;
        resize: vertical;
        min-height: 80px;
        margin-bottom: 10px;
    }
    .btn-toggle-cancel {
        display: block;
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        background: #fff;
        border: 1px solid #ffcdd2;
        color: #d32f2f;
        transition: all 0.2s;
        text-align: center;
        cursor: pointer;
    }
    .btn-toggle-cancel:hover { background: #ffebee; }

    .btn-confirm-cancel {
        width: 100%;
        background: #d32f2f;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .btn-cancel-disabled {
        display: block;
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        background: #f5f5f5;
        border: 1px solid #eee;
        color: #999;
        cursor: not-allowed;
        opacity: 0.7;
        text-align: center;
    }

    @media (max-width: 992px) {
        .checkout-layout { flex-direction: column; }
        .checkout-sidebar { width: 100%; order: -1; }
    }
</style>

<div class="account-content-area">
    <div class="checkout-container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                 <a href="{{ route('account.orders') }}" class="text-muted small text-decoration-none" up-target=".dashboard-content">
                     <i class="ti-arrow-left arrow-start"></i> @lang('ecommerce::ecommerce.back_to_orders')
                 </a>
                 <h2 class="mt-2 fw-bold">@lang('ecommerce::ecommerce.order_details') <span class="text-primary num">{{ $order->code ?? $order->order_number ?? $order->id }}</span></h2>
                 <span class="text-muted small" dir="ltr">{{ $order->created_at->format('d/m/Y - H:i') }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mb-4 rounded-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4 rounded-3">{{ session('error') }}</div>
        @endif

        <div class="checkout-layout">

            <div class="checkout-main">

                <div class="section-content-card">
                    <div class="section-header">
                        <h4 class="section-title">@lang('ecommerce::ecommerce.items') <span class="text-muted">({{ count($order->orderItems) }})</span></h4>
                    </div>

                    <div class="product-rows">
                        @foreach($order->orderItems as $item)
                        <div class="product-row">
                            <div class="product-img">
                                <img src="{{ $item->item?->image_path ?? asset('assets/images/pro/b1.jpg') }}"
                                     alt="Product" onerror="this.src='{{ asset('assets/images/pro/b1.jpg') }}'">
                            </div>
                            <div class="product-info">
                                <span class="product-name">{{ $item->item_name ?? $item->item->title ?? __('ecommerce::ecommerce.product_label') }}</span>
                            </div>
                            <div class="product-qty">
                                x {{ $item->qty }}
                            </div>
                            <div class="product-price">
                                {{ number_format($item->subtotal, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="section-content-card">
                    <div class="section-header">
                        <h4 class="section-title">@lang('ecommerce::ecommerce.shipping_details')</h4>
                    </div>

                    @php
                        $addrRaw = $order->addressInOrder;
                        $addr = is_string($addrRaw) ? json_decode($addrRaw, true) : $addrRaw;
                    @endphp

                    @if($addr && is_array($addr))
                        <div class="address-box">
                            <span class="addr-title">{{ $order->client_name ?? $order->user->name ?? 'User' }}</span>
                            <span class="addr-phone">
                                <i class="ti-mobile"></i> {{ $order->client_phone ?? $order->user->phone ?? '' }}
                            </span>
                            <div class="address-details mt-2">
                                <i class="ti-location-pin text-muted"></i>
                                {{ $addr['city_name'] ?? '' }}
                                @if(isset($addr['district_name'])) - {{ $addr['district_name'] }} @endif
                                , {{ $addr['address'] ?? '' }}
                            </div>
                        </div>
                    @else
                        <p class="text-muted">@lang('ecommerce::ecommerce.no_address_found')</p>
                    @endif
                </div>

            </div>

            <div class="checkout-sidebar">

                <div class="section-content-card">
                    <div class="section-header">
                        <h4 class="section-title">@lang('ecommerce::ecommerce.order_status')</h4>
                    </div>

                    <div class="info-row">
                        <span class="status-badge-lg {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="text-muted">@lang('ecommerce::ecommerce.payment_status')</span>
                        <span class="status-badge-lg {{ $order->payment_color }}">
                            {{ $order->payment_label }}
                        </span>
                    </div>

                    <div class="cancel-btn-wrapper">
                        @php
                            $statusVal = $order->status instanceof \UnitEnum ? $order->status->value : $order->status;

                            $forbiddenStatuses = [
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::READY->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::CANCELLED->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::EXPIRED->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::PREVIOUS->value,
                                \App\Application\Marketplace\Enums\MerchantOrderStatuses::CURRENT->value
                            ];

                            $isCancellable = !in_array($statusVal, $forbiddenStatuses);
                        @endphp

                        @if($isCancellable)
                            <div onclick="document.getElementById('cancel-form-box').style.display = document.getElementById('cancel-form-box').style.display === 'block' ? 'none' : 'block'"
                                 class="btn-toggle-cancel">
                                <i class="ti-close"></i> @lang('ecommerce::ecommerce.cancel_order')
                            </div>

                            <div id="cancel-form-box" class="cancel-form-container">
                                <form action="{{ route('account.order.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    <label class="small text-danger fw-bold mb-2 d-block">@lang('ecommerce::ecommerce.confirm_cancel_order')</label>
                                    <textarea name="cancellation_reason" class="cancel-textarea" placeholder="@lang('ecommerce::ecommerce.cancel_reason_placeholder')" required></textarea>
                                    <button type="submit" class="btn-confirm-cancel">
                                        @lang('ecommerce::ecommerce.submit_cancel')
                                    </button>
                                </form>
                            </div>
                        @elseif($statusVal == \App\Application\Marketplace\Enums\MerchantOrderStatuses::CANCELLED->value)
                             <button class="btn-cancel-disabled" disabled>
                                @lang('ecommerce::ecommerce.order_cancelled')
                            </button>
                             @php
                                 $cancelHistory = $order->orders_history->sortByDesc('created_at')->first();
                                 $reason = $cancelHistory ? $cancelHistory->reason : null;
                             @endphp
                             @if($reason)
                                 <div class="mt-3 p-2 bg-danger bg-opacity-10 border border-danger rounded small text-danger">
                                     <strong>@lang('ecommerce::ecommerce.cancellation_reason'):</strong><br>
                                     {{ $reason }}
                                 </div>
                             @endif
                        @else
                            <button class="btn-cancel-disabled" disabled title="@lang('ecommerce::ecommerce.job_is_in_progress')">
                                <i class="ti-lock"></i> @lang('ecommerce::ecommerce.cancel_order')
                            </button>
                            <div class="text-center mt-2">
                                <small class="text-muted">@lang('ecommerce::ecommerce.cannot_cancel_order_in_progress')</small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="section-content-card">
                    <div class="section-header">
                        <h4 class="section-title">@lang('ecommerce::ecommerce.order_summary')</h4>
                    </div>

                    <div class="summary-container">
                        <div class="summary-row">
                            <span>@lang('ecommerce::ecommerce.subtotal')</span>
                            <span>{{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if($order->delivery_fee > 0)
                        <div class="summary-row">
                            <span>@lang('ecommerce::ecommerce.delivery_fee')</span>
                            <span>{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        @endif

                        @if($order->vat > 0)
                        <div class="summary-row">
                            <span>@lang('ecommerce::ecommerce.vat')</span>
                            <span>{{ number_format($order->vat, 2) }}</span>
                        </div>
                        @endif

                        @if($order->discount > 0)
                        <div class="summary-row discount">
                            <span>@lang('ecommerce::ecommerce.discount')
                                @php
                                    $code_val = $order->getAttribute('promocode');
                                    $code_str = is_array($code_val) ? ($code_val['code'] ?? '') : ($code_val ?: '');
                                    $code_str = is_string($code_str) ? $code_str : '';
                                @endphp
                                @if($code_str) <small>({{ $code_str }})</small> @endif
                            </span>
                            <span>-{{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif

                        <div class="summary-row total">
                            <span>@lang('ecommerce::ecommerce.grand_total')</span>
                            <span>{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->total_after_vat ?: $order->total) }}</span>
                        </div>
                    </div>

                    <div class="payment-info pt-3 mt-3 border-top d-flex align-items-center gap-2">
                        <i class="ti-credit-card text-muted"></i>
                        <span class="small text-muted">@lang('ecommerce::ecommerce.payment_method'):</span>
                        <span class="fw-bold text-dark">{{ $order->payment_method }}</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
