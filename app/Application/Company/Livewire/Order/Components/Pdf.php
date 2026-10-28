<?php

namespace Application\Company\Livewire\Order\Components;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Livewire\Component;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class Pdf extends Component
{
    public array $filters = [];
    public bool $hasRecords = true;
    public $companyId;
    protected MerchantOrderRepository $orderRepository;
    protected $listeners = ['prepareDriverOrderPdf', 'exportPdf'];

    public function prepareDriverOrderPdf($filters)
    {
        $this->filters = $filters;
        $this->checkHasRecords();
    }

    public function mount()
    {
        $this->companyId = resolve('_InitialCompany');
        $this->checkHasRecords();
    }

    public function checkHasRecords()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $count = $this->orderRepository->getCompanyDriversOrders($this->companyId, $this->filters)->count();
        $this->hasRecords = $count > 0;
    }

    public function exportPdf()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $filters = $this->filters;
        $ordersQuery = $this->orderRepository->getCompanyDriversOrders($this->companyId, $this->filters)
            ->with(['user', 'merchant', 'branch', 'driver', 'payment']);

        if ($ordersQuery->count() === 0) {
            $this->hasRecords = false;
            $this->emit('toastr', [
                'type' => 'warning',
                'message' => __('company::home.no_records_to_export') ?: 'لا توجد سجلات للتصدير'
            ]);
            return;
        }

        $orders = $ordersQuery->get();

        $html = view('company::pages.pdf.orders.drivers.index', compact('orders', 'filters'))->render();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode'        => 'utf-8',
            'format'      => 'A4-L',
            'orientation' => 'L',
            'default_font' => 'dejavusans',
            'fontDir'     => $fontDirs,
            'fontdata'    => $fontData,
            'autoScriptToLang' => true,
            'autoLangToFont'   => true,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;
        $mpdf->baseScript       = 1;
        $mpdf->autoVietnamese   = true;
        $mpdf->autoArabic       = true;

        $mpdf->WriteHTML($html);

        $filename = 'Driver requests.pdf';

        return response()->streamDownload(function () use ($mpdf, $filename) {
            echo $mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN);
        }, $filename);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.order.components.pdf');
    }
}