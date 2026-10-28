<?php

namespace Application\Admin\Livewire\Catalog\ChildCategory\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory\FetchSingleChildCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory\UpdateChildCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory\FetchSubcategoryAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $is_active = '1';
    public $child_category_id = null;
    public $subcategory_id, $subcategories;
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $slug;
    public $image;
    public $imagePreview;
    public $currentImage;

    public function mount(FetchSubcategoryAction $fetchSubcategoriesAction)
    {
        $this->subcategories = $fetchSubcategoriesAction->handle();
    }

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'title.en' => 'title',
            'description.ar' => trans('admin:lang.fields.description'),
            'description.en' => 'description',
            'slug' => 'slug',
            'subcategory_id' => 'subcategory',
            'image' => 'image'
        ];
    }

    protected function rules(): array
    {
        $childCategoryId = $this->child_category_id ?? 0;

        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:2|max:120',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|min:2|max:500',
            'slug' => 'nullable|string|min:2|max:120|unique:child_categories,slug,' . $childCategoryId,
            'subcategory_id' => 'required|exists:sub_categories,id',
            'is_active' => 'required|string|in:1,0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    protected $listeners = ['updateChildCategory'];

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

    public function updateChildCategory($id, FetchSingleChildCategoryAction $fetchSingleChildCategoryAction): void
    {
        $childCategory = $fetchSingleChildCategoryAction->handle($id);
        if ($childCategory) {
            $this->title = $childCategory->getTranslations('title');
            $this->description = $childCategory->getTranslations('description');
            $this->is_active = $childCategory->is_active;
            $this->child_category_id = $childCategory->id;
            $this->subcategory_id = $childCategory->subcategory_id;
            $this->slug = $childCategory->slug;
            $this->currentImage = $childCategory->image;
            $this->emit('openModal', '#updateChildCategory');
        }
    }

    public function updateStatus($val): void
    {
        $this->is_active = $val;
    }

    public function store(UpdateChildCategoryAction $updateChildCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        try {
            $updateChildCategoryAction->handle($this->child_category_id, $data);
            $this->emit('closeModal', '#updateChildCategory');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::catalog.messages.child_category_updated'));
            $this->reset('title', 'description', 'slug', 'subcategory_id', 'is_active', 'image', 'imagePreview', 'currentImage');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.childcategory.modal.update');
    }
}
