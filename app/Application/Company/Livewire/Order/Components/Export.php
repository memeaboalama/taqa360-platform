<?php

namespace Application\Company\Livewire\Order\Components;

use App\Application\Company\Exports\Drivers\DriverOrderExport;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Export extends Component
{
    public array $filters = [];
    public $companyId;
    protected MerchantOrderRepository $orderRepository;
    protected $listeners = ['prepareDriverOrderExport', 'export'];

    public function mount()
    {
        $this->companyId = resolve('_InitialCompany');
    }

    public function prepareDriverOrderExport($filters)
    {
        $this->filters = $filters;
    }

    public function export()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        return Excel::download(new DriverOrderExport($this->orderRepository->getCompanyDriversOrders($this->companyId,
                $this->filters)->with(['user', 'driver', 'payment'])->get())
            , 'Driver requests.xlsx');
    }
    public function render()
    {
        return view('company::livewire.order.components.export');
    }
}
