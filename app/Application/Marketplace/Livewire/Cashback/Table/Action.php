<?php

namespace Application\Marketplace\Livewire\Cashback\Table;

use Livewire\Component;

class Action extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.cashback.table.action');
    }
}
