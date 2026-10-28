<?php

namespace Application\Admin\Livewire\Catalog\Subcategory\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory\FetchSingleSubcategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory\UpdateSubcategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $is_active = '1';
    public $subcategory_id = null;
    public $category_id, $categories;
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $slug;
    public $image;
    public $imagePreview;
    public $currentImage;

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
        $subcategoryId = $this->subcategory_id ?? 0;

        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|min:2|max:500',
            'slug' => 'nullable|string|min:2|max:120|unique:sub_categories,slug,' . $subcategoryId,
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'required|string|in:1,0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    protected $listeners = ['updateSubcategory'];

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

    public function removeCurrentImage()
    {
        $this->currentImage = null;
        $this->image = null;
        $this->imagePreview = null;
    }

    public function updateSubcategory($id, FetchSingleSubcategoryAction $fetchSingleSubcategoryAction): void
    {
        $subcategory = $fetchSingleSubcategoryAction->handle($id);
        if ($subcategory) {
            $this->title = $subcategory->getTranslations('title');
            $this->description = $subcategory->getTranslations('description');
            $this->is_active = $subcategory->is_active;
            $this->subcategory_id = $subcategory->id;
            $this->category_id = $subcategory->category_id;
            $this->slug = $subcategory->slug;
            $this->currentImage = $subcategory->image;
            $this->emit('openModal', '#updateSubcategory');
        }
    }

    public function updateStatus($val): void
    {
        $this->is_active = $val;
    }

    public function store(UpdateSubcategoryAction $updateSubcategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        try {
            $updateSubcategoryAction->handle($this->subcategory_id, $data);
            $this->emit('closeModal', '#updateSubcategory');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::catalog.messages.subcategory_updated'));
            $this->reset('title', 'description', 'slug', 'category_id', 'is_active', 'image', 'imagePreview', 'currentImage');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.subcategory.modal.update');
    }
}
