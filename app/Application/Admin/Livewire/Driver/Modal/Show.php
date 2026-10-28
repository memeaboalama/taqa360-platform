<?php

namespace Application\Admin\Livewire\Driver\Modal;

use App\Domain\Admin\Actions\Dashboard\Driver\ApproveDriverAction;
use App\Domain\Admin\Actions\Dashboard\Driver\FetchSingleDriverAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use LivewireAlert;

    public ?int $driver_id = null;
    protected $listeners = ['showRegDriver'];

    public function showRegDriver($d_id)
    {
            $this->driver_id = $d_id;
            $this->emit('openModal', '#showRegDriver');
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modal.show');
    }
}
