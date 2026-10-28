<?php

namespace Application\Marketplace\Livewire\Choice\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Domain\MarketPlace\Actions\Dashboard\Choice\StoreChoiceAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\StoreCategoryAction;

class Create extends Component
{
    use LivewireAlert;

    public  $status = 'active';
    public  $title = ['en' => null,  'ar' => null];


    protected function rules(): array
    {
        return [
            'title.en' => ['required'],
            'title.ar' => ['required'],
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('marketplace::home.name'),
            'title.en' => trans('marketplace::home.name'),
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreChoiceAction $action): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $action->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createChoice');
        $this->alert('success',__('marketplace::home.data_saved_successfully'));
        $this->reset('title','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.choice.modal.create');
    }
}
