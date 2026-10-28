<?php

namespace Application\Admin\Livewire\Educator\Modal;

use App\Domain\Admin\Actions\Dashboard\Educator\ApproveEducatorAction;
use App\Domain\Admin\Actions\Dashboard\Educator\FetchSingleEducatorAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Approve extends Component
{
    use LivewireAlert;

    public $educator_id;
    protected $listeners = ['approveEducator'];

    public function approveEducator($id, FetchSingleEducatorAction $fetchSingleEducatorAction): void
    {
        $educator = $fetchSingleEducatorAction->handle($id);
        if ($educator) {
            $this->educator_id = $educator->id;
            $this->emit('openModal', '#approveEducator');
        }
    }

    public function accept(ApproveEducatorAction $approveEducatorAction): void
    {
        DB::beginTransaction();
        $approveEducatorAction->handle($this->educator_id);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#approveEducator');
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.educator.modal.approve');
    }
}
