<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Choices\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\Choice\FetchSingleChoiceAction;
use App\Domain\Admin\Actions\Dashboard\Menu\Choice\UpdateChoiceAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';

    public array $title = ['en' => null];
    public $merchantId;

    protected $listeners = ['updateChoice'];

    public function mount(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }
    public function updateChoice($id, FetchSingleChoiceAction $fetchSingleChoiceAction): void
    {
        $category = $fetchSingleChoiceAction->handle($id, $this->merchantId);
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
            'title.ar' => trans('admin::home.name'),
            'title.en' => trans('admin::home.name'),
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

    public function store(UpdateChoiceAction $updateChoiceAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateChoiceAction->handle($this->category_id, $data);
        $this->emit('closeModal', '#updateChoice');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_updated_successfully'));

        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.merchant.tabs.choices.modal.update');
    }
}
