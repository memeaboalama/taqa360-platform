<?php

namespace Application\Admin\Livewire\Company\Tabs\Orders;

use App\Domain\Provider\Actions\Dashboard\Order\FetchSingleOrderAction;
use Livewire\Component;

class Show extends Component
{
    public $order;
    public $orderUser;
    public $orderServices = [];
    public $address;
    public mixed $company_id;
    public int $order_id;
    public mixed $provider_sub_services;
    public mixed $towingOrder;
    /**
     * @var mixed|null
     */
    public mixed $provider;


    public function mount(int $order_id, int $company_id = null, FetchSingleOrderAction $fetchSingleOrderAction): void
    {
        $this->order = $fetchSingleOrderAction->handle($order_id);
            $this->order ?? abort(404);


        $this->towingOrder = $this->order?->provider_towing_order_detail;
        $this->provider_sub_services = $this->order?->provider_sub_services;

        $this->orderUser = $this->order?->user;
        $this->provider = $this->order?->provider;
        $this->address = $this->order?->address;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.tabs.orders.show');
    }


}
