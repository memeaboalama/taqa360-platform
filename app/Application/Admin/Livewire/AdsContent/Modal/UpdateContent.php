<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdBanner\UpdateBannerAction;
use App\Domain\AppBase\Models\AdBanner;
use App\Support\Enum\BannerStatusEnum;
use App\Support\Enum\ClickActionTypeEnum;
use App\Support\Enum\PlacementTypeEnum;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UpdateContent extends Component
{
    use LivewireAlert, WithFileUploads;

    public $bannerId;
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

    public $existing_image_desktop;
    public $existing_image_tablet;
    public $existing_image_mobile;
    public $existing_image_favicon;

    public $click_action_type = 'none';
    public $click_action_url;
    public $click_action_id;
    public $button_text_en;
    public $button_text_ar;
    public $selected_item;
    public $merchant_id;
    public $selected_merchant;

    public $start_date;
    public $end_date;

    public $status = 'draft';
    public $priority = 0;

    public $activeTab = 'basic';

    protected $listeners = ['openUpdateContentModal', 'itemSelected', 'merchantSelected' => 'updatedMerchantId'];

    public function openUpdateContentModal($bannerId)
    {
        $this->bannerId = $bannerId;
        $this->loadBanner();
        $this->activeTab = 'basic';

        if ($this->placement) {
            $this->emit('adsContentDevices', $this->getCropDevices());
        }

        $this->emit('openModal', '#updateContentModal');
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

        $result['allowCrop'] = true;

        return $result;
    }

    public function loadBanner()
    {
        $banner = AdBanner::with('placement')->findOrFail($this->bannerId);
        $this->placement = $banner->placement;
        $this->selected_item = null;
        $this->selected_merchant = null;
        $this->merchant_id = null;

        $this->name = $banner->name ?? '';
        $this->title_en = $banner->getTranslation('title', 'en') ?? '';
        $this->title_ar = $banner->getTranslation('title', 'ar') ?? '';
        $this->subtitle_en = $banner->getTranslation('subtitle', 'en') ?? '';
        $this->subtitle_ar = $banner->getTranslation('subtitle', 'ar') ?? '';

        $content = $banner->content ?? [];
        $this->existing_image_desktop = $content['images']['desktop'] ?? null;
        $this->existing_image_tablet = $content['images']['tablet'] ?? null;
        $this->existing_image_mobile = $content['images']['mobile'] ?? null;
        $this->existing_image_favicon = $content['images']['favicon'] ?? null;

        $this->image_desktop = null;
        $this->image_tablet = null;
        $this->image_mobile = null;
        $this->image_favicon = null;

        $clickAction = $banner->click_action ?? [];
        $this->click_action_type = $clickAction['type'] ?? 'none';
        $this->click_action_url = $clickAction['url'] ?? null;
        $this->click_action_id = $clickAction['id'] ?? null;
        $this->button_text_en = $clickAction['button_text']['en'] ?? '';
        $this->button_text_ar = $clickAction['button_text']['ar'] ?? '';

        if ($this->click_action_type === 'product' && $this->click_action_id) {
            $product = Item::find($this->click_action_id);
            if ($product) {
                // Set selected item for the LiveSearch component
                $this->selected_item = [
                    'id' => $product->id,
                    'title' => $product->title,
                    'image' => $product->image_path ?? '/default.jpg',
                    'type' => 'product'
                ];

                if ($product->merchant_id) {
                    $this->merchant_id = $product->merchant_id;
                    $merchant = $product->merchant;
                    if ($merchant) {
                        $this->selected_merchant = [
                            'id' => $merchant->id,
                            'title' => $merchant->company_name,
                            'image' => $merchant->logo ?? '/default.jpg',
                            'type' => 'merchant'
                        ];
                    }
                }
            }
        } elseif ($this->click_action_type === 'merchant' && $this->click_action_id) {
            $merchant = Merchant::find($this->click_action_id);
            if ($merchant) {
                $this->selected_item = [
                    'id' => $merchant->id,
                    'title' => $merchant->getTranslation('company_name', app()->getLocale()),
                    'image' => $merchant->logo ?? '/default.jpg',
                    'type' => 'merchant',
                ];
            }
        } elseif ($this->click_action_type === 'category' && $this->click_action_id) {
            $level = $clickAction['level'] ?? null;
            if (!$level && $this->click_action_url) {
                parse_str((string) parse_url($this->click_action_url, PHP_URL_QUERY), $query);
                $level = isset($query['child_category']) ? 3 : (isset($query['sub_category']) ? 2 : 1);
            }

            if ($level === 3 || $level === '3') {
                $category = ChildCategory::with('subcategory')->find($this->click_action_id);
                if ($category) {
                    $this->selected_item = [
                        'id' => $category->id,
                        'title' => $category->getTranslation('title', app()->getLocale()),
                        'type' => 'category',
                        'level' => 3,
                        'parent_id' => $category->subcategory_id,
                        'parent_main_id' => $category->subcategory->category_id ?? null,
                    ];
                }
            } elseif ($level === 2 || $level === '2') {
                $category = Subcategory::find($this->click_action_id);
                if ($category) {
                    $this->selected_item = [
                        'id' => $category->id,
                        'title' => $category->getTranslation('title', app()->getLocale()),
                        'type' => 'category',
                        'level' => 2,
                        'parent_id' => $category->category_id,
                    ];
                }
            } else {
                $category = Category::find($this->click_action_id);
                if ($category) {
                    $this->selected_item = [
                        'id' => $category->id,
                        'title' => $category->getTranslation('title', app()->getLocale()),
                        'type' => 'category',
                        'level' => 1,
                    ];
                }
            }
        }

        $this->start_date = $banner->start_date ? $banner->start_date->format('Y-m-d\TH:i') : null;
        $this->end_date = $banner->end_date ? $banner->end_date->format('Y-m-d\TH:i') : null;

        $this->status = $banner->status->value ?? 'draft';
        $this->priority = $banner->priority ?? 0;
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
        $merchant = $merchantId ? Merchant::find($merchantId) : null;

        if (!$merchant) {
            $this->merchant_id = null;
            $this->selected_merchant = null;
            if ($this->click_action_type === 'product') {
                $this->reset(['click_action_id', 'click_action_url', 'selected_item']);
            }
            $this->emit('updateMerchantId', null);
            return;
        }

        $this->merchant_id = $merchant->id;
        $this->selected_merchant = [
            'id' => $merchant->id,
            'title' => $merchant->getTranslation('company_name', app()->getLocale()),
            'image' => $merchant->logo ?? '/default.jpg',
            'type' => 'merchant'
        ];

        if ($this->click_action_type === 'product') {
            $this->reset(['click_action_id', 'click_action_url', 'selected_item']);
        }
        $this->emit('updateMerchantId', $merchant->id);
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

        $parentMainId = $item['parent_main_id'] ?? null;

        return match($type) {
            'product' => url(app()->getLocale() . "/product/{$id}"),
            'merchant' => url(app()->getLocale() . "/ecommerce/merchant/{$id}"),
            'category' => $this->generateCategoryUrl($id, $level, $parentId, $parentMainId),
            default => null
        };
    }

    private function generateCategoryUrl($id, $level, $parentId, $parentMainId)
    {
        $baseUrl = url(app()->getLocale() . '/categories');

        return match($level) {
            1 => "{$baseUrl}?main_category={$id}",
            2 => $parentId ? "{$baseUrl}?main_category={$parentId}&sub_category={$id}" : null,
            3 => $parentMainId && $parentId
                ? "{$baseUrl}?main_category={$parentMainId}&sub_category={$parentId}&child_category={$id}"
                : null,
            default => null
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
            'name' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'click_action_type' => 'required|in:' . implode(',', ClickActionTypeEnum::values()),
            'click_action_url' => 'nullable|required_if:click_action_type,url,product,category,merchant|url|max:2048',
            'click_action_id' => 'nullable|required_if:click_action_type,product,category,merchant|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', BannerStatusEnum::values()),
            'priority' => 'required|integer|min:0|max:100',
        ];

        if ($this->placement->key === 'home_hero_slider' || $this->button_text_en !== '' || $this->button_text_ar !== '') {
            $rules['button_text_en'] = 'nullable|string|max:100';
            $rules['button_text_ar'] = 'nullable|string|max:100';
        }

        $devices = $this->placement->settings['display']['devices'] ?? [];

        $desktopEnabled = (bool) ($devices['desktop']['enabled'] ?? false);
        $rules['image_desktop'] = [
            'nullable',
            'image',
            'max:2048',
            'mimes:jpeg,png,jpg,gif,webp',
        ];
        if ($desktopEnabled) {
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

        if ($this->placement->type === 'header' || $this->placement->type === \App\Support\Enum\PlacementTypeEnum::HEADER) {
            $rules['image_favicon'] = [
                'nullable',
                'image',
                'max:1024',
                'mimes:ico,png,jpg,jpeg,svg',
            ];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'image_desktop.dimensions' => __('admin::lang.Image dimensions do not match'),
            'image_tablet.dimensions' => __('admin::lang.Image dimensions do not match'),
            'image_mobile.dimensions' => __('admin::lang.Image dimensions do not match'),
        ];
    }

    public function update(UpdateBannerAction $action)
    {
        $this->validate();

        if ($this->status === 'active') {
            $currentBanner = AdBanner::find($this->bannerId);

            if ($currentBanner->status->value !== 'active') {
                $maxItems = $this->placement->settings['slider_config']['max_items'] ?? $this->placement->settings['max_items'] ?? null;

                if ($maxItems !== null) {
                    $currentActiveCount = AdBanner::where('placement_id', $this->placement->id)
                        ->where('status', 'active')
                        ->count();

                    if ($currentActiveCount >= $maxItems) {
                        $this->alert('error', __('admin::lang.Max Limit Reached') . ' (' . $maxItems . ')');
                        return;
                    }
                }
            }
        }

        $clickAction = [
            'type' => $this->click_action_type,
            'url' => $this->click_action_type === 'none' ? null : $this->click_action_url,
            'id' => in_array($this->click_action_type, ['product', 'category', 'merchant'], true) ? $this->click_action_id : null,
        ];

        if ($this->click_action_type === 'category' && $this->selected_item) {
            $clickAction['level'] = $this->selected_item['level'] ?? null;
            $clickAction['parent_id'] = $this->selected_item['parent_id'] ?? null;
            $clickAction['parent_main_id'] = $this->selected_item['parent_main_id'] ?? null;
        }

        $clickAction['button_text'] = [
            'en' => $this->button_text_en,
            'ar' => $this->button_text_ar,
        ];

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
            'click_action' => $clickAction,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => BannerStatusEnum::from($this->status),
            'priority' => $this->priority,
        ];

        if ($this->image_desktop) {
            $data['image_desktop'] = $this->image_desktop;
        }
        if ($this->image_tablet) {
            $data['image_tablet'] = $this->image_tablet;
        }
        if ($this->image_mobile) {
            $data['image_mobile'] = $this->image_mobile;
        }
        if ($this->image_favicon) {
            $data['image_favicon'] = $this->image_favicon;
        }

        try {
            if (!$action->handle($this->bannerId, $data)) {
                throw new \RuntimeException('Unable to save banner changes.');
            }

            $this->emit('banner-updated');
            $this->emit('closeModal', '#updateContentModal');
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::lang.Updated Successfully'));
        } catch (\Throwable $e) {
            Log::error('Error updating ad banner: ' . $e->getMessage(), ['exception' => $e]);
            $this->alert('error', __('admin::lang.Error occurred') . ': ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.update-content');
    }
}
