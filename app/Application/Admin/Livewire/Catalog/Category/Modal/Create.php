<?php

namespace Application\Admin\Livewire\Catalog\Category\Modal;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\StoreCategoryAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $is_active = '1';
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $slug;
    public $image;
    public $imagePreview;

    protected $listeners = ['createCatalogCategory'];

    public function createCatalogCategory(): void
    {
        $this->emit('openModal', '#createCatalogCategory');
    }

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
            'slug' => 'nullable|string|unique:categories,slug|min:2|max:120',
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

    public function store(StoreCategoryAction $storeCategoryAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            $storeCategoryAction->handle($data);
            $this->emit('refreshDatatable');
            $this->emit('closeModal', '#createCatalogCategory');
            $this->alert('success', __('admin::catalog.messages.category_created'));
            $this->reset('title', 'description', 'slug', 'is_active', 'image', 'imagePreview');
            DB::commit();

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::main.messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.category.modal.create');
    }
}
