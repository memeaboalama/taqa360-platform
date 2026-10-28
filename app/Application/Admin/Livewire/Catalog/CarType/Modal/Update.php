<?php

namespace Application\Admin\Livewire\Catalog\CarType\Modal;

use App\Domain\AppBase\Actions\Car\FetchSingleCarTypeAction;
use App\Domain\AppBase\Actions\Car\UpdateCarTypeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public int|null $car_type_id = null;
    public array $title = ['en' => null];
    public $key;
    public $elm_id;
    public string $is_active = '1';

    protected $listeners = ['openUpdateCarTypeModal'];

    public function openUpdateCarTypeModal($id, FetchSingleCarTypeAction $fetchSingleCarTypeAction): void
    {
        $carType = $fetchSingleCarTypeAction->handle($id);

        if ($carType) {
            $this->car_type_id = $carType->id;
            $this->title = $carType->getTranslations('title');
            $this->key = $carType->key;
            $this->elm_id = $carType->elm_id;
            $this->is_active = $carType->is_active ? '1' : '0';
            $this->emit('openModal', '#updateCarType');
        }
    }

    public function nickNames(): array
    {
        return [
            'title.ar' => trans('admin::lang.Title') . ' (AR)',
            'title.en' => trans('admin::lang.Title') . ' (EN)',
            'key' => trans('admin::lang.Key'),
            'elm_id' => trans('admin::lang.Elm Id'),
            'is_active' => trans('admin::lang.Active'),
        ];
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'key' => 'nullable|string|max:255',
            'elm_id' => 'nullable|string|max:255|unique:cars_types,elm_id,' . $this->car_type_id,
            'is_active' => 'required|in:1,0,true,false',
        ];
    }

    public function updateStatus($val): void
    {
        $this->is_active = $val;
    }

    public function store(UpdateCarTypeAction $updateCarTypeAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $data['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
            $updateCarTypeAction->handle($this->car_type_id, $data);

            $this->emit('closeModal', '#updateCarType');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::lang.Updated Successfully'));
            $this->reset('title', 'key', 'elm_id', 'car_type_id', 'is_active');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.car-type.modal.update');
    }
}
