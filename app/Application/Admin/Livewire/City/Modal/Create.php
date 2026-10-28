<?php

namespace Application\Admin\Livewire\City\Modal;

use App\Domain\Admin\Actions\Dashboard\City\StoreCityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
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
            'title' => 'required|array',
            'priority' => 'required',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreCityAction $storeCityAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeCityAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCity');
        $this->alert('success', __('admin::lang.City Created Successfully'));
        $this->reset('title', 'priority','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.city.modal.create');
    }
}
