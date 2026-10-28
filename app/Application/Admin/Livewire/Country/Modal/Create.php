<?php

namespace Application\Admin\Livewire\Country\Modal;

use App\Domain\Admin\Actions\Dashboard\Country\StoreCountryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public $priority = "";
    public $iso = "";
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
            'title' => 'required|array',
            'priority' => 'required',
            'iso' => 'required|string|max:3',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreCountryAction $storeCountryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeCountryAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCountry');
        $this->alert('success', __('admin::lang.Country Created Successfully'));
        $this->reset('title', 'priority', 'iso', 'status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.country.modal.create');
    }
}