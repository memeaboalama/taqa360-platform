<?php

namespace Application\Admin\Livewire\Finance\Reports\Order\Merchant\Components;

use App\Application\Admin\Exports\Orders\MerchantOrderExport;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public array $filters = [];
    protected MerchantOrderRepository $orderRepository;
    protected $listeners = ['prepareMerchantOrderExport', 'export'];
    public function prepareMerchantOrderExport($filters)
    {
        $this->filters = $filters;
    }

    public function export()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        return Excel::download(new MerchantOrderExport($this->orderRepository->filterOrders($this->filters,
            ['user', 'merchant', 'branch', 'payment', 'merchant.packages'], ['*'])->get())
            , 'Store Order Report.xlsx');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.finance.reports.order.merchant.components.export');
    }
}
