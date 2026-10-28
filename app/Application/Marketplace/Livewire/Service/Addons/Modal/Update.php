<?php

namespace Application\Marketplace\Livewire\Service\Addons\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Service\Addon\UpdateAddonCategoryAction;
use Domain\MarketPlace\Actions\Dashboard\Service\Addon\FetchSingleAddonCategoryAction;
use Domain\MarketPlace\Models\MerchantAddonCategory;
use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;
    public int|null $service_id;
    public MerchantAddonCategory $addon_category_edit;
    public $addons;
    public $title;
    public $status;
    public $addon_category_edit_id;

    protected $listeners = ['updateAddonCategory'];

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.service.addons.modal.update');
    }

    public function mount(MerchantService $service)
    {
        $this->service_id = $service->id;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required',
            'status' => 'required',
            'addons.*.title' => 'required',
            'addons.*.status' => 'required',
            'addons.*.price' => 'required',
            'addons.*.working_minutes' => 'required',
        ];
    }

    public function updateAddonCategory($id, FetchSingleAddonCategoryAction $fetchSingleAddonCategoryAction): void
    {
        $addon_category_edit = $fetchSingleAddonCategoryAction->handle($id);
        if ($addon_category_edit) {
            $this->addon_category_edit = $addon_category_edit;
            $this->title = $addon_category_edit->title;
            $this->status = $addon_category_edit->status;
            $this->addon_category_edit_id = $addon_category_edit->id;
            $this->addons = new Collection();
            $this->populateAddons();
            $this->emit('openModal', '#updateAddon');
        }
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function update(UpdateAddonCategoryAction $updateAddonCategoryAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $addonCategory = $updateAddonCategoryAction->handle($this->addon_category_edit_id, $data);
        $this->emit('closeModal', '#updateAddon');
        $this->emit( 'refresh_addons', $addonCategory->service);
        $this->emit('refreshDatatable');
        $this->alert('success', 'Addon Category Updated Successfully');
        DB::commit();
    }

    public function addItem($data = null)
    {
        if ($data == null) {
            $data = [
                'id' => null,
                'title' => '',
                'status' => '',
                'price' => '0.00',
                'working_minutes' => '0',
            ];
        }
        $this->addons->add($data);
    }

    public function removeItem($key)
    {
        $this->addons->forget($key);
    }

    protected function populateAddons()
    {
        if ($this->addon_category_edit->addons()->count() > 0) {
            foreach ($this->addon_category_edit->addons as $item) {
                $this->addItem([
                    'id' => $item->id,
                    'title' => $item->title,
                    'status' => $item->status,
                    'price' => $item->price,
                    'working_minutes' => $item->working_minutes,
                ]);
            }
        } else {
            $this->addItem();
        }
    }
}
