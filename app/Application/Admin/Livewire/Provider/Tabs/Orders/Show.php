<?php

namespace Application\Admin\Livewire\Provider\Tabs\Orders;

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
    /**
     * @var mixed|null
     */
    public mixed $provider;

    public function mount(int $order_id, int $company_id, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $this->order = $fetchSingleOrderAction->handle($order_id, $this->company_id);
            $this->order ?? abort(404);

        $this->orderServices = $this->order?->provider_towing_order_detail;
        $this->orderUser = $this->order?->user;
        $this->provider = $this->order?->provider;
        $this->address = $this->order?->address;
    }

    public function render()
    {
        return view('admin::livewire.company.tabs.orders.show');
    }


}
