<?php

namespace Application\Admin\Livewire\Order\Components;

use App\Domain\MarketPlace\Models\MerchantOrder;
use Livewire\Component;

class Employees extends Component
{
    public $order;
    protected $listeners = ['refreshOrderEmployees' => 'render', 'refresh_employees' => 'refresh_employees'];

    public function mount($order)
    {
        $this->order = $order;
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.components.employees', [
            'order' => $this->order,
        ]);
    }

    public function refresh_employees(MerchantOrder $order)
    {
        $this->order = $order;
    }
}
