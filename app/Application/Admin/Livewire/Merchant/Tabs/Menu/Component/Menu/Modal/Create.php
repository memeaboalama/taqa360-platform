<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Menu\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Menu\StoreMenuAction;
use App\Domain\MarketPlace\Actions\Dashboard\Service\StoreServiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;
    public array $title = ['en' => null];
    public string $status = 'active';
    public $categories;
    public $menuSelected;
    public $merchantId;

    public function mount(int $merchant_id)
    {
        $this->merchantId = $merchant_id;
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    protected function rules(): array
    {
        return [
            'title.en' => ['required'],
            'title.ar' => ['required'],
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'status' => trans('admin::home.status'),
        ];
    }

    public function store(StoreMenuAction $storeMenuAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $data['merchant_id'] = $this->merchantId;
        $menu = $storeMenuAction->handle($data);
        $this->emit('selectAndSet', $menu->id);
        $this->emit('refreshMenus');
        $this->emit('closeModal', '#createMenu');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset( 'title');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.menu.modal.create');
    }
}
