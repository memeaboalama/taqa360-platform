<?php

namespace Application\Admin\Livewire\Region\Modal;

use App\Domain\Admin\Actions\Dashboard\Region\FetchSingleRegionAction;
use App\Domain\Admin\Actions\Dashboard\Region\UpdateRegionAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public int|null $region_id;
    public $priority = "";
    public $city_id, $cities;
    public array $title = ['en' => null];

    public function mount(FetchCitiesAction $fetchCitiesAction)
    {
        $this->cities = $fetchCitiesAction->handle();
    }

    protected $listeners = ['updateRegion'];

    public function updateRegion($id, FetchSingleRegionAction $fetchSingleRegionAction): void
    {
        $region = $fetchSingleRegionAction->handle($id);
        if ($region) {
            $this->title = $region->getTranslations('title');
            $this->status = $region->status->value;
            $this->region_id = $region->id;
            $this->priority = $region->priority;
            $this->city_id = $region->city()->first()?->id;
            $this->emit('openModal', '#updateRegion');
        }
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'city_id' => 'city',
            'title.en' => 'title'
        ];
    }



    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'priority' => 'required',
            'city_id' => 'required',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateRegionAction $updateRegionAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateRegionAction->handle($this->region_id, $data);
        $this->emit('closeModal', '#updateRegion');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Region Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.region.modal.update');
    }
}