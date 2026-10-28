<?php

namespace Application\Admin\Livewire\Category\Provider\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Main\FetchMainCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Provider\StoreProviderCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public array $title = ['en' => null];
    public $selection, $main_category_id, $main_categories;

    public function mount(FetchMainCategoryAction $fetchMainCategoryAction)
    {
        $this->main_categories = $fetchMainCategoryAction->handle();
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'main_category_id' => 'main category',
            'title.en' => 'title'
        ];
    }

    protected function rules(): array
    {
        return [
            'main_category_id' => 'required',
            'selection' => 'required',
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreProviderCategoryAction $storeProviderCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeProviderCategoryAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createProviderCategory');
        $this->alert('success', 'Provider Category Created Successfully');
        $this->reset('selection','main_category_id', 'title','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.provider.modal.create');
    }
}
