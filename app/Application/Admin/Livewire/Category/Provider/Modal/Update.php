<?php

namespace Application\Admin\Livewire\Category\Provider\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Main\FetchMainCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Provider\FetchSingleProviderCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Provider\UpdateProviderCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public string $status = 'active';
    public int|null $provider_category_id;
    public array $title = ['en' => null];
    public $selection, $main_category_id, $main_categories;
    protected $listeners = ['updateProviderCategory'];

    public function mount(FetchMainCategoryAction $fetchMainCategoryAction)
    {
        $this->main_categories = $fetchMainCategoryAction->handle();
    }


    public function updateProviderCategory($id, FetchSingleProviderCategoryAction $fetchSingleProviderCategoryAction): void
    {
        $providerCategory = $fetchSingleProviderCategoryAction->handle($id);
        if ($providerCategory) {
            $this->title = $providerCategory->getTranslations('title');
            $this->status = $providerCategory->status->value;
            $this->provider_category_id = $providerCategory->id;
            $this->selection = $providerCategory->selection;
            $this->main_category_id = $providerCategory->main_category_id;
            $this->emit('openModal', '#updateProviderCategory');
        }
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

    public function store(UpdateProviderCategoryAction $updateProviderCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateProviderCategoryAction->handle($this->provider_category_id, $data);
        $this->emit('closeModal', '#updateProviderCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Provider Category Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.provider.modal.update');
    }
}
