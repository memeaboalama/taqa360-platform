<?php

namespace Application\Admin\Livewire\Country\Modal;

use App\Domain\Admin\Actions\Dashboard\Country\FetchSingleCountryAction;
use App\Domain\Admin\Actions\Dashboard\Country\UpdateCountryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public int|null $country_id;
    public $priority = "";
    public $iso = "";
    public array $title = ['en' => null];

    protected $listeners = ['updateCountry'];

    public function updateCountry($id, FetchSingleCountryAction $fetchSingleCountryAction): void
    {
        $country = $fetchSingleCountryAction->handle($id);
        if ($country) {
            $this->title = $country->getTranslations('title');
            $this->status = $country->status->value;
            $this->iso = $country->iso;
            $this->country_id = $country->id;
            $this->priority = $country->priority;
            $this->emit('openModal', '#updateCountry');
        }
    }

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

    public function store(UpdateCountryAction $updateCountryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateCountryAction->handle($this->country_id, $data);
        $this->emit('closeModal', '#updateCountry');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Country Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.country.modal.update');
    }
}