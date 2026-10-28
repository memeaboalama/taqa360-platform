<?php

namespace Application\Admin\Livewire\AdBanner\Modal;

use App\Domain\AppBase\Actions\AdBanner\UpdateBannerAction;
use App\Domain\AppBase\Repositories\AdBannerRepository;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\ClickActionTypeEnum;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;

    public $bannerId;
    public $placementId;

    public $name;
    public $title_en;
    public $title_ar;
    public $subtitle_en;
    public $subtitle_ar;

    public $image_desktop;
    public $image_tablet;
    public $image_mobile;

    public $new_image_desktop;
    public $new_image_tablet;
    public $new_image_mobile;

    public $click_action_type;
    public $click_action_url;
    public $click_action_id;
    
    public $searchQuery = '';
    public $searchResults = [];

    public $start_date;
    public $end_date;
    public $status;
    public $priority;

    protected $listeners = ['openUpdateModal'];

    public function openUpdateModal(int $bannerId, AdBannerRepository $repo)
    {
        $banner = $repo->findByCols(['id' => $bannerId]);

        if (!$banner) {
            return;
        }

        $this->bannerId = $banner->id;
        $this->placementId = $banner->placement_id;
        $this->name = $banner->name;
        $this->title_en = $banner->title['en'] ?? '';
        $this->title_ar = $banner->title['ar'] ?? '';
        $this->subtitle_en = $banner->subtitle['en'] ?? '';
        $this->subtitle_ar = $banner->subtitle['ar'] ?? '';

        $this->image_desktop = $banner->content['images']['desktop'] ?? null;
        $this->image_tablet = $banner->content['images']['tablet'] ?? null;
        $this->image_mobile = $banner->content['images']['mobile'] ?? null;

        $this->click_action_type = $banner->click_action['type'] ?? 'none';
        $this->click_action_url = $banner->click_action['url'] ?? '';
        $this->click_action_id = $banner->click_action['id'] ?? '';

        $this->start_date = $banner->start_date ? $banner->start_date->format('Y-m-d') : null;
        $this->end_date = $banner->end_date ? $banner->end_date->format('Y-m-d') : null;
        $this->status = $banner->status->value;
        $this->priority = $banner->priority;

        $this->dispatch('open-modal', 'updateBannerModal');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'new_image_desktop' => 'nullable|image|max:2048',
            'new_image_mobile' => 'nullable|image|max:2048',
            'click_action_type' => 'required|in:' . implode(',', ClickActionTypeEnum::values()),
            'status' => 'required|in:' . implode(',', BannerStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
        ];
    }

    public function update(UpdateBannerAction $action)
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'title' => [
                'en' => $this->title_en,
                'ar' => $this->title_ar,
            ],
            'subtitle' => [
                'en' => $this->subtitle_en,
                'ar' => $this->subtitle_ar,
            ],
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

        if ($this->new_image_desktop) {
            $data['image_desktop'] = $this->new_image_desktop;
        }
        if ($this->new_image_tablet) {
            $data['image_tablet'] = $this->new_image_tablet;
        }
        if ($this->new_image_mobile) {
            $data['image_mobile'] = $this->new_image_mobile;
        }

        $action->handle($this->bannerId, $data);

        $this->dispatch('close-modal', 'updateBannerModal');
        $this->dispatch('banner-updated');
        $this->dispatch('success', __('admin::home.Updated Successfully'));
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
        return [];
    }

    public function render()
    {
        return view('admin::livewire.ad-banner.modal.update');
    }
}
