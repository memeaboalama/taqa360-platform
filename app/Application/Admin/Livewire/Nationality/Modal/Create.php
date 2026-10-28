<?php

namespace Application\Admin\Livewire\Nationality\Modal;

use App\Domain\Admin\Actions\Dashboard\Nationality\StoreNationalityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
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
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreNationalityAction $storeNationalityAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeNationalityAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createNationality');
        $this->alert('success', 'Nationality Created Successfully');
        $this->reset('title','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.nationality.modal.create');
    }
}
