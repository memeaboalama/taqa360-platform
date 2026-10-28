<?php

namespace Application\Admin\Livewire\AdBanner\Modal;

use App\Domain\AppBase\Actions\AdBanner\StoreBannerAction;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\ClickActionTypeEnum;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $placementId;
    public $placement;

    public $name;
    public $title_en;
    public $title_ar;
    public $subtitle_en;
    public $subtitle_ar;

    public $image_desktop;
    public $image_tablet;
    public $image_mobile;

    public $click_action_type = 'none';
    public $click_action_url;
    public $click_action_id;
    
    public $searchQuery = '';
    public $searchResults = [];

    public $start_date;
    public $end_date;
    public $status = 'draft';
    public $priority = 0;

    protected $listeners = ['openCreateModal'];

    public function openCreateModal(int $placementId, AdPlacementRepository $repo)
    {
        $this->placementId = $placementId;
        $this->placement = $repo->findByCols(['id' => $placementId]);
        $this->reset(['name', 'title_en', 'title_ar', 'subtitle_en', 'subtitle_ar']);
        $this->dispatch('open-modal', 'createBannerModal');
    }

    protected function rules()
    {
        return [
            'placementId' => 'required|exists:ad_placements,id',
            'name' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'image_desktop' => 'required|image|max:2048',
            'image_mobile' => 'nullable|image|max:2048',
            'click_action_type' => 'required|in:' . implode(',', ClickActionTypeEnum::values()),
            'status' => 'required|in:' . implode(',', BannerStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
        ];
    }

    public function store(StoreBannerAction $action)
    {
        $this->validate();

        $data = [
            'placement_id' => $this->placementId,
            'name' => $this->name,
            'title' => [
                'en' => $this->title_en,
                'ar' => $this->title_ar,
            ],
            'subtitle' => [
                'en' => $this->subtitle_en,
                'ar' => $this->subtitle_ar,
            ],
            'image_desktop' => $this->image_desktop,
            'image_tablet' => $this->image_tablet,
            'image_mobile' => $this->image_mobile,
            'click_action' => [
                'type' => $this->click_action_type,
                'url' => $this->click_action_url,
                'id' => $this->click_action_id,
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => BannerStatusEnum::from($this->status),
            'priority' => $this->priority,
        ];

        $action->handle($data);

        $this->dispatch('close-modal', 'createBannerModal');
        $this->dispatch('banner-created');
        $this->dispatch('success', __('admin::home.Added Successfully'));
    }

    public function updatedSearchQuery()
    {
        if (empty($this->searchQuery) || strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }
        
        $this->searchResults = match($this->click_action_type) {
            'product' => $this->searchProducts(),
            'category' => $this->searchCategories(),
            'merchant' => $this->searchMerchants(),
            default => []
        };
    }
    
    public function selectSearchResult($id)
    {
        $url = match($this->click_action_type) {
            'product' => route('product.show', $id),
            'category' => route('category.show', $id),
            'merchant' => route('merchant.show', $id),
            default => ''
        };
        
        $this->click_action_url = $url;
        $this->click_action_id = $id;
        $this->searchQuery = '';
        $this->searchResults = [];
    }
    
    private function searchProducts()
    {
        try {
            $action = app(\App\Domain\MarketPlace\Actions\Api\Item\FetchItemsAction::class);
            $results = $action->handle([
                'search' => $this->searchQuery,
                'per_page' => 5,
                'paginated' => false
            ]);
            return $results->take(5);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function searchCategories()
    {
        try {
            $action = app(\App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchCategoryAction::class);
            $results = $action->handle();
            return $results->filter(function($cat) {
                return stripos($cat->title ?? $cat->name, $this->searchQuery) !== false;
            })->take(5);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function searchMerchants()
    {
        // Placeholder - implement based on your merchant repository
        return [];
    }

    public function render()
    {
        return view('admin::livewire.ad-banner.modal.create');
    }
}
