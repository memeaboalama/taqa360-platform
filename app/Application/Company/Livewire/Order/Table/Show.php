<?php

namespace Application\Company\Livewire\Order\Table;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use LivewireAlert;
    public $order;
    public $address;
    public $towingOrderDetails;
    public $provider_sub_services;
    public $provider;
    public function mount($order, $towingOrderDetails, $provider_sub_services, $provider)
    {
        $this->order = $order;
        $this->towingOrderDetails = $towingOrderDetails;
        $this->provider_sub_services = $provider_sub_services;
        $this->provider = $provider;
        $this->address = $this->order->user->addresses()->where('is_default', 1)->first();
    }
    public function render()
    {
        return view('company::livewire.order.table.show');
    }
}
