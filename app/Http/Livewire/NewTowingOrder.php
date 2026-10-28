<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NewTowingOrder extends Component
{
    public $providerId;
    public $showNewOrderNotification = false;
    public $data;

    public function getListeners(): array
    {
        return [
            "echo-private:providers.{$this->providerId},.new-towing-order" => 'notifyNewOrder',
        ];
    }
    public function notifyNewOrder($data)
    {
        $this->data = $data;
        $this->showNewOrderNotification = true;
    }

    public function mount()
    {
        $this->providerId = auth('provider')->id();

    }
    public function render()
    {
        return view('livewire.new-towing-order');
    }
}
