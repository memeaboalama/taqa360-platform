<?php

namespace App\Application\Admin\Livewire\Message\Table;

use Livewire\Component;

class Action extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.message.table.action');
    }
}
