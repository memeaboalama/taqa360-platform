<?php

namespace Application\Admin\Livewire\Brand\Model\Modal;

use App\Domain\Admin\Actions\Dashboard\Brand\Brand\FetchBrandAction;
use App\Domain\Admin\Actions\Dashboard\Brand\Model\StoreModelAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public array $title = ['en' => null];
    public $brand_id, $brands;

    public function mount(FetchBrandAction $fetchBrandAction)
    {
        $this->brands = $fetchBrandAction->handle();
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

    public function store(StoreModelAction $storeModelAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeModelAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createModel');
        $this->alert('success', 'Model Created Successfully');
        $this->reset('brand_id', 'title');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.brand.model.modal.create');
    }
}
