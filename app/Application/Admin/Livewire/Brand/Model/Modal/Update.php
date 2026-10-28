<?php

namespace Application\Admin\Livewire\Brand\Model\Modal;

use App\Domain\Admin\Actions\Dashboard\Brand\Brand\FetchBrandAction;
use App\Domain\Admin\Actions\Dashboard\Brand\Model\FetchSingleModelAction;
use App\Domain\Admin\Actions\Dashboard\Brand\Model\UpdateModelAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public int|null $model_id;
    public $brand_id;
    public array $title = ['en' => null];

    protected $listeners = ['updateModel'];

    public $brands;

    public function mount(FetchBrandAction $fetchBrandAction)
    {
        $this->brands = $fetchBrandAction->handle();
    }

    public function updateModel($id, FetchSingleModelAction $fetchSingleModelAction): void
    {

        $model = $fetchSingleModelAction->handle($id);
        if ($model) {
            $this->title = $model->getTranslations('title');
            $this->brand_id = $model->brand_id;
            $this->model_id = $model->id;
            $this->emit('openModal', '#updateModel');
        }
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'brand_id' => 'brand',
            'title.en' => 'title'
        ];
    }


    protected function rules(): array
    {
        return [
            'brand_id' => 'required',
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
        ];
    }

    public function store(UpdateModelAction $updateModelAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateModelAction->handle($this->model_id, $data);
        $this->emit('closeModal', '#updateModel');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Model Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.brand.model.modal.update');
    }
}
