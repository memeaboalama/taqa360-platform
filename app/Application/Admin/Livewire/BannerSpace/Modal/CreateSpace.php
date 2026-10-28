<?php

namespace Application\Admin\Livewire\BannerSpace\Modal;

use App\Domain\AppBase\Actions\AdPlacement\StorePlacementAction;
use App\Domain\AppBase\Models\AdPlacement;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class CreateSpace extends Component
{
    use LivewireAlert;

    public $pageId;
    public $name_en;
    public $name_ar;
    public $key;
    public $location = 'content_top';
    public $type = 'slider';
    public $status = 'active';
    public $sort_order = 0;

    public $max_items = 5;
    public $autoplay = true;
    public $autoplay_speed = 5000;
    public $transition_speed = 800;
    public $loop = true;
    public $show_arrows = true;
    public $show_dots = true;
    public $pause_on_hover = true;
    public $swipe_enabled = true;

    public $tabConfig = [];

    const AVAILABLE_TABS = [
        'latest' => ['en' => 'New Arrivals', 'ar' => 'وصل حديثاً'],
        'best_selling' => ['en' => 'Best Selling', 'ar' => 'الأكثر مبيعاً'],
        'featured' => ['en' => 'Featured', 'ar' => 'مميز'],
        'on_sale' => ['en' => 'On Sale', 'ar' => 'عروض خاصة'],
    ];

    public $desktop_enabled = true;
    public $desktop_width = 1920;
    public $desktop_height = 600;

    public $tablet_enabled = true;
    public $tablet_width = 1024;
    public $tablet_height = 400;

    public $mobile_enabled = true;
    public $mobile_width = 768;
    public $mobile_height = 300;

    public $grid_columns_desktop = 4;
    public $grid_columns_tablet = 2;
    public $grid_columns_mobile = 1;
    public $grid_gap = 20;
    public $grid_max_items = 8;

    public $click_enabled = true;
    public $allowed_click_types = ['url', 'product', 'category', 'merchant', 'none'];

    protected $listeners = ['openCreateSpaceModal', 'openCreateSpaceModalFromCopy'];

    public function updatedNameEn($value)
    {
        $this->generateUniqueKey($value);
    }

    protected function generateUniqueKey(?string $name = null): void
    {
        $base = Str::slug($name ?? $this->name_en ?? 'space');
        if (!$base) {
            $base = 'space';
        }

        $key = $base;
        $counter = 1;
        while (AdPlacement::where('key', $key)->exists()) {
            $counter++;
            $key = $base . '-' . $counter;
        }

        $this->key = $key;
    }

    public function openCreateSpaceModal($pageId)
    {
        $this->pageId = $pageId;
        $this->reset([
            'name_en', 'name_ar', 'key', 'location', 'type', 'status', 'sort_order',
            'max_items', 'autoplay', 'autoplay_speed', 'loop', 'tabConfig'
        ]);

        foreach (self::AVAILABLE_TABS as $key => $titles) {
            $this->tabConfig[$key] = [
                'enabled' => false,
                'title_en' => $titles['en'],
                'title_ar' => $titles['ar'],
                'query' => [
                    'type' => 'preset',
                    'preset' => $key,
                ],
            ];
        }

        $this->generateUniqueKey();

        $this->emit('openModal', '#createSpaceModal');
    }

    public function openCreateSpaceModalFromCopy($placementId)
    {
        $placement = AdPlacement::with('page')->findOrFail($placementId);

        $this->pageId = $placement->page_id;
        $this->name_en = $placement->getTranslation('name', 'en') . ' (Copy)';
        $this->name_ar = $placement->getTranslation('name', 'ar') . ' (Copy)';
        $this->key = $placement->key ? $placement->key . '_copy' : null;
        $this->location = $placement->location;
        $this->type = $placement->type->value;
        $this->status = $placement->status->value;
        $this->sort_order = $placement->sort_order;

        $settings = $placement->settings ?? [];

        $this->desktop_enabled = $settings['display']['devices']['desktop']['enabled'] ?? true;
        $this->desktop_width = $settings['display']['devices']['desktop']['width'] ?? 1920;
        $this->desktop_height = $settings['display']['devices']['desktop']['height'] ?? 600;

        $this->tablet_enabled = $settings['display']['devices']['tablet']['enabled'] ?? true;
        $this->tablet_width = $settings['display']['devices']['tablet']['width'] ?? 1024;
        $this->tablet_height = $settings['display']['devices']['tablet']['height'] ?? 400;

        $this->mobile_enabled = $settings['display']['devices']['mobile']['enabled'] ?? true;
        $this->mobile_width = $settings['display']['devices']['mobile']['width'] ?? 768;
        $this->mobile_height = $settings['display']['devices']['mobile']['height'] ?? 300;

        if (isset($settings['slider_config'])) {
            $this->max_items = $settings['slider_config']['max_items'] ?? 5;
            $this->autoplay = $settings['slider_config']['autoplay'] ?? true;
            $this->autoplay_speed = $settings['slider_config']['autoplay_speed'] ?? 5000;
            $this->transition_speed = $settings['slider_config']['transition_speed'] ?? 800;
            $this->loop = $settings['slider_config']['loop'] ?? true;
            $this->show_arrows = $settings['slider_config']['show_arrows'] ?? true;
            $this->show_dots = $settings['slider_config']['show_dots'] ?? true;
            $this->pause_on_hover = $settings['slider_config']['pause_on_hover'] ?? true;
            $this->swipe_enabled = $settings['slider_config']['swipe_enabled'] ?? true;
        }

        if (isset($settings['grid_config'])) {
            $this->grid_columns_desktop = $settings['grid_config']['columns']['desktop'] ?? 4;
            $this->grid_columns_tablet = $settings['grid_config']['columns']['tablet'] ?? 2;
            $this->grid_columns_mobile = $settings['grid_config']['columns']['mobile'] ?? 1;
            $this->grid_gap = $settings['grid_config']['gap'] ?? 20;
            $this->grid_max_items = $settings['grid_config']['max_items'] ?? 8;
        }

        if (isset($settings['actions'])) {
            $this->click_enabled = $settings['actions']['click_enabled'] ?? true;
            $this->allowed_click_types = $settings['actions']['allowed_types'] ?? ['url', 'product', 'category', 'merchant', 'none'];
        }

        foreach (self::AVAILABLE_TABS as $key => $titles) {
            $this->tabConfig[$key] = [
                'enabled' => false,
                'title_en' => $titles['en'],
                'title_ar' => $titles['ar'],
                'query' => [
                    'type' => 'preset',
                    'preset' => $key,
                ],
            ];
        }

        foreach ($settings['groups'] ?? [] as $group) {
            if (isset($this->tabConfig[$group['key']])) {
                $this->tabConfig[$group['key']] = [
                    'enabled' => true,
                    'title_en' => $group['title']['en'] ?? '',
                    'title_ar' => $group['title']['ar'] ?? '',
                    'query' => $group['query'] ?? [
                        'type' => 'preset',
                        'preset' => $group['key'],
                    ],
                ];
            }
        }

        $this->emit('openModal', '#createSpaceModal');
    }

    protected function rules()
    {
        return [
            'pageId' => 'required|exists:ad_pages,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'key' => 'nullable|string|max:100|unique:ad_placements,key',
            'location' => 'required|string|max:50',
            'type' => 'required|in:' . implode(',', PlacementTypeEnum::values()),
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'sort_order' => 'required|integer|min:0',
        ];
    }

    public function store(StorePlacementAction $action)
    {
        $this->validate();

        $settings = $this->buildSettings();

        $data = [
            'page_id' => $this->pageId,
            'name' => [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ],
            'key' => $this->key,
            'location' => $this->location,
            'type' => PlacementTypeEnum::from($this->type),
            'settings' => $settings,
            'status' => StatusEnum::from($this->status),
            'sort_order' => $this->sort_order,
        ];

        DB::beginTransaction();
        $action->handle($data);
        \Illuminate\Support\Facades\Cache::forget('home_page_data_en');
        \Illuminate\Support\Facades\Cache::forget('home_page_data_ar');
        
        $this->emit('refreshSpaces');
        $this->emit('closeModal', '#createSpaceModal');
        $this->alert('success', __('admin::home.Added Successfully'));
        DB::commit();
    }

    protected function buildSettings(): array
    {
        $type = PlacementTypeEnum::from($this->type);

        $settings = [
            'display' => [
                'devices' => [
                    'desktop' => [
                        'enabled' => $this->desktop_enabled,
                        'width' => $this->desktop_width,
                        'height' => $this->desktop_height
                    ],
                    'tablet' => [
                        'enabled' => $this->tablet_enabled,
                        'width' => $this->tablet_width,
                        'height' => $this->tablet_height
                    ],
                    'mobile' => [
                        'enabled' => $this->mobile_enabled,
                        'width' => $this->mobile_width,
                        'height' => $this->mobile_height
                    ],
                ],
                'responsive' => true,
            ],
            'actions' => [
                'click_enabled' => $this->click_enabled,
                'allowed_types' => $this->allowed_click_types,
            ],
        ];

        if ($type === PlacementTypeEnum::SLIDER || $type === PlacementTypeEnum::CAROUSEL) {
            $settings['slider_config'] = [
                'max_items' => $this->max_items,
                'autoplay' => $this->autoplay,
                'autoplay_speed' => $this->autoplay_speed,
                'transition_speed' => $this->transition_speed,
                'loop' => $this->loop,
                'show_arrows' => $this->show_arrows,
                'show_dots' => $this->show_dots,
                'pause_on_hover' => $this->pause_on_hover,
                'swipe_enabled' => $this->swipe_enabled,
            ];
        }

        if ($type === PlacementTypeEnum::GRID_BANNERS) {
            $settings['grid_config'] = [
                'columns' => [
                    'desktop' => $this->grid_columns_desktop,
                    'tablet' => $this->grid_columns_tablet,
                    'mobile' => $this->grid_columns_mobile,
                ],
                'gap' => $this->grid_gap,
                'max_items' => $this->grid_max_items,
            ];
        }

        if ($type === PlacementTypeEnum::PRODUCT_TABS) {
            $groups = [];
            foreach ($this->tabConfig as $key => $config) {
                if ($config['enabled'] ?? false) {
                    $groups[] = [
                        'key' => $key,
                        'title' => [
                            'en' => $config['title_en'] ?? '',
                            'ar' => $config['title_ar'] ?? '',
                        ],
                        'enabled' => true,
                        'query' => $config['query'] ?? [
                            'type' => 'preset',
                            'preset' => $key,
                        ],
                    ];
                }
            }
            $settings['groups'] = $groups;
            $settings['max_items'] = $this->max_items;
        }

        return $settings;
    }

    public function render()
    {
        return view('admin::livewire.banner-space.modal.create-space');
    }
}
