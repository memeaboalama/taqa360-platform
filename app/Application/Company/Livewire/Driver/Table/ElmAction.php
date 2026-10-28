<?php

namespace Application\Company\Livewire\Driver\Table;

use Livewire\Component;

class ElmAction extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('company::livewire.driver.table.elmaction');
    }
}
