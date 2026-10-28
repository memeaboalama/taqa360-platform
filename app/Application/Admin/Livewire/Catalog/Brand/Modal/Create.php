<?php

namespace Application\Admin\Livewire\Catalog\Brand\Modal;

use App\Domain\MarketPlace\Actions\Brand\StoreBrandAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $is_active = '1';
    public array $name = ['en' => null];
    public array $description = ['en' => null];
    public $image;
    public $imagePreview;
    public $tags;

    protected $listeners = ['openCreateBrandModal'];

    public function openCreateBrandModal(): void
    {
        $this->emit('openModal', '#createBrand');
    }

    public function nickNames()
    {
        return [
            'name.ar' => trans('admin::lang.Name') . ' (AR)',
            'name.en' => trans('admin::lang.Name') . ' (EN)',
            'description.ar' => trans('admin::lang.Description') . ' (AR)',
            'description.en' => trans('admin::lang.Description') . ' (EN)',
            'image' => trans('admin::lang.Image'),
            'tags' => trans('admin::lang.Tags'),
            'is_active' => trans('admin::lang.Active'),
        ];
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.*' => 'required|string|min:2|max:120',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|min:2|max:500',
            'is_active' => 'required|in:1,0,true,false',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tags' => 'nullable|string',
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

    public function store(StoreBrandAction $storeBrandAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());

        DB::beginTransaction();
        try {
            
            if (isset($data['tags']) && $data['tags']) {
                $data['tags'] = explode(',', $data['tags']);
            }

            // Convert boolean/string active status
            $data['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);

            $storeBrandAction->handle($data);
            
            $this->emit('refreshDatatable');
            $this->emit('closeModal', '#createBrand');
            $this->alert('success', __('admin::lang.Created Successfully'));
            $this->reset('name', 'description', 'is_active', 'image', 'imagePreview', 'tags');
            DB::commit();

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', __('admin::messages.something_went_wrong'));
        }
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.catalog.brand.modal.create');
    }
}
