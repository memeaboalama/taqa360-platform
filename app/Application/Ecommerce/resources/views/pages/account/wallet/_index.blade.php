@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    /* PRECISE TABLE STYLES */
    .precise-table-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table-responsive {
        margin-bottom: 0;
        width: 100%;
        overflow-x: auto;
    }

    .precise-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .precise-table th {
        background: #f8f9fa;
        padding: 18px 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #5d6778;
        border-bottom: 1px solid #eef2f5;
        white-space: nowrap;
        text-align: inherit;
    }

    .precise-table td {
        padding: 20px 25px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f4f8;
        font-size: 14px;
        color: #2d3748;
        background: #fff;
        transition: background 0.2s;
    }

    .precise-table tr:last-child td { border-bottom: none; }
    .precise-table tr:hover td { background-color: #fcfcfd; }

    /* STATUS BADGES */
    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f5f5f5;
        color: #666;
    }
    .status-badge::before {
        content: ''; display: block; width: 6px; height: 6px; border-radius: 50%; background-color: currentColor;
    }

    .bg-confirmed { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-pending { background-color: #fff8e1 !important; color: #f57f17 !important; }

    /* TYPE ICONS IN TABLE */
    .table-icon-box {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; margin-inline-end: 12px; flex-shrink: 0;
    }
    .icon-deposit { background: #e8f5e9; color: #2e7d32; }
    .icon-withdraw { background: #ffebee; color: #c62828; }

    /* WALLET SUMMARY CARD */
    .wallet-summary-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 30px;
    }

    .summary-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eee;
        color: #1a202c;
    }

    .balance-display {
        font-size: 28px;
        font-weight: 800;
        color: #306FAA;
        margin-bottom: 25px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 10px;
    }

    .btn-deposit {
        background: #306FAA;
        color: #fff;
        border: none;
    }
    .btn-deposit:hover {
        background: #245C8E;
        transform: translateY(-2px);
        color: #fff;
    }

    .btn-withdraw {
        background: #fff;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }
    .btn-withdraw:hover {
        background: #f8f9fa;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    /* MOBILE RESPONSIVE TABLE */
    @media (max-width: 768px) {
        .precise-table thead { display: none; }
        .precise-table, .precise-table tbody, .precise-table tr, .precise-table td { display: block; width: 100%; }

        .precise-table tr {
            margin-bottom: 15px;
            border: 1px solid #eef2f5;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        .precise-table td {
            padding: 12px 20px;
            border-bottom: 1px solid #f7fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: start;
        }

        .precise-table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #718096;
            text-align: start;
            margin-inline-end: auto;
            padding-inline-end: 15px;
            font-size: 11px;
            text-transform: uppercase;
        }
    }
</style>

<div class="account-content-area w-100">
    @if(empty($hide_header))
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.my_wallet')</h2>
    </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mb-4 rounded-3 d-flex align-items-center gap-2">
            <i class="ti-check-box"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4 rounded-3 d-flex align-items-center gap-2">
            <i class="ti-alert"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Main Content: Transactions -->
        <div class="col-lg-8 order-2 order-lg-1">
            <div class="precise-table-card">
                <div class="table-responsive">
                    <table class="precise-table">
                        <thead>
                            <tr>
                                <th width="25%">@lang('ecommerce::ecommerce.type')</th>
                                <th width="15%">@lang('ecommerce::ecommerce.id')</th>
                                <th width="20%">@lang('ecommerce::ecommerce.status')</th>
                                <th width="25%">@lang('ecommerce::ecommerce.date')</th>
                                <th width="15%" class="text-end">@lang('ecommerce::ecommerce.amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td data-label="@lang('ecommerce::ecommerce.type')">
                                        <div class="d-flex align-items-center">
                                            @if(in_array($transaction->type, ['deposit', 'MainCoupon', 'MerchantCoupon']))
                                                <div class="table-icon-box icon-deposit"><i class="ti-arrow-down"></i></div>
                                                <div>
                                                    <span class="d-block fw-bold text-dark">@lang('ecommerce::ecommerce.deposit')</span>
                                                    <small class="text-muted">{{ $transaction->type == 'MainCoupon' ? 'Coupon' : 'Online' }}</small>
                                                </div>
                                            @else
                                                <div class="table-icon-box icon-withdraw"><i class="ti-arrow-up"></i></div>
                                                <div>
                                                    <span class="d-block fw-bold text-dark">@lang('ecommerce::ecommerce.withdraw')</span>
                                                    <small class="text-muted">Transfer</small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td data-label="@lang('ecommerce::ecommerce.id')">
                                        <span class="font-monospace fw-bold text-muted">#{{ $transaction->id }}</span>
                                    </td>

                                    <td data-label="@lang('ecommerce::ecommerce.status')">
                                        <span class="status-badge {{ $transaction->confirmed ? 'bg-confirmed' : 'bg-pending' }}">
                                            {{ $transaction->confirmed ? __('ecommerce::ecommerce.confirmed') : __('ecommerce::ecommerce.pending') }}
                                        </span>
                                    </td>

                                    <td data-label="@lang('ecommerce::ecommerce.date')">
                                        <span class="d-block text-dark fw-medium">{{ $transaction->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                    </td>

                                    <td data-label="@lang('ecommerce::ecommerce.amount')" class="text-end">
                                        <span class="fw-bold {{ $transaction->amountfloat > 0 ? 'text-success' : 'text-danger' }} fs-6">
                                            {{ $transaction->amountfloat > 0 ? '+' : '' }}{{ number_format($transaction->amountfloat, 2) }}
                                            <small class="text-muted fw-normal">SAR</small>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="ti-wallet text-muted mb-3 d-block" style="font-size: 32px; opacity: 0.3;"></i>
                                        <span class="text-muted fw-medium">@lang('ecommerce::ecommerce.no_transactions')</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($transactions->hasPages())
                <div class="mt-4">
                    <div class="content_detail__pagination cdp">
                        {{ $transactions->appends(request()->query())->links('vendor.pagination.custom') }}
                    </div>
                </div>
                <script>
                    (function () {
                        const paginationEl = document.currentScript && document.currentScript.previousElementSibling;
                        if (!paginationEl) return;
                        paginationEl.querySelectorAll('.content_detail__pagination a').forEach(function (a) {
                            a.setAttribute('up-target', '.dashboard-content');
                        });
                    })();
                </script>
            @endif
        </div>

        <!-- Sidebar: Balance & Actions -->
        <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
            <div class="wallet-summary-card">
                <h4 class="summary-title">@lang('ecommerce::ecommerce.wallet_summary')</h4>

                <div class="balance-display">
                    {{ number_format((float)$user->balance, 2) }} <small style="font-size: 14px; color: #a0aec0;">SAR</small>
                </div>

                <a href="{{ route('account.wallet.deposit') }}" class="action-btn btn-deposit" up-target=".dashboard-content">
                    <i class="ti-plus"></i> @lang('ecommerce::ecommerce.deposit_funds')
                </a>

                <a href="{{ route('account.wallet.withdraw') }}" class="action-btn btn-withdraw" up-target=".dashboard-content">
                    <i class="ti-arrow-left arrow-start"></i> @lang('ecommerce::ecommerce.withdraw_funds')
                </a>

                <div class="text-center mt-3 text-muted" style="font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <i class="ti-lock"></i> @lang('ecommerce::ecommerce.secure_payment')
                </div>
            </div>
        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
