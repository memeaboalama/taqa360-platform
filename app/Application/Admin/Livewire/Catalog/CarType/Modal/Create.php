<?php

namespace Application\Admin\Livewire\Catalog\CarType\Modal;

use App\Domain\AppBase\Actions\Car\StoreCarTypeAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public array $title = ['en' => null];
    public $key;
    public $elm_id;
    public string $is_active = '1';

    protected $listeners = ['openCreateCarTypeModal'];

    public function openCreateCarTypeModal(): void
    {
        $this->emit('openModal', '#createCarType');
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
            'elm_id' => 'nullable|string|max:255|unique:cars_types,elm_id',
            'is_active' => 'required|in:1,0,true,false',
        ];
    }

    public function updateStatus($val): void
    {
        $this->is_active = $val;
    }

    public function store(StoreCarTypeAction $storeCarTypeAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $data['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
            $storeCarTypeAction->handle($data);

            $this->emit('refreshDatatable');
            $this->emit('closeModal', '#createCarType');
            $this->alert('success', __('admin::lang.Created Successfully'));
            $this->reset('title', 'key', 'elm_id', 'is_active');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.car-type.modal.create');
    }
}
