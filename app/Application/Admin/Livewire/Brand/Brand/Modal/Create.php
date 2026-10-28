<?php

namespace Application\Admin\Livewire\Brand\Brand\Modal;

use App\Domain\Admin\Actions\Dashboard\Brand\Brand\StoreBrandAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public array $title = ['en' => null];
    public $type;

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

    public function store(StoreBrandAction $storeBrandAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeBrandAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createBrand');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset('type', 'title');
        DB::commit();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.brand.brand.modal.create');
    }
}
