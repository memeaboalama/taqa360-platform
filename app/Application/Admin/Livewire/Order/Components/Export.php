<?php

namespace Application\Admin\Livewire\Order\Components;

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
        return Excel::download(new MerchantOrderExport($this->orderRepository->filter($this->filters, ['orderItems','user'])->get())
            , __('admin::home.Orders Report') .'.xlsx');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.components.export');
    }
}
