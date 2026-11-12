<?php

namespace Application\Admin\Controllers\Finance\Reports\Driver;

use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

class InvoiceDeliveryController extends Controller
{
    public function index()
    {
        return view('admin::pages.finance.reports.driver.invoice.index');
    }

    public function show($orderId, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $order = $fetchSingleOrderAction
            ->getById($orderId);
        if ($order)
        {
            $date = Carbon::parse($order->created_at)->toIso8601String();

            $displayQRCode = GenerateQrCode::fromArray([
                new Seller("شركة توصيل الخليجية للتجارة"),
                new TaxNumber("310564268100003"),
                new InvoiceDate($date),
                new InvoiceTotalAmount($order?->delivery_fee),
                new InvoiceTaxAmount($order->deliveryVat)
            ])->render();
            $html = view('admin::pages.pdf.finance.reports.driver.invoice.index', compact('order', 'displayQRCode'))->render();

            $defaultConfig = (new ConfigVariables())->getDefaults();
            $defaultFontConfig = (new FontVariables())->getDefaults();
            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font' => 'dejavusans',
                'fontDir' => $defaultConfig['fontDir'],
                'fontdata' => $defaultFontConfig['fontdata'],
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);
            $pdf->SetDirectionality('rtl');
            $pdf->WriteHTML($html);

            return response($pdf->Output('Delivery Invoice.pdf', \Mpdf\Output\Destination::STRING_RETURN), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Delivery Invoice.pdf"',
            ]);
        }else{
            abort(404);
        }

    }
}
