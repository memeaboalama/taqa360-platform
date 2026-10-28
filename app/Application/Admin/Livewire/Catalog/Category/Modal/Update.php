<?php

namespace Application\Admin\Livewire\Catalog\Category\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchSingleCategoryAction;
use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\UpdateCategoryAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $is_active = '1';
    public int|null $category_id;
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $slug;
    public $image;
    public $imagePreview;
    public $currentImage;

    protected $listeners = ['updateCatalogCategory'];

    public function nickNames()
    {
        return [
            'title.ar' => trans('admin:lang.fields.title'),
            'title.en' => 'title',
            'description.ar' => trans('admin:lang.fields.description'),
            'description.en' => 'description',
            'slug' => 'slug',
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
            'slug' => 'nullable|string|min:2|max:120|unique:categories,slug,' . $this->category_id,
            'is_active' => 'required|string|in:1,0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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

    public function removeCurrentImage()
    {
        $this->currentImage = null;
        $this->image = null;
        $this->imagePreview = null;
    }

    public function updateCatalogCategory($id, FetchSingleCategoryAction $fetchSingleCategoryAction): void
    {
        $category = $fetchSingleCategoryAction->handle($id);
        if ($category) {
            Log::info("Loading category data", [$category->title]);
            $this->title = $category->getTranslations('title');
            $this->description = $category->getTranslations('description');
            $this->is_active = $category->is_active;
            $this->category_id = $category->id;
            $this->slug = $category->slug;
            $this->currentImage = $category->image;
        }
        $this->emit('openModal', '#updateCatalogCategory');
    }

    public function updateStatus($val): void
    {
        $this->is_active = $val;
    }

    public function store(UpdateCategoryAction $updateCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $updateCategoryAction->handle($this->category_id, $data);
            $this->emit('closeModal', '#updateCatalogCategory');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::catalog.messages.category_updated'));
            $this->reset('title', 'description', 'slug', 'is_active', 'image', 'imagePreview', 'currentImage');
            DB::commit();

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating category: ' . $e->getMessage());
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.category.modal.update');
    }
}
