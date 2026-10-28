@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Driver Financial Reports</title>
    <style>
        body {
            font-family: 'dejavusans', sans-serif;
            direction: rtl;
            font-size: 11px;
            color: #333;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e12b2b;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #e12b2b;
        }
        .company-info {
            font-size: 9px;
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #222;
            margin: 8px 0;
        }
        .date-info {
            text-align: right;
            font-size: 9px;
            color: #555;
            margin-bottom: 8px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .data-table thead tr {
            background-color: #e12b2b;
            color: #ffffff;
        }
        .data-table thead th {
            padding: 5px 4px;
            text-align: right;
            border: 1px solid #aaa;
        }
        .data-table tbody tr:nth-child(even) {
            background-color: #fdf5f5;
        }
        .data-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .data-table tbody td {
            padding: 4px;
            text-align: right;
            border: 1px solid #ddd;
        }
        .totals-table {
            width: 55%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }
        .totals-table td {
            padding: 5px 8px;
            border: 1px solid #ddd;
        }
        .totals-table .lbl {
            background-color: #faf0f0;
            font-weight: bold;
            text-align: right;
            color: #333;
        }
        .totals-table .val {
            text-align: center;
            color: #e12b2b;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 4px;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td>
            @if(isset($filters['driver_id']) && $orders->first()?->driver)
                <div class="company-name">كشف حساب السائق - {{ $orders->first()->driver->name }}</div>
            @else
                <div class="company-name">كشف حساب السائقين</div>
            @endif
            <div class="company-info">شركة توصيل الخليجية للتجارة | البريد: info@eats.com.sa | هاتف: 920033084 | الرقم الضريبي: 310564268100003</div>
        </td>
    </tr>
</table>

<div class="report-title">التقارير المالية للسائقين — Driver Financial Reports</div>

@if(isset($filters['date']) && $filters['date'] != "")
<div class="date-info">
    من: <strong>{{ $filters['date'] }}</strong>
    @if(isset($filters['to_date']) && $filters['to_date'] != "")
        &nbsp;&nbsp; إلى: <strong>{{ $filters['to_date'] }}</strong>
    @endif
</div>
@endif

<table class="data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('admin::home.code') }}</th>
            <th>{{ __('admin::home.userName') }}</th>
            <th>{{ __('admin::home.Merchant') }}</th>
            <th>{{ __('admin::home.Branch Name') }}</th>
            <th>{{ __('admin::home.Driver Name') }}</th>
            <th>{{ __('admin::home.Driver Phone') }}</th>
            <th>{{ __('admin::home.totalOrder') }}</th>
            <th>{{ __('admin::home.delivery_fee') }}</th>
            <th>{{ __('admin::home.commission percentage') }}</th>
            <th>{{ __('admin::home.commission') }}</th>
            <th>{{ __('admin::home.Due to the driver') }}</th>
            <th>{{ __('admin::home.date') }}</th>
            <th>{{ __('admin::home.status') }}</th>
            <th>{{ __('admin::home.payment_method') }}</th>
            <th>{{ __('admin::home.Transaction ID') }}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($orders as $i => $order)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $order->code }}</td>
            <td>{{ $order->user?->name }}</td>
            <td>{{ $order->merchant?->company_name }}</td>
            <td>{{ $order->branch?->title }}</td>
            <td>{{ $order->driver?->name }}</td>
            <td>{{ $order->driver?->phone }}</td>
            <td>{{ number_format($order->total_after_vat, 2) }}</td>
            <td>{{ number_format($order->delivery_fee, 2) }}</td>
            <td>{{ $order->commission_from_driver }}%</td>
            <td>{{ number_format($order->driverCommission, 2) }}</td>
            <td>{{ number_format($order->dueToDriver, 2) }}</td>
            <td>{{ Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
            <td>{{ $order->status?->trans() }}</td>
            <td>{{ $order->payment_method }}</td>
            <td>{{ $order->payment?->payment_id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="totals-table">
    <tr>
        <td class="lbl">إجمالي رسوم التوصيل (شامل ضريبة 15%)</td>
        <td class="val">{{ number_format($orders->sum('delivery_fee'), 2) }} SAR</td>
    </tr>
    <tr>
        <td class="lbl">إجمالي العمولة المستقطعة</td>
        <td class="val">{{ number_format($orders->sum(fn($o) => $o->driverCommission), 2) }} SAR</td>
    </tr>
    <tr>
        <td class="lbl">إجمالي المستحق للسائق</td>
        <td class="val">{{ number_format($orders->sum(fn($o) => $o->dueToDriver), 2) }} SAR</td>
    </tr>
</table>

<div class="footer">
    تاريخ التصدير: {{ now()->format('Y-m-d H:i') }} | عدد السجلات: {{ $orders->count() }}
</div>

</body>
</html>
