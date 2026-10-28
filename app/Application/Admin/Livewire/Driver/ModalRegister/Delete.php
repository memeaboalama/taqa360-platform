<?php

namespace Application\Admin\Livewire\Driver\ModalRegister;

use App\Domain\Admin\Actions\Dashboard\Driver\DestroyDriverAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteDriver'];

    public function deleteDriver($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteDriver');
    }

    public function destroy(DestroyDriverAction $destroyDriverAction): void
    {
        DB::beginTransaction();
        $destroyDriverAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteDriver');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Driver Deactivated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modalregister.delete');
    }
}
