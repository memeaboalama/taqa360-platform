<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Choices\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Choice\StoreChoiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public  $status = 'active';
    public  $title = ['en' => null,  'ar' => null];
    public $merchantId;
    public function mount(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }

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
            'title.ar' => trans('admin::home.name'),
            'title.en' => trans('admin::home.name'),
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreChoiceAction $storeChoiceAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $data['merchant_id'] = $this->merchantId;
        $storeChoiceAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createChoice');
        $this->alert('success',__('admin::home.data_saved_successfully'));
        $this->reset('title','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.choices.modal.create');
    }
}
