<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Category\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Category\StoreMerchantCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public  $status = 'active';
    public $priority = "high", $menu_id = null, $sort;
    public  $title = ['en' => null,  'ar' => null];
    public $merchantId;
    protected $listeners = ['createCategory'];

    public function mount(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }
    public function createCategory($menu_id): void
    {
        $this->menu_id = $menu_id;
        $this->emit('openModal', '#createCategory');
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
            'menu_id' => 'required|exists:merchant_services,id',
            'priority' => 'required|in:high,medium,low',
            'sort' => 'required',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreMerchantCategoryAction $storeMerchantCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $data['merchant_id'] = $this->merchantId;
        $category = $storeMerchantCategoryAction->handle($data);
        $this->emit('refreshCategories', $this->menu_id);
        $this->emit('setCategoryItems', $category->id);
        $this->emit('closeModal', '#createCategory');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset('sort','title','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.category.modal.create');
    }
}
