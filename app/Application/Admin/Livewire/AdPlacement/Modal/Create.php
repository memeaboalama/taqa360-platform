<?php

namespace Application\Admin\Livewire\AdPlacement\Modal;

use App\Domain\AppBase\Actions\AdPlacement\StorePlacementAction;
use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPageRepository;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Livewire\Component;

class Create extends Component
{
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
    public $loop = true;

    public $showOffcanvas = false;
    // public $groups = [];
    public $tabConfig = [];

    const AVAILABLE_TABS = [
        'latest' => ['en' => 'New Arrivals', 'ar' => 'وصل حديثاً'],
        'best_selling' => ['en' => 'Best Selling', 'ar' => 'الأكثر مبيعاً'],
        'featured' => ['en' => 'Featured', 'ar' => 'متميز'],
        'on_sale' => ['en' => 'On Sale', 'ar' => 'تخفيضات'],
    ];

    protected $listeners = ['openCreateModal'];

    public function openCreateModal(int $pageId)
    {
        $this->pageId = $pageId;
        $this->reset(['name_en', 'name_ar', 'key', 'location', 'type', 'status', 'sort_order', 'tabConfig']);

        foreach (self::AVAILABLE_TABS as $key => $titles) {
            $this->tabConfig[$key] = [
                'enabled' => false,
                'title_en' => $titles['en'],
                'title_ar' => $titles['ar'],
            ];
        }

        $this->showOffcanvas = true;
    }

    public function close()
    {
        $this->showOffcanvas = false;
    }


    protected function rules()
    {
        return [
            'pageId' => 'required|exists:ad_pages,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'key' => 'nullable|string|max:100',
            'location' => 'required|string|max:50',
            'type' => 'required|in:' . implode(',', PlacementTypeEnum::values()),
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'sort_order' => 'required|integer|min:0',
            'tabConfig' => 'array',
            'tabConfig.*.enabled' => 'boolean',
            'tabConfig.*.title_en' => 'required_if:tabConfig.*.enabled,true|string',
            'tabConfig.*.title_ar' => 'required_if:tabConfig.*.enabled,true|string',
        ];
    }

    public function updateTabQuery($tabKey, $query)
    {
        if (isset($this->tabConfig[$tabKey])) {
            $this->tabConfig[$tabKey]['query'] = $query;
        }
    }

    public function store()
    {
        $this->validate();

        try {
            $groups = [];
            if ($this->type === PlacementTypeEnum::PRODUCT_TABS->value) {
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
            }

            $data = [
                'page_id' => $this->pageId,
                'name' => [
                    'en' => $this->name_en,
                    'ar' => $this->name_ar,
                ],
                'key' => $this->key,
                'location' => $this->location,
                'type' => PlacementTypeEnum::from($this->type),
                'status' => StatusEnum::from($this->status),
                'sort_order' => $this->sort_order,
                'settings' => $this->buildSettings($groups),
            ];

            $action = new StorePlacementAction(new AdPageRepository(new AdPage()));
            $placement = $action->handle($data);

            $this->dispatch('placement-created');
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => __('admin::lang.Placement created successfully')
            ]);

            $this->close();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('admin::lang.Error creating placement') . ': ' . $e->getMessage()
            ]);
        }
    }

    protected function buildSettings($groups = []): array
    {
        $settings = [];
        if (in_array($this->type, ['slider', 'carousel', 'product_slider', 'product_tabs'])) {
            $settings['autoplay'] = $this->autoplay;
            $settings['autoplay_speed'] = $this->autoplay_speed;
            $settings['loop'] = $this->loop;
        }

        if (in_array($this->type, ['product_tabs', 'product_slider', 'product_sidebar'])) {
            $settings['max_items'] = $this->max_items;
        }

        if ($this->type === PlacementTypeEnum::PRODUCT_TABS->value && !empty($groups)) {
            $settings['groups'] = $groups;
        }

        $typeEnum = PlacementTypeEnum::from($this->type);
        if (!in_array($typeEnum, [PlacementTypeEnum::PRODUCT_TABS, PlacementTypeEnum::PRODUCT_SLIDER, PlacementTypeEnum::PRODUCT_SIDEBAR])) {
            $settings['display'] = [
                'devices' => [
                    'desktop' => ['enabled' => true, 'width' => 1920, 'height' => 600],
                    'tablet' => ['enabled' => true, 'width' => 1024, 'height' => 400],
                    'mobile' => ['enabled' => true, 'width' => 768, 'height' => 300],
                ],
                'responsive' => true,
            ];

            $settings['actions'] = [
                'click_enabled' => true,
                'allowed_types' => ['url', 'product', 'category', 'merchant', 'none'],
            ];
        }

        if ($typeEnum === PlacementTypeEnum::SLIDER || $typeEnum === PlacementTypeEnum::CAROUSEL || $typeEnum === PlacementTypeEnum::PRODUCT_SLIDER) {
            // $settings['slider_config'] = [
            //     'max_items' => $this->max_items,
            //     'autoplay' => $this->autoplay,
            //     'autoplay_speed' => $this->autoplay_speed,
            //     'loop' => $this->loop,
            //     'show_arrows' => true,
            //     'show_dots' => true,
            // ];
            $settings['show_arrows'] = true;
            $settings['show_dots'] = true;
        }

        if ($typeEnum === PlacementTypeEnum::GRID_BANNERS) {
            $settings['grid_config'] = [
                'columns' => ['desktop' => 4, 'tablet' => 2, 'mobile' => 1],
                'gap' => 20,
                'max_items' => $this->max_items ?? 8,
            ];
        }

        if ($typeEnum === PlacementTypeEnum::PRODUCT_SIDEBAR) {
            // $settings['widget_config'] = [
            //     'max_items' => $this->max_items ?? 5,
            // ];
        }

        return $settings;
    }

    public function render()
    {
        return view('admin::livewire.ad-placement.modal.create');
    }
}
