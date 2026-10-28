<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Menu\Component\Menu\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Menu\FetchSingleMenuAction;
use App\Domain\Admin\Actions\Dashboard\Menu\Menu\UpdateMenuAction;
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


    public function mount(int $merchant_id)
    {
        $this->merchantId = $merchant_id;
    }
    public function updateMenu($id, FetchSingleMenuAction $fetchSingleMenuAction): void
    {
        $menu = $fetchSingleMenuAction->handle($id, $this->merchantId);
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
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'status' => trans('admin::home.status'),
        ];
    }

    public function update(UpdateMenuAction $updateMenuAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $menu = $updateMenuAction->handle($this->menu_id, $data);
        $this->emit('closeModal', '#updateMenu');
        $this->emit('selectAndSet', $menu->id);
        $this->emit('refreshMenus');
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.menu.component.menu.modal.update');
    }
}
