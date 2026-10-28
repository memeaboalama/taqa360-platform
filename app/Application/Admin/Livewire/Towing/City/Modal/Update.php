<?php

namespace Application\Admin\Livewire\Towing\City\Modal;

use App\Domain\Admin\Actions\Dashboard\Towing\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\Towing\City\UpdateCityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $can_pickup = 'active';
    public string $can_dropoff = 'active';
    public $priority = "";
    public int|null $city_id;
    public array $title = ['en' => null];

    protected $listeners = ['updateTowingCity'];

    public function updateTowingCity($id, FetchSingleCityAction $fetchSingleCityAction): void
    {

        $city = $fetchSingleCityAction->handle($id);
        if ($city) {
            $this->title = $city->getTranslations('title');
            $this->can_dropoff = $city->can_dropoff->value;
            $this->can_pickup = $city->can_pickup->value;
            $this->priority = $city->priority;
            $this->city_id = $city->id;
            $this->emit('openModal', '#updateTowingCity');
        }
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'title.en' => 'title'
        ];
    }

    protected function rules(): array
    {
        return [
            'priority' => 'required',
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'can_pickup' => 'required|string|in:active,inactive',
            'can_dropoff' => 'required|string|in:active,inactive',
        ];
    }

    public function updatePickup($val): void
    {
        $this->can_pickup = $val;
    }

    public function updateDropoff($val): void
    {
        $this->can_dropoff = $val;
    }

    public function store(UpdateCityAction $updateCityAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateCityAction->handle($this->city_id, $data);
        $this->emit('closeModal', '#updateTowingCity');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Towing City Updated Successfully');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.towing.city.modal.update');
    }
}
