<?php

namespace Application\Admin\Livewire\Category\Main\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Main\StoreMainCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $type;


    public function nickNames()
    {
        return [
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
        ];
    }

    protected function rules(): array
    {
        return [
            'type' => 'required',
            'title.en' => 'required|string|min:2|max:120',
            'title.ar' => 'required|string|min:2|max:120',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreMainCategoryAction $storeMainCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeMainCategoryAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createMainCategory');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset('title','description', 'type','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.main.modal.create');
    }
}
