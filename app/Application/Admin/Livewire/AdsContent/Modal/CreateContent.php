<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdBanner\StoreBannerAction;
use App\Domain\AppBase\Actions\AdBanner\ReorderActiveBannersAction;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\ClickActionTypeEnum;
use App\Support\Enum\PlacementTypeEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Cache;

class CreateContent extends Component
{
    use LivewireAlert, WithFileUploads;

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
    public $image_favicon;

    public $click_action_type = 'none';
    public $click_action_url;
    public $click_action_id;
    public $selected_item;
    public $merchant_id;
    public $selected_merchant;

    public $start_date;
    public $end_date;

    public $status = 'draft';
    public $priority = 0;

    public $activeTab = 'basic';

    protected $listeners = ['openCreateContentModal', 'itemSelected', 'merchantSelected' => 'updatedMerchantId'];

    public function openCreateContentModal($placementId)
    {
        $this->placementId = $placementId;
        $this->loadPlacement();
        $this->reset(['name', 'title_en', 'title_ar', 'subtitle_en', 'subtitle_ar', 'image_desktop', 'image_tablet', 'image_mobile', 'image_favicon', 'click_action_type', 'click_action_url', 'click_action_id', 'start_date', 'end_date', 'status', 'priority', 'merchant_id', 'selected_merchant']);
        $this->activeTab = 'basic';

        if ($this->placement) {
            $this->emit('adsContentDevices', $this->getCropDevices());
        }

        $this->emit('openModal', '#createContentModal');
    }

    protected function getCropDevices(): array
    {
        $devices = $this->placement->settings['display']['devices'] ?? [];
        $labels = [
            'image_desktop' => $this->placement->type === PlacementTypeEnum::HEADER ? __('admin::lang.Logo') : __('admin::lang.Desktop Image'),
            'image_mobile' => $this->placement->type === PlacementTypeEnum::HEADER ? __('admin::lang.Small Logo') : __('admin::lang.Mobile Image'),
            'image_tablet' => __('admin::lang.Tablet Image'),
        ];

        $map = [
            'image_desktop' => 'desktop',
            'image_mobile' => 'mobile',
            'image_tablet' => 'tablet',
        ];

        $defaults = [
            'desktop' => ['width' => 1920, 'height' => 600],
            'tablet' => ['width' => 1024, 'height' => 400],
            'mobile' => ['width' => 768, 'height' => 300],
        ];

        $result = [];
        foreach ($map as $property => $device) {
            $cfg = $devices[$device] ?? [];
            $result[$property] = [
                'width' => (int) ($cfg['width'] ?? $defaults[$device]['width']),
                'height' => (int) ($cfg['height'] ?? $defaults[$device]['height']),
                'label' => $labels[$property],
            ];
        }

        $result['allowCrop'] = $this->placement->key !== 'home_hero_slider';

        return $result;
    }

    public function loadPlacement()
    {
        $repository = new AdPlacementRepository(new \App\Domain\AppBase\Models\AdPlacement());
        $this->placement = $repository->findById($this->placementId);
    }

    public function itemSelected($item = null)
    {
        if ($item) {
            $this->selected_item = $item;
            $this->click_action_id = $item['id'] ?? null;

            $this->click_action_url = $this->generateUrlForItem($item);
        } else {
            $this->selected_item = null;
            $this->click_action_id = null;
            $this->click_action_url = null;
        }
    }

    public function updatedMerchantId($value)
    {
        $merchantId = is_array($value) ? ($value['id'] ?? null) : $value;
        if ($merchantId) {
            $merchant = Merchant::find($merchantId);
            if ($merchant) {
                $this->selected_merchant = [
                    'id' => $merchant->id,
                    'title' => $merchant->getTranslation('company_name', app()->getLocale()),
                    'image' => $merchant->logo ?? '/default.jpg',
                    'type' => 'merchant'
                ];
            }
            if ($this->click_action_type === 'product') {
                $this->reset(['click_action_id', 'click_action_url', 'selected_item']);
            }
            $this->emit('updateMerchantId', $merchantId);
        } else {
            $this->selected_merchant = null;
            $this->emit('updateMerchantId', null);
        }
    }

    public function updatedClickActionType($value)
    {
        $this->reset(['click_action_id', 'click_action_url', 'selected_item', 'merchant_id', 'selected_merchant']);
    }

