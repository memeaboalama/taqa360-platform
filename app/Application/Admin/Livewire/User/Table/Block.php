<?php

namespace App\Application\Admin\Livewire\User\Table;

use Livewire\Component;

class Block extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.user.table.block');
    }
}
