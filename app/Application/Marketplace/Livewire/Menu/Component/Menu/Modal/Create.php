<?php

namespace Application\Marketplace\Livewire\Menu\Component\Menu\Modal;

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

    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
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
            'title.ar' => trans('marketplace::home.Title'),
            'title.en' => trans('marketplace::home.Title'),
            'status' => trans('marketplace::home.status'),
        ];
    }

    public function store(StoreServiceAction $storeServiceAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $data['merchant_id'] = $this->merchantId;
        $menu = $storeServiceAction->handle($data);
        $this->emit('refreshMenus');
        $this->emit('closeModal', '#createMenu');
        $this->alert('success', __('marketplace::home.data_saved_successfully'), ['position' => $this->alertPosition()]);
        $this->reset( 'title');
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
        return view('marketplace::livewire.menu.component.menu.modal.create');
    }
}
