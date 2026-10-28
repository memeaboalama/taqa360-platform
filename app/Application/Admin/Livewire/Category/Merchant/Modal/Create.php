<?php

namespace Application\Admin\Livewire\Category\Merchant\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Merchant\StoreMerchantCategoryAction;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public array $title = ['en' => null];
    public $priority, $merchant_id, $merchants;

    public function mount(FetchMerchants $fetchMerchants)
    {
        $this->merchants = $fetchMerchants->fetchCollection();
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'merchant_id' => 'merchant',
            'title.en' => 'title'
        ];
    }
    protected function rules(): array
    {
        return [
            'merchant_id' => 'required',
            'priority' => 'required',
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreMerchantCategoryAction $storeMerchantCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeMerchantCategoryAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createMerchantCategory');
        $this->alert('success', 'Merchant Category Created Successfully');
        $this->reset('title','merchant_id', 'priority','status');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.merchant.modal.create');
    }
}
