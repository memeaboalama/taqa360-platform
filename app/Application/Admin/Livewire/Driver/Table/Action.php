<?php

namespace Application\Admin\Livewire\Driver\Table;

use Livewire\Component;

class Action extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.table.action');
    }
}
