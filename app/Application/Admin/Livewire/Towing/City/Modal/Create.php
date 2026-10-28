<?php

namespace Application\Admin\Livewire\Towing\City\Modal;

use App\Domain\Admin\Actions\Dashboard\Towing\City\StoreCityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $can_pickup = 'active';
    public string $can_dropoff = 'active';
    public $priority = "";
    public array $title = ['en' => null];

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

    public function store(StoreCityAction $storeCityAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeCityAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createTowingCity');
        $this->alert('success', 'Towing City Created Successfully');
        $this->reset('priority', 'title','can_pickup', 'can_dropoff');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.towing.city.modal.create');
    }
}
