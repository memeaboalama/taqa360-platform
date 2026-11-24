<?php

namespace Application\Admin\Livewire\CustomerTestimonials;

use App\Domain\AppBase\Models\CustomerTestimonial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;

class Index extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $name = '';

    public string $positionAr = '';

    public string $positionEn = '';

    public string $contentAr = '';

    public string $contentEn = '';

    public int $sortOrder = 0;

    public bool $isActive = true;

    public $image;

    public ?string $existingImage = null;

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    public function hydrate(): void
    {
        $this->authorizeAdmin();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'positionAr' => ['nullable', 'string', 'max:255'],
            'positionEn' => ['nullable', 'string', 'max:255'],
            'contentAr' => ['required', 'string', 'max:2000'],
            'contentEn' => ['required', 'string', 'max:2000'],
            'sortOrder' => ['required', 'integer', 'min:0', 'max:65535'],
            'isActive' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $testimonial = $this->editingId
            ? CustomerTestimonial::query()->findOrFail($this->editingId)
            : new CustomerTestimonial;
        $oldImage = $testimonial->image;

        if ($this->image) {
            $imagePath = $this->image->store('customer-testimonials', 'fileDisk');

            if (! $imagePath) {
                throw new RuntimeException('Unable to store the customer testimonial image.');
            }

            $testimonial->image = $imagePath;
        }

        $testimonial->fill([
            'name' => $this->name,
            'position' => [
                'ar' => $this->positionAr ?: null,
                'en' => $this->positionEn ?: null,
            ],
            'content' => [
                'ar' => $this->contentAr,
                'en' => $this->contentEn,
            ],
            'sort_order' => $this->sortOrder,
            'is_active' => $this->isActive,
        ]);
        if (! $testimonial->exists) {
            $testimonial->status = 'approved';
        }
        $testimonial->save();

        if ($oldImage && $oldImage !== $testimonial->image) {
            $this->deleteStoredImage($oldImage);
        }

        $this->clearHomePageCache();
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $testimonial = CustomerTestimonial::query()->findOrFail($id);
        $position = $testimonial->getTranslations('position');
        $content = $testimonial->getTranslations('content');

        $this->editingId = $testimonial->id;
        $this->name = $testimonial->name;
        $this->positionAr = $position['ar'] ?? '';
        $this->positionEn = $position['en'] ?? '';
        $this->contentAr = $content['ar'] ?? '';
        $this->contentEn = $content['en'] ?? '';
        $this->sortOrder = $testimonial->sort_order;
        $this->isActive = $testimonial->is_active;
        $this->image = null;
        $this->existingImage = $testimonial->image;
    }

    public function delete(int $id): void
    {
        $testimonial = CustomerTestimonial::query()->findOrFail($id);
        $image = $testimonial->image;
        $testimonial->delete();

        if ($image) {
            $this->deleteStoredImage($image);
        }

        $this->clearHomePageCache();

        if ($this->editingId === $id) {
            $this->resetForm();
        }
    }

    public function approve(int $id): void
    {
        $testimonial = CustomerTestimonial::query()->findOrFail($id);
        $testimonial->status = 'approved';
        $testimonial->is_active = true;
        $testimonial->save();

        $this->clearHomePageCache();
    }

    public function reject(int $id): void
    {
        $testimonial = CustomerTestimonial::query()->findOrFail($id);
        $testimonial->status = 'rejected';
        $testimonial->is_active = false;
        $testimonial->save();

        $this->clearHomePageCache();
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('admin::livewire.customer-testimonials.index', [
            'testimonials' => CustomerTestimonial::query()
                ->orderByRaw("status = 'pending' DESC")
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    private function deleteStoredImage(string $path): void
    {
        if (! str_starts_with($path, 'customer-testimonials/')) {
            return;
        }

        $disk = Storage::disk('fileDisk');
        if ($disk->exists($path) && ! $disk->delete($path)) {
            throw new RuntimeException('Unable to delete the customer testimonial image.');
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'name',
            'positionAr',
            'positionEn',
            'contentAr',
            'contentEn',
            'image',
            'existingImage',
        ]);
        $this->sortOrder = 0;
        $this->isActive = true;
        $this->resetValidation();
    }

    private function clearHomePageCache(): void
    {
        foreach (['ar', 'en'] as $locale) {
            Cache::forget("home_page_data_storefront_v7_{$locale}");
        }
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::guard('admin')->check(), 403);
    }
}
