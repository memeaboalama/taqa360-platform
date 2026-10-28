<?php

namespace Application\Admin\Livewire\AdsContent;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Models\AdBanner;
use App\Domain\AppBase\Actions\AdBanner\StoreBannerAction;
use App\Domain\AppBase\Actions\AdBanner\UpdateBannerAction;
use App\Support\Enum\BannerStatusEnum;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Domain\MarketPlace\Models\Merchant;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class HeaderSettings extends Component
{
    use WithFileUploads, LivewireAlert;

    public $placementId;
    public $bannerId;

    public $site_name_en;
    public $site_name_ar;
    public $top_message_en;
    public $top_message_ar;

    public $offer_text_en;
    public $offer_text_ar;

    public $click_action_type = 'none';
    public $click_action_url;
    public $click_action_id;

    public $merchant_id;
    public $selected_merchant;
    public $selected_action_item;

    public $logo;
    public $mobile_logo;
    public $favicon;

    public $delete_logo = false;
    public $delete_mobile_logo = false;
    public $delete_favicon = false;

    public $existing_logo;
    public $existing_mobile_logo;
    public $existing_favicon;

    protected $listeners = ['itemSelected', 'merchantSelected' => 'updatedMerchantId'];

    public function mount($placementId)
    {
        $this->placementId = $placementId;
        $this->loadContent();
    }

    public function removeImage($key)
    {
        $this->$key = null;
        $this->{"existing_$key"} = null;
        $this->{"delete_$key"} = true;
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
                $this->reset(['click_action_id', 'click_action_url']);
            }
            $this->emit('updateMerchantId', $merchantId);
        } else {
            $this->selected_merchant = null;
            $this->emit('updateMerchantId', null);
        }
    }

    public function itemSelected($item = null)
    {
        if ($item) {
            $this->click_action_id = $item['id'] ?? null;
            $this->click_action_url = $this->generateUrlForItem($item);
            $this->selected_action_item = $item;
        } else {
            $this->click_action_id = null;
            $this->click_action_url = null;
        }
    }

    public function updatedClickActionType($value)
    {
        $this->reset(['click_action_id', 'click_action_url', 'merchant_id', 'selected_merchant']);
    }

    private function generateUrlForItem($item)
    {
        $type = $item['type'] ?? $this->click_action_type;
        $id = $item['id'];
        $level = $item['level'] ?? null;
        $parentId = $item['parent_id'] ?? null;

        return match ($type) {
            'product' => url(app()->getLocale() . "/product/{$id}"),
            'merchant' => url(app()->getLocale() . "/ecommerce/merchant/{$id}"),
            'category' => $this->generateCategoryUrl($id, $level, $parentId),
            default => null
        };
    }

    private function generateCategoryUrl($id, $level, $parentId)
    {
        $baseUrl = url(app()->getLocale() . '/categories');

        return match ($level) {
            1 => "{$baseUrl}?main_category={$id}",
            2 => "{$baseUrl}?main_category={$parentId}&sub_category={$id}",
            3 => "{$baseUrl}?main_category=1&child_category={$id}&sub_category=all",
            default => "{$baseUrl}?main_category={$id}"
        };
    }

    public function loadContent()
    {
        $banner = AdBanner::where('placement_id', $this->placementId)
            ->where('status', 'active')
            ->first();

        if ($banner) {
            $this->bannerId = $banner->id;
            $this->site_name_en = $banner->getTranslation('title', 'en');
            $this->site_name_ar = $banner->getTranslation('title', 'ar');
            $this->top_message_en = $banner->getTranslation('subtitle', 'en');
            $this->top_message_ar = $banner->getTranslation('subtitle', 'ar');

            $content = $banner->content ?? [];
            $this->existing_logo = $content['images']['desktop'] ?? null;
            $this->existing_mobile_logo = $content['images']['mobile'] ?? null;
            $this->existing_favicon = $content['images']['favicon'] ?? null;

            $this->offer_text_en = $content['offer_text']['en'] ?? '';
            $this->offer_text_ar = $content['offer_text']['ar'] ?? '';

            $this->click_action_type = $content['offer_action']['type'] ?? 'none';
            $this->click_action_url = $content['offer_action']['url'] ?? '';
            $this->click_action_id = $content['offer_action']['id'] ?? null;

            if ($this->click_action_id && $this->click_action_type !== 'none' && $this->click_action_type !== 'url') {
                $this->hydrateSelectedItems();
            }
        }
    }

    public function hydrateSelectedItems()
    {
        if ($this->click_action_type === 'product') {
            $item = \Domain\MarketPlace\Models\Item::find($this->click_action_id);
            if ($item) {
                $this->merchant_id = $item->merchant_id;
                $merchant = $item->merchant;
                if ($merchant) {
                    $this->selected_merchant = [
                        'id' => $merchant->id,
                        'title' => $merchant->name,
                        'image' => $merchant->logo ?? '/default.jpg',
                        'type' => 'merchant'
                    ];
                }

                $this->selected_action_item = [
                    'id' => $item->id,
                    'title' => $item->getTranslation('title', app()->getLocale()),
                    'image' => $item->image_path ?? '/default.jpg',
                    'type' => 'product'
                ];
            }
        } elseif ($this->click_action_type === 'merchant') {
            $merchant = Merchant::find($this->click_action_id);
            if ($merchant) {
                $this->selected_action_item = [
                    'id' => $merchant->id,
                    'title' => $merchant->name,
                    'image' => $merchant->logo ?? '/default.jpg',
                    'type' => 'merchant'
                ];
            }
        } elseif ($this->click_action_type === 'category') {
            $category = Category::find($this->click_action_id);

            if ($category) {
                $this->selected_action_item = [
                    'id' => $category->id,
                    'title' => $category->title,
                    'type' => 'category',
                    'image' => null
                ];
            } else {
                $sub = Subcategory::find($this->click_action_id);
                if ($sub) {
                    $this->selected_action_item = [
                        'id' => $sub->id,
                        'title' => $sub->title,
                        'type' => 'category',
                        'image' => $sub->image
                    ];
                }
            }
        }
    }

    public function save(StoreBannerAction $storeAction, UpdateBannerAction $updateAction)
    {
        $this->validate([
            'site_name_en' => 'nullable|string|max:255',
            'site_name_ar' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'mobile_logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024|mimes:ico,png,jpg,jpeg,svg',
            'offer_text_en' => 'nullable|string|max:255',
            'offer_text_ar' => 'nullable|string|max:255',
            'click_action_url' => 'nullable|url',
        ]);

        $images = [
            'desktop' => $this->delete_logo ? null : ($this->existing_logo ?? null),
            'mobile'  => $this->delete_mobile_logo ? null : ($this->existing_mobile_logo ?? null),
            'favicon' => $this->delete_favicon ? null : ($this->existing_favicon ?? null),
        ];

        $contentData = [
            'images' => $images,
            'offer_text' => [
                'en' => $this->offer_text_en,
                'ar' => $this->offer_text_ar,
            ],
            'offer_action' => [
                'type' => $this->click_action_type,
                'url' => $this->click_action_url,
                'id' => $this->click_action_id,
            ],
        ];

        $data = [
            'placement_id' => $this->placementId,
            'name' => 'Header Content',
            'title' => [
                'en' => $this->site_name_en,
                'ar' => $this->site_name_ar,
            ],
            'subtitle' => [
                'en' => $this->top_message_en,
                'ar' => $this->top_message_ar,
            ],
            'status' => BannerStatusEnum::ACTIVE->value,
            'content' => $contentData,
        ];

        if ($this->logo) {
            $data['image_desktop'] = $this->logo;
        }

        if ($this->mobile_logo) {
            $data['image_mobile'] = $this->mobile_logo;
        }

        if ($this->favicon) {
            $data['image_favicon'] = $this->favicon;
        }

        if ($this->bannerId) {
            $updateAction->handle($this->bannerId, $data);
        } else {
            $storeAction->handle($data);
            $this->loadContent();
        }

        $placement = AdPlacement::find($this->placementId);
        if ($placement) {
            Cache::forget('site_header_settings');
        }

        $this->alert('success', __('admin::lang.Updated Successfully'));

        $this->logo = null;
        $this->mobile_logo = null;
        $this->favicon = null;

        $this->loadContent();
    }

    public function render()
    {
        return view('admin::livewire.ads-content.header-settings');
    }
}
