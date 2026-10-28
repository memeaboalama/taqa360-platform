<?php

namespace Application\Admin\Livewire\Towing\Line\Modal;

use App\Domain\Admin\Actions\Dashboard\Towing\City\FetchCityAction;
use App\Domain\Admin\Actions\Dashboard\Towing\Line\StoreLineAction;
use App\Domain\Admin\Actions\Dashboard\Towing\Type\FetchTypeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public array $title = ['en' => null];
    public $from_towing_city_id, $to_towing_city_id, $towing_type_id, $price;
    public $towing_cities, $towing_types;

    public function mount(FetchCityAction $fetchCityAction, FetchTypeAction $fetchTypeAction)
    {
        $this->towing_cities = $fetchCityAction->handle();
        $this->towing_types = $fetchTypeAction->handle();
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'from_towing_city_id' => 'from towing city',
            'to_towing_city_id' => 'to towing city',
            'towing_type_id' => 'towing type',
            'title.en' => 'title'
        ];
    }
    protected function rules(): array
    {
        return [
            'from_towing_city_id' => 'required',
            'to_towing_city_id' => 'required',
            'towing_type_id' => 'required',
            'price' => 'required',
            'title' => 'sometimes|array',
            'title.*' => 'sometimes|string|min:2|max:120',
        ];
    }

    public function store(StoreLineAction $storeLineAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeLineAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createTowingLine');
        $this->alert('success', 'Towing Line Created Successfully');
        $this->reset('from_towing_city_id', 'to_towing_city_id','towing_type_id', 'price', 'title');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.towing.line.modal.create');
    }
}
