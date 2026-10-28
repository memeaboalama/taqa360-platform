<?php

namespace App\Http\Livewire;

use App\Domain\Provider\Models\ProviderOrder;
use Livewire\Component;

class FollowTowingOrder extends Component
{
    public $orderId;
    public $showNewOrderNotification = false;
    public $data;

    public function getListeners(): array
    {
        return [
            "echo-private:user-order.{$this->orderId},.user-order-follow" => 'notifyNewOrder',
        ];
    }
    public function notifyNewOrder($data): void
    {
        $this->data = $data;
        $this->showNewOrderNotification = true;
    }

    public function mount()
    {
        $this->orderId = ProviderOrder::latest()->first()->id;

    }
    public function render()
    {
        return view('livewire.follow-towing-order');
    }
}
