<?php

namespace Application\Marketplace\Livewire\Menu\Component\Category\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchSingleCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\UpdateCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public $priority = "high";
    public  $category_id, $sort, $menu_id = null;
    public array $title = ['en' => null];
    public $merchantId;
    public $selected_package = null;

    protected $listeners = ['updateCategory'];

    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }

    public function updateCategory($id, $menu_id, FetchSingleCategoryAction $fetchSingleCategoryAction): void
    {
        $category = $fetchSingleCategoryAction->handle($id, $this->merchantId);
        $this->menu_id = $menu_id;
        if ($category) {
            $this->title = $category->getTranslations('title');
            $this->priority = $category->priority;
            $this->sort = $category->sort;
            $this->status = $category->status;
            $this->category_id = $category->id;
            $this->emit('openModal', '#updateCategory');
        }
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('marketplace::home.Title'),
            'title.en' => trans('marketplace::home.Title'),
            'sort' => trans('marketplace::home.Priority'),
        ];
    }

    protected function rules(): array
    {
        return [
            'title.en' => ['required'],
            'title.ar' => ['required'],
            'priority' => 'required|in:high,medium,low',
            'sort' => 'required',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateCategoryAction $updateCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $category = $updateCategoryAction->handle($this->category_id, $data);
        $this->emit('closeModal', '#updateCategory');
        $this->emit('refreshCategories', $this->menu_id);
        $this->emit('setCategoryItems', $category->id);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.category.modal.update');
    }
}
