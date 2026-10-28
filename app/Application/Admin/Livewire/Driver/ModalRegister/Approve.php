<?php

namespace Application\Admin\Livewire\Driver\ModalRegister;

use App\Domain\Admin\Actions\Dashboard\Driver\ApproveDriverAction;
use App\Domain\Admin\Actions\Dashboard\Driver\FetchSingleDriverAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Approve extends Component
{
    use LivewireAlert;

    public $driver_id;
    protected $listeners = ['approveDriver'];

    public function approveDriver($id, FetchSingleDriverAction $fetchSingleDriverAction): void
    {
        $driver = $fetchSingleDriverAction->handle($id);
        if ($driver) {
            $this->driver_id = $driver->id;
            $this->emit('openModal', '#approveDriver');
        }
    }

    public function accept(ApproveDriverAction $approveDriverAction): void
    {
        DB::beginTransaction();
        $approveDriverAction->handle($this->driver_id);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#approveDriver');
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modalregister.approve');
    }
}
