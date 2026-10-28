<?php

namespace Application\Admin\Livewire\Driver\Modal;

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

    public function accept(ApproveDriverAction $approveDriverAction, FetchSingleDriverAction $fetchSingleDriverAction): void
    {
        $driver = $fetchSingleDriverAction->handle($this->driver_id);
        if (!$driver) {
            $this->emit('closeModal', '#approveDriver');
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }
        // Do not approve drivers with missing files ("غير جاهزة").
        if (!$driver->filesCompleted()) {
            $this->alert('error', __('admin::home.files') . ': ' . __('admin::home.Incomplete'));
            return;
        }
        DB::beginTransaction();
        try {
            $approveDriverAction->handle($this->driver_id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#approveDriver');
        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modal.approve');
    }
}
