<?php

namespace Application\Admin\Livewire\Order\Modal;

use Domain\MarketPlace\Models\ElmOrder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Elmerrors extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public array|null $elm_errors = [];
    protected $listeners = ['showElmOrderErros'];

    public function showElmOrderErros($id): void
    {
        $this->elm_errors = ElmOrder::where('id', $id)->value('elm_errors');
        $this->elm_errors ? $this->emit('openModal', '#showElmOrderErros'): null;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.order.modal.elmerrors');
    }
}
