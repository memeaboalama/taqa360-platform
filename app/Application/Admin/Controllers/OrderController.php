<?php

namespace App\Application\Admin\Controllers;

use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Domain\AppBase\Models\Payment;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin::pages.orders.index');
    }

    public function showOrder($orderId, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $order = $fetchSingleOrderAction
            ->getById($orderId);
        if ($order) {
            $orderItems = $order->orderItems;
            $orderUser = $order->user;
            $address = $order->address;
            $driver = $order->driver;
            $payment = $order->payment()
                ->latest('id')
                ->first()
                ?? Payment::where('payable_id', $orderId)
                    ->where(function ($q) use ($order) {
                        $q->where('payable_type', $order->getMorphClass())
                          ->orWhere('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder');
                    })
                    ->latest('id')
                    ->first()
                ?? $order->payment;
            return view('admin::pages.orders.show', get_defined_vars());
        } else
            abort(404);
    }

    public function showInvoice($orderId, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $order = $fetchSingleOrderAction
            ->getById($orderId);
        $date = Carbon::parse($order->created_at)->toIso8601String();

        $displayQRCode = GenerateQrCode::fromArray([
            new Seller($order->merchant?->company_name),
            new TaxNumber(311034145900003),
            new InvoiceDate($date),
            new InvoiceTotalAmount($order->total_after_vat),
            new InvoiceTaxAmount($order->vat)
        ])->render();
        $pdf = \PDF::loadView('admin::pages.pdf.index', compact('order', 'displayQRCode'));
        return $pdf->inline();
    }
}
