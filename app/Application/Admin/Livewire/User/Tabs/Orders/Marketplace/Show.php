<?php

namespace Application\Admin\Livewire\User\Tabs\Orders\Marketplace;

use App\Domain\MarketPlace\Actions\Dashboard\Order\FetchSingleOrderAction;
use Livewire\Component;

class Show extends Component
{
    public $order;
    public $orderUser;
    public $orderServices;
    public $address;
    public mixed $user_id;
    public int $order_id;

    public function mount(int $order_id, int $user_id, FetchSingleOrderAction $fetchSingleOrderAction)
    {
        $this->order = $fetchSingleOrderAction->getByUser($order_id, $user_id);
            $this->order ?? abort(404);


        $this->orderItems = $this->order?->orderItems;
        $this->orderUser = $this->order?->user;
        $this->address = $this->order?->address;
    }

    public function render()
    {
        return view('admin::livewire.user.tabs.orders.marketplace.show', [
            'order' => $this->order,
            'orderItems' => $this->orderItems,
            'orderUser' => $this->orderUser,
            'address' => $this->address,
        ]);
    }


}
