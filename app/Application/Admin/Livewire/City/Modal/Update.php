<?php

namespace Application\Admin\Livewire\City\Modal;

use App\Domain\Admin\Actions\Dashboard\City\FetchSingleCityAction;
use App\Domain\Admin\Actions\Dashboard\City\UpdateCityAction;
use App\Domain\AppBase\Models\Location\Region;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public int|null $city_id;
    public $priority = "";
    public $region_id = '';
    public $regions;
    public array $title = ['en' => null];

    public function mount(): void
    {
        $this->regions = Region::query()->get(['id', 'title']);
    }

    protected $listeners = ['updateCity'];

    public function updateCity($id, FetchSingleCityAction $fetchSingleCityAction): void
    {

        $city = $fetchSingleCityAction->handle($id);
        if ($city) {
            $this->title = $city->getTranslations('title');
            $this->status = $city->status->value;
            $this->city_id = $city->id;
            $this->priority = $city->priority;
            $this->region_id = $city->region_id;
            $this->emit('openModal', '#updateCity');
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
            'title' => 'required|array',
            'priority' => 'required',
            'region_id' => 'required|integer|exists:regions,id',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateCityAction $updateCityAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateCityAction->handle($this->city_id, $data);
        $this->emit('closeModal', '#updateCity');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.City Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.city.modal.update');
    }
}
