<?php

namespace Application\Admin\Livewire\Category\Marketplace\Modal;

use App\Domain\Admin\Actions\Dashboard\Category\Marketplace\FetchSingleMarketplaceCategoryAction;
use App\Domain\Admin\Actions\Dashboard\Category\Marketplace\UpdateMarketplaceCategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{

    use LivewireAlert, WithFileUploads;

    public string $status = 'active';
    public int|null $category_id;
    public array $title = ['en' => null];
    public $sort, $image, $image_path, $color = '#136ad0';

    protected $listeners = ['updateMarketplaceCategory'];

    public function updateMarketplaceCategory($id, FetchSingleMarketplaceCategoryAction $fetchSingleMarketplaceCategoryAction): void
    {

        $marketplaceCategory = $fetchSingleMarketplaceCategoryAction->handle($id);
        if ($marketplaceCategory) {
            $this->title = $marketplaceCategory->getTranslations('title');
            $this->status = $marketplaceCategory->status;
            $this->category_id = $marketplaceCategory->id;
            $this->sort = $marketplaceCategory->sort;
            $this->image_path = $marketplaceCategory->image_path;
            $this->color = $marketplaceCategory->color;
            $this->emit('openModal', '#updateMarketplaceCategory');
        }
    }


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
            'sort' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => 'required',
            'title.en' => 'required|string|min:2|max:120',
            'title.ar' => 'required|string|min:2|max:120',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateMarketplaceCategoryAction $updateMarketplaceCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateMarketplaceCategoryAction->handle($this->category_id, $data);
        $this->emit('closeModal', '#updateMarketplaceCategory');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.category.marketplace.modal.update');
    }
}

