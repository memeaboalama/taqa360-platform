<?php

namespace Application\Admin\Livewire\Category\Merchant\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Merchant\FetchSingleMerchantCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Merchant\UpdateMerchantCategoryAction;
use Domain\Admin\Actions\Dashboard\Merchant\FetchMerchants;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public string $status = 'active';
    public int|null $merchant_category_id;
    public array $title = ['en' => null];
    public $priority, $merchant_id, $merchants;

    protected $listeners = ['updateMerchantCategory'];

    public function mount(FetchMerchants $fetchMerchants)
    {
        $this->merchants = $fetchMerchants->fetchCollection();
    }

    public function updateMerchantCategory($id, FetchSingleMerchantCategoryAction $fetchSingleMerchantCategoryAction): void
    {

        $merchantCategory = $fetchSingleMerchantCategoryAction->handle($id);
        if ($merchantCategory) {
            $this->title = $merchantCategory->getTranslations('title');
            $this->status = $merchantCategory->status->value;
            $this->merchant_category_id = $merchantCategory->id;
            $this->priority = $merchantCategory->priority;
            $this->merchant_id = $merchantCategory->merchant_id;
            $this->emit('openModal', '#updateMerchantCategory');
        }
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

    public function store(UpdateMerchantCategoryAction $updateMerchantCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateMerchantCategoryAction->handle($this->merchant_category_id, $data);
        $this->emit('closeModal', '#updateMerchantCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Merchant Category Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.merchant.modal.update');
    }
}
