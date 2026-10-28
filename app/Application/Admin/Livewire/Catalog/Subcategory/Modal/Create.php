<?php

namespace Application\Admin\Livewire\Catalog\Subcategory\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory\StoreSubcategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $is_active = '1';
    public $category_id, $categories;
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $slug;
    public $image;
    public $imagePreview;

    public function mount(FetchCategoryAction $fetchCategoriesAction)
    {
        $this->categories = $fetchCategoriesAction->handle();
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'title.en' => 'title',
            'description.ar' => trans('admin:lang.fields.description'),
            'description.en' => 'description',
            'slug' => 'slug',
            'category_id' => 'category',
            'image' => 'image'
        ];
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|min:2|max:500',
            'slug' => 'nullable|string|unique:sub_categories,slug|min:2|max:120',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|string|in:1,0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $this->imagePreview = $this->image->temporaryUrl();
    }

    public function removeImage()
    {
        $this->image = null;
        $this->imagePreview = null;
    }

    public function updateStatus($val): void
    {
        $this->is_active = $val;
    }

    public function store(StoreSubcategoryAction $storeSubcategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        try {
            $storeSubcategoryAction->handle($data);
            $this->emit('refreshDatatable');
            $this->emit('closeModal', '#createSubcategory');
            $this->alert('success', __('admin::catalog.messages.subcategory_created'));
            $this->reset('title', 'description', 'slug', 'category_id', 'is_active', 'image', 'imagePreview');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.subcategory.modal.create');
    }
}
