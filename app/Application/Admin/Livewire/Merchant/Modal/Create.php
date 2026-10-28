<?php

namespace Application\Admin\Livewire\Merchant\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Category\StoreCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public int $priority = 1;
    public array $title = ['en' => null,  'ar' => null];


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

    public function store(StoreCategoryAction $storeCategoryAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeCategoryAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCategory');
        $this->alert('success', 'Category Created Successfully');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.modal.create');
    }
}
