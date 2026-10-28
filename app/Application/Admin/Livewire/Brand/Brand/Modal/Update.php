<?php

namespace Application\Admin\Livewire\Brand\Brand\Modal;

use App\Domain\Admin\Actions\Dashboard\Brand\Brand\FetchSingleBrandAction;
use App\Domain\Admin\Actions\Dashboard\Brand\Brand\UpdateBrandAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public int|null $brand_id;
    public $type;
    public array $title = ['en' => null];

    protected $listeners = ['updateBrand'];

    public function updateBrand($id, FetchSingleBrandAction $fetchSingleBrandAction): void
    {

        $brand = $fetchSingleBrandAction->handle($id);
        if ($brand) {
            $this->title = $brand->getTranslations('title');
            $this->type = $brand->type;
            $this->brand_id = $brand->id;
            $this->emit('openModal', '#updateBrand');
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
            'type' => 'required',
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
        ];
    }

    public function store(UpdateBrandAction $updateBrandAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateBrandAction->handle($this->brand_id, $data);
        $this->emit('closeModal', '#updateBrand');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.brand.brand.modal.update');
    }
}
