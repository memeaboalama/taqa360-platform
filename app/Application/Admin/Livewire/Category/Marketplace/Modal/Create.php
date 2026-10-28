<?php

namespace Application\Admin\Livewire\Category\Marketplace\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Main\StoreMainCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Marketplace\StoreMarketplaceCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $status = 'active';
    public array $title = ['en' => null];
    public $sort, $image, $color = '#136ad0';


    public function nickNames()
    {
        return [
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'sort' => trans('admin::home.sort'),
            'color' => trans('admin::home.color'),
        ];
    }

    protected function rules(): array
    {
        return [
            'color' => 'required',
            'sort' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title.en' => 'required|string|min:2|max:120',
            'title.ar' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreMarketplaceCategoryAction $storeMarketplaceCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $storeMarketplaceCategoryAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createCategory');
        $this->alert('success', __('admin::home.data_saved_successfully'));
        $this->reset('title', 'sort','status', 'image', 'color');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.marketplace.modal.create');
    }
}
