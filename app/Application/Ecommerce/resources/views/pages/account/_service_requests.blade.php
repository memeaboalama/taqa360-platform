@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    .service-requests-container {
        width: 100%;
        padding-bottom: 40px;
    }

    .precise-table-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .table-responsive { margin-bottom: 0; }

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

    .bg-pending, .bg-warning { background-color: #fff8e1 !important; color: #f57f17 !important; }
    .bg-approved, .bg-completed { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-cancelled, .bg-rejected { background-color: #ffebee !important; color: #c62828 !important; }
    .bg-assigned, .bg-in-progress { background-color: #e3f2fd !important; color: #1565c0 !important; }
    .bg-draft { background-color: #f3e5f5 !important; color: #7b1fa2 !important; }

    /* Service type badge */
    .service-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        background-color: #eef4fa;
        color: #245C8E;
        border: 1px solid #d8e6f2;
        white-space: nowrap;
    }
    .service-type-badge i { font-size: 12px; }

    .request-id { font-weight: 800; color: #1a202c; font-family: monospace; font-size: 15px; }
    .request-title { font-weight: 600; color: #2d3748; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; vertical-align: middle; }
    .total-price { font-weight: 800; color: #2d3748; font-size: 15px; }
    .date-text { color: #718096; font-weight: 500; }

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
        .request-title { white-space: normal; max-width: 100%; }
    }
</style>

@php
    function serviceRequestStatusColor($status): string {
        $value = $status instanceof \UnitEnum ? $status->value : (string) $status;
        return match ($value) {
            'draft' => 'bg-draft',
            'pending' => 'bg-pending',
            'approved' => 'bg-approved',
            'assigned' => 'bg-assigned',
            'rejected' => 'bg-rejected',
            'in-progress' => 'bg-in-progress',
            'completed' => 'bg-completed',
            'cancelled' => 'bg-cancelled',
            default => 'bg-pending',
        };
    }
@endphp

<div class="account-content-area w-100">
    <div class="service-requests-container">

        <div class="content-header mb-4 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.service_requests')</h2>
            <div class="text-muted small">
                @lang('ecommerce::ecommerce.total_requests'): <strong>{{ $serviceRequests->total() }}</strong>
            </div>
        </div>

        @if($serviceRequests && $serviceRequests->count() > 0)
            <div class="precise-table-card">
                <div class="table-responsive">
                    <table class="precise-table">
                        <thead>
                            <tr>
                                <th width="10%">@lang('ecommerce::ecommerce.request_number')</th>
                                <th width="25%">@lang('ecommerce::ecommerce.request_title')</th>
                                <th width="13%">@lang('ecommerce::ecommerce.date')</th>
                                <th width="14%">@lang('ecommerce::ecommerce.service_type')</th>
                                <th width="14%">@lang('ecommerce::ecommerce.status')</th>
                                <th width="11%">@lang('ecommerce::ecommerce.amount')</th>
                                <th width="13%" class="text-end">@lang('ecommerce::ecommerce.details')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceRequests as $serviceRequest)
                            <tr>
                                <td data-label="@lang('ecommerce::ecommerce.request_number')">
                                    <span class="request-id num">{{ $serviceRequest->code }}</span>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.request_title')">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ti-crown text-muted"></i>
                                        <span class="request-title">{{ $serviceRequest->title }}</span>
                                    </div>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.date')">
                                    <span class="date-text">{{ \Carbon\Carbon::parse($serviceRequest->created_at)->format('d M Y') }}</span>
                                    <small class="d-block text-muted" style="font-size: 11px; margin-top: 3px;">{{ \Carbon\Carbon::parse($serviceRequest->created_at)->format('h:i A') }}</small>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.service_type')">
                                    <span class="service-type-badge" title="@lang('ecommerce::ecommerce.service_request_tooltip')">
                                        <i class="ti-settings"></i> {{ $serviceRequest->type instanceof \UnitEnum ? $serviceRequest->type->trans() : $serviceRequest->type }}
                                    </span>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.status')">
                                    <span class="status-badge {{ serviceRequestStatusColor($serviceRequest->status) }}">
                                        {{ $serviceRequest->status instanceof \UnitEnum ? $serviceRequest->status->trans() : $serviceRequest->status }}
                                    </span>
                                </td>
                                <td data-label="@lang('ecommerce::ecommerce.amount')">
                                    <span class="total-price">{{ number_format($serviceRequest->budget ?? 0, 2) }} <small class="fw-normal text-muted">SAR</small></span>
                                </td>
                                <td data-label="" class="text-end">
                                    <a href="{{ route('account.service_request', $serviceRequest->id) }}" class="btn-view" up-target=".dashboard-content">
                                        @lang('ecommerce::ecommerce.view_details') <i class="ti-arrow-left arrow-start ms-1" style="font-size: 10px;"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($serviceRequests->hasPages())
                <div class="pagination_sec mt-4">
                    <div class="content_detail__pagination cdp">
                        {{ $serviceRequests->appends(request()->query())->links('vendor.pagination.custom') }}
                    </div>
                </div>
                <script>
                    (function () {
                        var container = document.currentScript && document.currentScript.previousElementSibling;
                        if (!container) return;
                        container.querySelectorAll('.content_detail__pagination a').forEach(function (a) {
                            a.setAttribute('up-target', '.dashboard-content');
                        });
                    })();
                </script>
            @endif
        @else
            <div class="text-center py-5 precise-table-card">
                <i class="ti-settings text-muted mb-3" style="font-size: 50px;"></i>
                <h4 class="text-muted">@lang('ecommerce::ecommerce.no_service_requests_yet')</h4>
                <button type="button" class="btn btn-solid mt-3" data-bs-toggle="modal" data-bs-target="#serviceRequestModal">
                    @lang('ecommerce::ecommerce.submit_service_request')
                </button>
            </div>
        @endif
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
