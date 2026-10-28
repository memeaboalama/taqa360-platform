<?php

namespace Application\Marketplace\Livewire\Service\Addons\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Service\Addon\StoreAddonsCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public int|null $service_id;

    public $inputs = [];
    public $increamentInputs = 1;
    public $title;
    public $status;
    public $addon_title;
    public $addon_status;
    public $addon_price;
    public $addon_working_minutes;


    public function mount($service)
    {
        $this->service_id = $service->id;
    }

    protected function rules(): array
    {
        return [
            'service_id' => 'required|exists:merchant_services,id',
            'title' => 'required',
            'status' => 'required',
            'addon_title' => 'required|array',
            'addon_title.*' => 'required|string|min:2|max:120',
            'addon_status' => 'required|array',
            'addon_status.*' => 'required|string|in:active,inactive',
            'addon_price' => 'required|array',
            'addon_price.*' => 'required',
            'addon_working_minutes' => 'required|array',
            'addon_working_minutes.*' => 'required',
        ];
    }

    public function add($increamentInputs)
    {
        $increamentInputs = $increamentInputs + 1;
        $this->increamentInputs = $increamentInputs;
        array_push($this->inputs, $increamentInputs);
    }

    public function remove($increamentInputs)
    {
        unset($this->inputs[$increamentInputs]);
    }

    public function store(StoreAddonsCategoryAction $storeAddonsServiceAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $service = $storeAddonsServiceAction->handle($data);
        $this->emit( 'refresh_addons', $service);
        $this->emit('closeModal', '#createAddons');
        $this->alert('success', 'Addon Category Added Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.service.addons.modal.create');
    }
}
