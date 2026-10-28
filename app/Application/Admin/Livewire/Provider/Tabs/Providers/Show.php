<?php

namespace Application\Admin\Livewire\Provider\Tabs\Providers;

use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use Livewire\Component;

class Show extends Component
{
    public $order;
    public $orderUser;
    public $orderServices;
    public $address;
    public mixed $merchant_id;
    public int $order_id;

    public function mount(int $order_id, int $merchant_id, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $this->order = $fetchSingleOrderAction->handle($order_id, $this->merchant_id);
            $this->order ?? abort(404);

        $this->orderServices = $this->order?->services;
        $this->orderUser = $this->order?->user;
        $this->address = $this->order?->address;
    }

    public function render()
    {
        return view('admin::livewire.company.tabs.orders.show');
    }


}
