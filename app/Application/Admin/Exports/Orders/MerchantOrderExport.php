<?php

namespace App\Application\Admin\Exports\Orders;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MerchantOrderExport implements FromCollection, WithHeadings
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
        $orders = $this->orders->map(function ($order) {
            return [
                'id' => $order->id,
                'code' => $order->code,
                'userName' => $order->user?->name,
                //    'userPhone' => $order->user?->PhoneNumber,
                'userPhone' => $order->user?->phone_number,
                'Merchant' => $order->merchant?->company_name,
                'Branch Name' => $order->branch?->title,
                //    'Promocode' => $order->promocode,
                'Promocode' => $order->refPromocode?->code,
                'Order Price' => $order->subtotal,
                'Admin Discount' => $order->refPromocode?->merchant_id ?  number_format(0, 2) : number_format($order->discount, 2),
                'Merchant Discount' => $order->refPromocode?->merchant_id ?  number_format($order->discount, 2) : number_format(0, 2),
                'Total after Merchant Discount' => $order->refPromocode?->merchant_id ?  number_format((float) $order->subtotal - (float) $order->discount, 2) : number_format(0, 2),
                'commission percentage' => $order->merchant?->commissionPercentage,
                'commission' => number_format($order->commissionFromSub, 2),
                //    'Payment Id' => $order->payment?->payment_id,
                // 'Payment Paid' => $order->payment?->amountFormat,
                // 'Payment Commission' => $order->payment?->PaymentCommission,
                'Payment Id' => $order->refPayment?->payment_id,
                'Payment Paid' => $order->refpayment?->amountFormat,
                //    'Payment Commission' => $order->refpayment?->PaymentCommission,
                'Payment Commission' => $order->PaymentCommission,
                'Due to the merchant' => number_format($order->dueToMerchantFromSub, 2),
                'date' => Carbon::parse($order->created_at)->format('Y m d - H:i:s'),
                'status' => $order->status?->trans(),
                'payment_method' => $order->payment_method,
                'cash_back' =>$order->cash_back ,
                //    'discount' => $order->discount,
                //    'Total after Discount' => $order->subtotal - $order->discount,
                'change_status_by' => $order->ordersHistoryLastStatusChangeBy,
            ];
        });
        return collect($orders);
    }
    public function headings(): array
    {
        return [
            "#",
            __('admin::home.code'),
            __('admin::home.userName'),
            __('admin::home.phone'),
            __('admin::home.Merchant'),
            __('admin::home.Branch Name'),
            __('admin::home.Promocode'),
            __('admin::home.Order Price'),
            __('admin::home.Admin Discount'),
            __('admin::home.Merchant Discount'),
            __('admin::home.Total after discount merchants'),
            __('admin::home.commission percentage'),
            __('admin::home.commission'),
            __('admin::home.Payment Id'),
            __('admin::home.Payment paid'),
            __('admin::home.Payment Commission'),
            __('admin::home.Due to the merchant'),
            __('admin::home.date'),
            __('admin::home.status'),
            __('admin::home.payment_method'),
            __('admin::home.change_status_by'),
            // __('admin::home.discount'),
            // __('admin::home.Total after discount'),
        ];
    }
}
