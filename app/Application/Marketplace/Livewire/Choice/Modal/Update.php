<?php

namespace Application\Marketplace\Livewire\Choice\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\MarketPlace\Actions\Dashboard\Choice\UpdateChoiceAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\UpdateCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Choice\FetchSingleChoiceAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchSingleCategoryAction;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';

    public array $title = ['en' => null];
    public $merchantId;

    protected $listeners = ['updateChoice'];

    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
    }
    public function updateChoice($id, FetchSingleChoiceAction $fetchSingleCategoryAction): void
    {
        $category = $fetchSingleCategoryAction->handle($id, $this->merchantId);
        if ($category) {
            $this->title = $category->getTranslations('title');
            $this->status = $category->status;
            $this->category_id = $category->id;
            $this->emit('openModal', '#updateChoice');
        }
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('marketplace::home.name'),
            'title.en' => trans('marketplace::home.name'),
        ];
    }


    protected function rules(): array
    {
        return [
            'title.en' => ['required'],
            'title.ar' => ['required'],
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateChoiceAction $updateCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateCategoryAction->handle($this->category_id, $data);
        $this->emit('closeModal', '#updateChoice');
        $this->emit('refreshDatatable');
        $this->reset( 'title', 'status');
        $this->alert('success', __('marketplace::home.data_updated_successfully'));

        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.choice.modal.update');
    }
}
