<?php

namespace Application\Admin\Livewire\Nationality\Modal;

use App\Domain\Admin\Actions\Dashboard\Nationality\FetchSingleNationalityAction;
use App\Domain\Admin\Actions\Dashboard\Nationality\UpdateNationalityAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public string $status = 'active';
    public int|null $nationality_id;
    public array $title = ['en' => null];

    protected $listeners = ['updateNationality'];

    public function updateNationality($id, FetchSingleNationalityAction $fetchSingleNationalityAction): void
    {

        $nationality = $fetchSingleNationalityAction->handle($id);
        if ($nationality) {
            $this->title = $nationality->getTranslations('title');
            $this->status = $nationality->status->value;
            $this->nationality_id = $nationality->id;
            $this->emit('openModal', '#updateNationality');
        }
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

    public function store(UpdateNationalityAction $updateNationalityAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateNationalityAction->handle($this->nationality_id, $data);
        $this->emit('closeModal', '#updateNationality');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Nationality Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.nationality.modal.update');
    }
}
