<?php

namespace Application\Marketplace\Livewire\Menu\Component\Menu\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchSingleServiceAction;
use App\Domain\MarketPlace\Actions\Dashboard\Service\UpdateServiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;
    public array $title = ['en' => null];
    public string $status = 'active';
    public $merchantId;
    protected $listeners = ['updateMenu'];


    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }
    public function updateMenu($id, FetchSingleServiceAction $fetchSingleServiceAction): void
    {
        $menu = $fetchSingleServiceAction->handle($id, $this->merchantId);
        if ($menu) {
            $this->menu = $menu;
            $this->title = $menu->getTranslations('title');
            $this->status = $menu->status->value;
            $this->menu_id = $menu->id;
            $this->emit('openModal', '#updateMenu');
        }
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

    public function update(UpdateServiceAction $updateServiceAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $menu = $updateServiceAction->handle($this->menu_id, $data);
        $this->emit('closeModal', '#updateMenu');
        $this->emit('selectAndSet', $menu->id);
        $this->emit('refreshMenus');
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.menu.component.menu.modal.update');
    }
}
