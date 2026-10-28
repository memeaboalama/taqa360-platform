<?php

namespace Application\Admin\Livewire\Settings\Tabs;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\GeneralSetting\UpdateGeneralSettingAction;
use App\Domain\AppBase\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Capacity extends Component
{
    use LivewireAlert;
    public $restaurant_search_capacity, $driver_search_capacity;
    public function mount(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $this->fillData($fetchGeneralSettingsAction->handle(['restaurant_search_capacity', 'driver_search_capacity']));
    }

    protected function rules(): array
    {
        return [
            'restaurant_search_capacity' => 'required|numeric|max:300',
            'driver_search_capacity' => 'required|numeric',
        ];
    }

    public function nickNames()
    {
        return [
            'restaurant_search_capacity' => trans('admin::home.Customer search capacity for a restaurant'),
            'driver_search_capacity' => trans('admin::home.Restaurant search capacity for a driver'),
        ];
    }

    public function updateSearchCapacity(UpdateGeneralSettingAction $updateGeneralSettingAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $settings = $updateGeneralSettingAction->handle($data);
        $this->fillData($settings);
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    protected function fillData($settings): void
    {
        $this->restaurant_search_capacity = $settings?->restaurant_search_capacity;
        $this->driver_search_capacity = $settings?->driver_search_capacity;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.settings.tabs.capacity');
    }
}
