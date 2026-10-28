@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    /* Full Width Container matching account headers */
    .orders-container {
        width: 100%;
        padding-bottom: 40px;
    }

    /* Enhanced Precise Table Styling */
    .precise-table-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .table-responsive {
        margin-bottom: 0;
    }

    .precise-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .precise-table th {
        background: #f8f9fa;
        padding: 20px 25px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #5d6778;
        border-bottom: 1px solid #eef2f5;
        white-space: nowrap;
    }

    .precise-table td {
        padding: 22px 25px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f4f8;
        font-size: 14px;
        color: #2d3748;
        background: #fff;
        transition: background 0.2s;
    }

    .precise-table tr:last-child td { border-bottom: none; }
    .precise-table tr:hover td { background-color: #fcfcfd; }

    /* Status Badge Modern */
    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-badge::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }

    /* Strict Color Map */
    .bg-pending, .bg-warning, .badge.bg-pending, .badge.bg-warning { background-color: #fff8e1 !important; color: #f57f17 !important; }
    .bg-confirmed, .bg-success, .badge.bg-confirmed, .badge.bg-success, .bg-completed { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-cancelled, .bg-danger, .badge.bg-cancelled, .badge.bg-danger { background-color: #ffebee !important; color: #c62828 !important; }
    .bg-on-way, .bg-primary, .badge.bg-on-way, .badge.bg-primary, .bg-ready { background-color: #e3f2fd !important; color: #1565c0 !important; }
    .bg-secondary, .badge.bg-secondary, .bg-started-work { background-color: #f5f5f5 !important; color: #616161 !important; }
    .bg-draft, .bg-purple-transparent { background-color: #f3e5f5 !important; color: #7b1fa2 !important; }

    .btn-view {
        padding: 8px 18px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        background: #fff;
        text-decoration: none;
        display: inline-block;
    }
    .btn-view:hover {
        border-color: #306FAA;
        color: #fff;
        background: #306FAA;
        box-shadow: 0 4px 12px rgba(245, 179, 36, 0.25);
    }

    .order-id { font-weight: 800; color: #1a202c; font-family: monospace; font-size: 15px; }
    .total-price { font-weight: 800; color: #2d3748; font-size: 15px; }
    .date-text { color: #718096; font-weight: 500; }

    /* Mobile Card View override */
    @media (max-width: 768px) {
        .precise-table thead { display: none; }
        .precise-table, .precise-table tbody, .precise-table tr, .precise-table td { display: block; width: 100%; }
        .precise-table tr {
            margin-bottom: 20px;
            border: 1px solid #eef2f5;
            border-radius: 12px;
            padding: 0;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow: hidden;
        }
        .precise-table td {
            padding: 12px 20px;
            border-bottom: 1px solid #f7fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: start;
        }
        .precise-table td:last-child {
            background: #f8f9fa;
            padding: 15px 20px;
            justify-content: flex-end;
        }
        .precise-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #718096;
            text-align: start;
            margin-inline-end: auto;
            padding-inline-end: 15px;
        }
    }
</style>

<div class="account-content-area w-100">
    <div class="orders-container">

        <div class="content-header mb-4 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.my_orders')</h2>
            <div class="text-muted small">
                @lang('ecommerce::ecommerce.total_orders'): <strong>{{ $orders->total() }}</strong>
            </div>
        </div>

        @if($orders && $orders->count() > 0)
            <div class="precise-table-card">
                <div class="table-responsive">
                    <table class="precise-table">
                        <thead>
                            <tr>
                                <th width="15%">@lang('ecommerce::ecommerce.order_number')</th>
                                <th width="20%">@lang('ecommerce::ecommerce.date')</th>
                                <th width="10%">@lang('ecommerce::ecommerce.items')</th>
                                <th width="20%">@lang('ecommerce::ecommerce.status')</th>
                                <th width="20%">@lang('ecommerce::ecommerce.amount')</th>
                                <th width="15%" class="text-end">@lang('ecommerce::ecommerce.details')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td data-label="@lang('ecommerce::ecommerce.order_number')">
                                    <span class="order-id num">#{{ $order->code ?? $order->order_number ?? $order->id }}</span>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.date')">
                                    <span class="date-text">{{ $order->created_at->format('d M Y') }}</span>
                                    <small class="d-block text-muted" style="font-size: 11px; margin-top: 3px;">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.items')">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ti-package text-muted"></i>
                                        <span class="fw-bold">{{ count($order->orderItems) }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.status')">
                                    <span class="status-badge {{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.amount')">
                                    <span class="total-price"><small class="fw-normal text-muted">{{ \App\Domain\AppBase\Models\Setting\Currency::format($order->total_after_vat ?: $order->total) }}</small></span>
                                    @if($order->discount > 0)
                                        <div class="small text-danger fw-bold mt-1" style="font-size: 11px;">
                                            @lang('ecommerce::ecommerce.discount'): -{{ number_format($order->discount, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td data-label="" class="text-end">
                                    <a href="{{ route('account.order', $order->id) }}" class="btn-view" up-target=".dashboard-content">
                                        @lang('ecommerce::ecommerce.view_details') <i class="ti-arrow-left arrow-start ms-1" style="font-size: 10px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($orders->hasPages())
                <div class="pagination_sec mt-4">
                    <div class="content_detail__pagination cdp">
                        {{ $orders->links('vendor.pagination.custom') }}
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5 precise-table-card">
                <i class="ti-shopping-cart text-muted mb-3" style="font-size: 50px;"></i>
                <h4 class="text-muted">@lang('ecommerce::ecommerce.no_orders_yet')</h4>
                <a href="{{ route('home') }}" class="btn btn-solid mt-3">
                    @lang('ecommerce::ecommerce.start_shopping')
                </a>
            </div>
        @endif
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
