<?php

namespace Application\Admin\Livewire\Category\Main\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Main\FetchSingleMainCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Main\UpdateMainCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public string $status = 'active';
    public int|null $main_category_id;
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $sort;
    public $type;
    public $category;

    protected $listeners = ['updateMainCategory'];

    public function updateMainCategory($id, FetchSingleMainCategoryAction $fetchSingleMainCategoryAction): void
    {

        $this->category = $fetchSingleMainCategoryAction->handle($id);
        if ($this->category) {
            $this->title = $this->category->getTranslations('title');
            $this->description = $this->category->getTranslations('description');
            $this->status = $this->category->status->value;
            $this->main_category_id = $this->category->id;
            $this->type = $this->category->type;
            $this->emit('openModal', '#updateMainCategory');
        }
    }


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

    public function store(UpdateMainCategoryAction $updateMainCategoryAction): void
    {
        if (!$this->main_category_id) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $data = $this->validate(attributes: $this->nickNames());
        $category = DB::transaction(
            fn () => $updateMainCategoryAction->handle($this->main_category_id, $data)
        );
        if (!$category) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->emit('closeModal', '#updateMainCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.main.modal.update');
    }
}
