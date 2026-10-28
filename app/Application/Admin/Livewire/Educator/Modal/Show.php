<?php

namespace Application\Admin\Livewire\Educator\Modal;

use App\Domain\Admin\Actions\Dashboard\Driver\ApproveDriverAction;
use App\Domain\Admin\Actions\Dashboard\Driver\FetchSingleDriverAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Show extends Component
{
    use LivewireAlert;

    public int $id;
    protected $listeners = ['showEducatorInfo'];

    public function showEducatorInfo($d_id)
    {
            $this->id = $d_id;
            $this->emit('openModal', '#showEducatorInfo');
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.educator.modal.show');
    }
}
