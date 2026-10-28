<?php

namespace App\Application\Admin\Exports\Orders;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DriverOrderExport implements FromCollection , WithHeadings
{
    public $orders;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $orders = $this->orders->map(function ($order){
            return [
                'id' => $order->id,
                'code' => $order->code,
                'userName' => $order->user?->name,
                'Merchant' => $order->merchant?->company_name,
                'Branch Name' => $order->branch?->title,
                'Driver Name' => $order->driver?->name,
                'Driver Phone' => $order->driver?->phone,
                'totalOrder' => $order->total_after_vat,
                'delivery_fee' => $order?->delivery_fee,
                'commission percentage' => $order?->commission_from_driver,
                'commission' => $order?->driverCommission,
                'Due to the driver' => $order?->dueToDriver,
                'date' => Carbon::parse($order->created_at)->format('Y m d - h:i'),
                'status' => $order->status?->trans(),
                'payment_method' => $order->payment_method,
                'Transaction ID' => $order->payment?->payment_id,
            ];
        });
        return collect($orders);
    }

    public function headings() : array
    {
        return [
            "#",
            __('admin::home.code'),
            __('admin::home.userName'),
            __('admin::home.Merchant'),
            __('admin::home.Branch Name'),
            __('admin::home.Driver Name'),
            __('admin::home.Driver Phone'),
            __('admin::home.totalOrder'),
            __('admin::home.delivery_fee'),
            __('admin::home.commission percentage'),
            __('admin::home.commission'),
            __('admin::home.Due to the driver'),
            __('admin::home.date'),
            __('admin::home.status'),
            __('admin::home.payment_method'),
            __('admin::home.Transaction ID'),
        ];
    }


}
