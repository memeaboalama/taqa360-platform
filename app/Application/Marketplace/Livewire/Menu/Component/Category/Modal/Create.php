<?php

namespace Application\Marketplace\Livewire\Menu\Component\Category\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Category\StoreCategoryAction;
use Domain\MarketPlace\Models\MerchantService;
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

    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }
    public function createCategory($menu_id): void
    {
        $this->menu_id = $menu_id;
        $this->emit('openModal', '#createCategory');
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

    public function store(StoreCategoryAction $storeCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $data['merchant_id'] = $this->merchantId;
        $category = $storeCategoryAction->handle($data);
        $this->emit('refreshCategories', MerchantService::find($this->menu_id));
        $this->emit('closeModal', '#createCategory');
        $this->alert('success', __('marketplace::home.data_saved_successfully'), ['position' => $this->alertPosition()]);
        $this->reset('sort','title','status');
        DB::commit();
    }

    private function alertPosition(): string
    {
        return \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale() === 'ar'
            ? 'top-start'
            : 'top-end';
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.category.modal.create');
    }
}
