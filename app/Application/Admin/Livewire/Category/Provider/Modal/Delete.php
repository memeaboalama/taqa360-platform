<?php

namespace Application\Admin\Livewire\Category\Provider\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Provider\DestroyProviderCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Delete extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    protected $listeners = ['deleteProviderCategory'];

    public function deleteProviderCategory($id): void
    {
        $this->element_id = $id;
        $this->emit('openModal', '#deleteProviderCategory');
    }

    public function destroy(DestroyProviderCategoryAction $destroyProviderCategoryAction): void
    {
        DB::beginTransaction();
        $destroyProviderCategoryAction->handle($this->element_id);
        $this->emit('closeModal', '#deleteProviderCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Provider Category Deactivated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.provider.modal.delete');
    }

}