    private function generateUrlForItem($item)
    {
        $type = $item['type'] ?? $this->click_action_type;
        $id = $item['id'];
        $level = $item['level'] ?? null;
        $parentId = $item['parent_id'] ?? null;

        return match($type) {
            'product' => url(app()->getLocale() . "/product/{$id}"),
            'merchant' => url(app()->getLocale() . "/ecommerce/merchant/{$id}"),
            'category' => $this->generateCategoryUrl($id, $level, $parentId),
            default => null
        };
    }

    private function generateCategoryUrl($id, $level, $parentId)
    {
        $baseUrl = url(app()->getLocale() . '/categories');

        return match($level) {
            1 => "{$baseUrl}?main_category={$id}",
            2 => "{$baseUrl}?main_category={$parentId}&sub_category={$id}",
            3 => "{$baseUrl}?main_category=1&child_category={$id}&sub_category=all",
            default => "{$baseUrl}?main_category={$id}"
        };
    }


    protected function rules()
    {
        if (!$this->placement) {
            return [
                'placementId' => 'required|exists:ad_placements,id',
            ];
        }

        $rules = [
            'placementId' => 'required|exists:ad_placements,id',
            'name' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'click_action_type' => 'nullable|in:' . implode(',', ClickActionTypeEnum::values()),
            'status' => 'required|in:' . implode(',', BannerStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
        ];

        $devices = $this->placement->settings['display']['devices'] ?? [];

        if ($devices['desktop']['enabled'] ?? false) {
            $rules['image_desktop'] = [
                'required',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,gif,webp',
            ];
            if ($this->placement->key !== 'home_hero_slider') {
                $rules['image_desktop'][] = 'dimensions:width=' . ($devices['desktop']['width'] ?? 1920) . ',height=' . ($devices['desktop']['height'] ?? 600);
            }
        }

        if ($devices['tablet']['enabled'] ?? false) {
            $rules['image_tablet'] = [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,gif,webp',
            ];
            if ($this->placement->key !== 'home_hero_slider') {
                $rules['image_tablet'][] = 'dimensions:width=' . ($devices['tablet']['width'] ?? 1200) . ',height=' . ($devices['tablet']['height'] ?? 400);
            }
        }

        if ($devices['mobile']['enabled'] ?? false) {
            $rules['image_mobile'] = [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,gif,webp',
            ];
            if ($this->placement->key !== 'home_hero_slider') {
                $rules['image_mobile'][] = 'dimensions:width=' . ($devices['mobile']['width'] ?? 768) . ',height=' . ($devices['mobile']['height'] ?? 300);
            }
        }

        if ($this->placement->type->value === 'header') {
            $rules['image_favicon'] = [
                'nullable',
                'image',
                'max:1024',
                'mimes:ico,png,jpg,jpeg,svg',
            ];
        }

        return $rules;
    }

    public function store(StoreBannerAction $action)
    {
        $this->validate();

        if ($this->status === 'active') {
            $maxItems = $this->placement->settings['slider_config']['max_items'] ?? $this->placement->settings['max_items'] ?? null;

            if ($maxItems !== null) {
                $currentActiveCount = \App\Domain\AppBase\Models\AdBanner::where('placement_id', $this->placementId)
                    ->where('status', 'active')
                    ->count();

                if ($currentActiveCount >= $maxItems) {
                    $this->alert('error', __('admin::lang.Max Limit Reached') . ' (' . $maxItems . ')');
                    return;
                }
            }
        }

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
            'image_favicon' => $this->image_favicon,
            'click_action' => [
                'type' => $this->click_action_type,
                'url' => $this->click_action_url,
                'id' => $this->click_action_id,
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => BannerStatusEnum::from($this->status)->value,
            'priority' => $this->priority,
        ];

        try {
            DB::beginTransaction();
            $action->handle($data);
            DB::commit();

            DB::commit();

            if ($this->status === 'active') {
                (new ReorderActiveBannersAction())->handle($this->placementId);
            }

            if ($this->placement) {
                Cache::forget("ecommerce_banners_{$this->placement->key}_en");
                Cache::forget("ecommerce_banners_{$this->placement->key}_ar");
                Cache::forget('home_page_data_en');
                Cache::forget('home_page_data_ar');
            }

            $this->emit('banner-created');
            $this->emit('closeModal', '#createContentModal');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::lang.Added Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Error creating ad banner: ' . $e->getMessage(), ['exception' => $e]);
            $this->alert('error', __('admin::lang.Error occurred') . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.create-content');
    }
}
