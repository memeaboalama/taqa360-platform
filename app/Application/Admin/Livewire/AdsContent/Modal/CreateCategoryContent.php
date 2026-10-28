<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use Domain\AppBase\Models\AdCategoryContent;
use Domain\MarketPlace\Models\Catalog\Category;
use Livewire\Component;

class CreateCategoryContent extends Component
{
    public $placementId;
    public $selectedCategories = [];
    public $searchTerm = '';

    protected $listeners = ['openCreateCategoryContentModal'];

    public function openCreateCategoryContentModal($placementId)
    {
        $this->placementId = $placementId;
        $this->selectedCategories = [];
        $this->searchTerm = '';
        $this->emit('openModal', '#createCategoryContentModal');
    }

    public function getCategoriesProperty()
    {
        return Category::query()
            ->when($this->searchTerm, function($q) {
                $q->where(function($sub) {
                    $sub->where('title->en', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('title->ar', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->where('is_active', 1)
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();
    }

    public function store()
    {
        $this->validate([
            'selectedCategories' => 'required|array|min:1',
            'placementId' => 'required|exists:ad_placements,id'
        ]);

        foreach ($this->selectedCategories as $categoryId) {
            $exists = AdCategoryContent::where('placement_id', $this->placementId)
                ->where('category_id', $categoryId)
                ->exists();

            if (!$exists) {
                AdCategoryContent::create([
                    'placement_id' => $this->placementId,
                    'category_id' => $categoryId,
                    'priority' => 0,
                    'status' => 'active',
                    'created_by' => auth()->id(),
                ]);
            }
        }

        $this->emit('refreshContent');
        $this->emit('closeModal', '#createCategoryContentModal');
        $this->alert('success', __('admin::lang.Added Successfully'));
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.create-category-content', [
            'categories' => $this->categories
        ]);
    }
}
