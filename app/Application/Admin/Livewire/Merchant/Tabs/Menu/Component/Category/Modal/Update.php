<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Category\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Category\FetchSingleMerchantCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Menu\Category\UpdateMerchantCategoryAction;
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

    public function mount(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }

    public function updateCategory($id, $menu_id, FetchSingleMerchantCategoryAction $fetchSingleMerchantCategoryAction): void
    {
        $category = $fetchSingleMerchantCategoryAction->handle($id, $this->merchantId);
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
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'sort' => trans('admin::home.Priority'),
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

    public function store(UpdateMerchantCategoryAction $updateMerchantCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $category = $updateMerchantCategoryAction->handle($this->category_id, $data);
        $this->emit('closeModal', '#updateCategory');
        $this->emit('refreshCategories', $this->menu_id);
        $this->emit('setCategoryItems', $category->id);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.category.modal.update');
    }
}
