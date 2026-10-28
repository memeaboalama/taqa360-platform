<?php

namespace Application\Admin\Livewire\Company\Tabs\Info;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Livewire\Component;

class Pdf extends Component
{
    public array $filters = [];
    public $company_id;
    protected MerchantOrderRepository $orderRepository;
    protected $listeners = ['exportPdf'];

    public function mount(int $company_id)
    {
        $this->company_id = $company_id;
    }
    public function exportPdf()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $filters = $this->filters;
        $orders = $this->orderRepository->getCompanyDriversOrders($this->company_id)->get();
        $pdf = \PDF::loadView('admin::pages.pdf.finance.reports.orders.company.index', compact('orders', 'filters'));
        return $pdf->stream();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.tabs.info.pdf');
    }
}
