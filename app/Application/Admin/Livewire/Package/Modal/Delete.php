<?php

namespace Application\Admin\Livewire\Package\Modal;

use App\Domain\Admin\Actions\Dashboard\Package\DestroyPackageAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deletePackage'];

    public function deletePackage($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deletePackage');
    }

    public function destroy(DestroyPackageAction $destroyPackageAction): void
    {
        try {
            DB::beginTransaction();
            $destroyPackageAction->handle($this->element_id);
            $this->alert('success', __('admin::lang.Package Deactivated Successfully'));
            $this->emit('closeModal', '#deletePackage');
            $this->emit('refreshDatatable');
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Package Delete Error: ' . $e->getMessage());
            $this->alert('error', __('admin::lang.Something went wrong'));
        }
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.package.modal.delete');
    }
}
