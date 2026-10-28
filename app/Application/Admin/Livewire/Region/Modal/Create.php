<?php

namespace Application\Admin\Livewire\Region\Modal;

use App\Domain\Admin\Actions\Dashboard\Region\StoreRegionAction;
use App\Domain\AppBase\Actions\Location\FetchCitiesAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public $priority = "";
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

    public function store(StoreRegionAction $storeRegionAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeRegionAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createRegion');
        $this->alert('success', __('admin::lang.Region Created Successfully'));
        $this->reset('title', 'priority', 'status', 'city_id');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.region.modal.create');
    }
}