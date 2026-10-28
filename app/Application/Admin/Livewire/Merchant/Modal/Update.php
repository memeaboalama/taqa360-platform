<?php

namespace Application\Admin\Livewire\Merchant\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Category\FetchSingleNationalityAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\StoreNationalityAction;
use App\Domain\MarketPlace\Actions\Dashboard\Category\UpdateCategoryAction;
use DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{

    use LivewireAlert;

    public string $status = 'active';
    public int $priority = 1;
    public int|null $category_id;
    public array $title = ['en' => null];

    protected $listeners = ['updateCategory'];

    public function updateCategory($id, FetchSingleNationalityAction $fetchSingleCategoryAction): void
    {

        $category = $fetchSingleCategoryAction->handle($id, auth('merchant')->user()->merchant_id);
        if ($category) {
            $this->title = $category->getTranslations('title');
            $this->priority = $category->priority;
            $this->status = $category->status;
            $this->category_id = $category->id;
            $this->emit('openModal', '#updateCategory');
        }
    }


    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'priority' => 'required|int|min:1',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateCategoryAction $updateCategoryAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateCategoryAction->handle($this->category_id, $data);
        $this->emit('closeModal', '#updateCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', 'Category Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.modal.update');
    }
}
