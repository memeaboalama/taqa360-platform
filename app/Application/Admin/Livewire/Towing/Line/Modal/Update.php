<?php

namespace Application\Admin\Livewire\Towing\Line\Modal;

use App\Domain\Admin\Actions\Dashboard\Towing\City\FetchCityAction;
use App\Domain\Admin\Actions\Dashboard\Towing\Line\FetchSingleLineAction;
use App\Domain\Admin\Actions\Dashboard\Towing\Line\UpdateLineAction;
use App\Domain\Admin\Actions\Dashboard\Towing\Type\FetchTypeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public int|null $line_id;
    public $from_towing_city_id, $to_towing_city_id, $towing_type_id, $price;
    public array $title = ['en' => null];

    protected $listeners = ['updateTowingLine'];

    public $towing_cities, $towing_types;

    public function mount(FetchCityAction $fetchCityAction, FetchTypeAction $fetchTypeAction)
    {
        $this->towing_cities = $fetchCityAction->handle();
        $this->towing_types = $fetchTypeAction->handle();
    }

    public function updateTowingLine($id, FetchSingleLineAction $fetchSingleLineAction): void
    {

        $line = $fetchSingleLineAction->handle($id);
        if ($line) {
            $this->title = $line->getTranslations('title');
            $this->from_towing_city_id = $line->from_towing_city_id;
            $this->to_towing_city_id = $line->to_towing_city_id;
            $this->towing_type_id = $line->towing_type_id;
            $this->price = $line->price;
            $this->line_id = $line->id;
            $this->emit('openModal', '#updateTowingLine');
        }
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

    public function store(UpdateLineAction $updateLineAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateLineAction->handle($this->line_id, $data);
        $this->emit('closeModal', '#updateTowingLine');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Towing Line Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.towing.line.modal.update');
    }
}
