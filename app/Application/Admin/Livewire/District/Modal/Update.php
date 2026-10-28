<?php

namespace Application\Admin\Livewire\District\Modal;

use App\Domain\Admin\Actions\Dashboard\District\FetchSingleDistrictAction;
use App\Domain\Admin\Actions\Dashboard\District\UpdateDistrictAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public string $status = 'active';
    public int|null $district_id;
    public $city_id, $cities;
    public array $title = ['en' => null];

    public function mount(FetchCitiesAction $fetchCitiesAction)
    {
        $this->cities = $fetchCitiesAction->handle();
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
            'city_id' => 'required',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    protected $listeners = ['updateDistrict'];

    public function updateDistrict($id, FetchSingleDistrictAction $fetchSingleDistrictAction): void
    {

        $district = $fetchSingleDistrictAction->handle($id);
        if ($district) {
            $this->title = $district->getTranslations('title');
            $this->status = $district->status->value;
            $this->district_id = $district->id;
            $this->city_id = $district->city_id;
            $this->emit('openModal', '#updateDistrict');
        }
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateDistrictAction $updateDistrictAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateDistrictAction->handle($this->district_id, $data);
        $this->emit('closeModal', '#updateDistrict');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.District Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.district.modal.update');
    }
}
