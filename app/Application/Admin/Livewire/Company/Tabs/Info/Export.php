<?php

namespace Application\Admin\Livewire\Company\Tabs\Info;

use App\Application\Admin\Exports\Orders\DriverOrderExport;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public array $filters = [];
    public $company_id;
    protected MerchantOrderRepository $orderRepository;
    protected $listeners = ['export'];
    public function mount(int $company_id)
    {
        $this->company_id = $company_id;
    }

    public function export()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        return Excel::download(new DriverOrderExport($this->orderRepository->getCompanyDriversOrders($this->company_id)->get())
            , __('admin::home.Company Order Report') .'.xlsx');
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.tabs.info.export');
    }
}
