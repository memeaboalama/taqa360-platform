<?php

namespace Application\Admin\Livewire\District\Modal;

use App\Domain\Admin\Actions\Dashboard\District\StoreDistrictAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
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

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreDistrictAction $storeDistrictAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeDistrictAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createDistrict');
        $this->alert('success', __('admin::lang.District Created Successfully'));
        $this->reset('title', 'city_id','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.district.modal.create');
    }
}
