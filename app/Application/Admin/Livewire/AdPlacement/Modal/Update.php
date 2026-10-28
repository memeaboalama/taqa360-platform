<?php

namespace Application\Admin\Livewire\AdPlacement\Modal;

use App\Domain\AppBase\Actions\AdPlacement\UpdatePlacementAction;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use WithFileUploads;

    public $placementId;
    public $name_en;
    public $name_ar;
    public $key;
    public $location;
    public $type;
    public $status;
    public $sort_order;

    public $max_items;
    public $autoplay;
    public $autoplay_speed;
    public $loop;
    public $backgroundImage;
    public $existingBackgroundImage;
    public $removeBackground = false;

    public $showOffcanvas = false;
    // public $groups = [];
    public $tabConfig = [];

    const AVAILABLE_TABS = [
        'latest' => ['en' => 'New Arrivals', 'ar' => 'وصل حديثاً'],
        'best_selling' => ['en' => 'Best Selling', 'ar' => 'الأكثر مبيعاً'],
        'featured' => ['en' => 'Featured', 'ar' => 'متميز'],
        'on_sale' => ['en' => 'On Sale', 'ar' => 'تخفيضات'],
    ];

    protected $listeners = ['openUpdateModal'];

    public function openUpdateModal(int $placementId, AdPlacementRepository $repo)
    {
        $placement = $repo->findByCols(['id' => $placementId]);

        if (!$placement) {
            return;
        }

        $this->placementId = $placement->id;
        $this->name_en = $placement->name['en'] ?? '';
        $this->name_ar = $placement->name['ar'] ?? '';
        $this->key = $placement->key;
        $this->location = $placement->location;
        $this->type = $placement->type->value;
        $this->status = $placement->status->value;
        $this->sort_order = $placement->sort_order;

        $settings = $placement->settings;
        $this->max_items = $settings['slider_config']['max_items'] ?? $settings['grid_config']['max_items'] ?? $settings['carousel_config']['max_items'] ?? $settings['widget_config']['max_items'] ?? 5;
        $this->autoplay = $settings['slider_config']['autoplay'] ?? $settings['carousel_config']['autoplay'] ?? true;
        $this->autoplay_speed = $settings['slider_config']['autoplay_speed'] ?? $settings['carousel_config']['autoplay_speed'] ?? 5000;
        $this->loop = $settings['slider_config']['loop'] ?? $settings['carousel_config']['loop'] ?? true;
        $this->reset('backgroundImage');
        $this->existingBackgroundImage = $settings['background_image'] ?? null;
        $this->removeBackground = false;

        foreach (self::AVAILABLE_TABS as $key => $titles) {
            $this->tabConfig[$key] = [
                'enabled' => false,
                'title_en' => $titles['en'],
                'title_ar' => $titles['ar'],
            ];
        }

        if (isset($settings['groups'])) {
            foreach ($settings['groups'] as $group) {
                if (isset($this->tabConfig[$group['key']])) {
                    $this->tabConfig[$group['key']]['enabled'] = true;
                    $this->tabConfig[$group['key']]['title_en'] = $group['title']['en'] ?? $this->tabConfig[$group['key']]['title_en'];
                    $this->tabConfig[$group['key']]['title_ar'] = $group['title']['ar'] ?? $this->tabConfig[$group['key']]['title_ar'];
                }
            }
        }

        $this->showOffcanvas = true;
    }

    public function close()
    {
        $this->showOffcanvas = false;
        $this->reset();
    }


    protected function rules()
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'key' => 'nullable|string|max:100',
            'location' => 'required|string|max:50',
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'sort_order' => 'required|integer|min:0',
            'tabConfig' => 'array',
            'tabConfig.*.enabled' => 'boolean',
            'tabConfig.*.title_en' => 'required_if:tabConfig.*.enabled,true|string',
            'tabConfig.*.title_ar' => 'required_if:tabConfig.*.enabled,true|string',
            'backgroundImage' => 'nullable|image|max:5120',
        ];
    }

    public function updatedBackgroundImage()
    {
        $this->removeBackground = false;
    }

    public function removeBackground()
    {
        $this->reset('backgroundImage');
        $this->removeBackground = true;
    }

    public function update(UpdatePlacementAction $action, AdPlacementRepository $repo)
    {
        $this->validate();

        $placement = $repo->findByCols(['id' => $this->placementId]);
        if (!$placement) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('admin::lang.Placement not found'),
            ]);
            return;
        }

        $previousBackgroundPath = $placement->settings['background_image'] ?? null;
        $backgroundPath = $this->removeBackground ? null : $previousBackgroundPath;
        $uploadedBackgroundPath = null;

        if ($this->backgroundImage) {
            $uploadedBackgroundPath = $this->backgroundImage->store('ad-placements/backgrounds', 'fileDisk');
            if (!$uploadedBackgroundPath) {
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => __('admin::lang.Error uploading image'),
                ]);
                return;
            }

            $backgroundPath = $uploadedBackgroundPath;
        }

        $settings = $this->buildSettings($backgroundPath);

        $data = [
            'name' => [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ],
            'key' => $this->key,
            'location' => $this->location,
            'settings' => $settings,
            'status' => StatusEnum::from($this->status),
            'sort_order' => $this->sort_order,
        ];

        if (!$action->handle($this->placementId, $data)) {
            if ($uploadedBackgroundPath) {
                Storage::disk('fileDisk')->delete($uploadedBackgroundPath);
            }

            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('admin::lang.Error updating placement'),
            ]);
            return;
        }

        if ($previousBackgroundPath && $previousBackgroundPath !== $backgroundPath) {
            Storage::disk('fileDisk')->delete($previousBackgroundPath);
        }

        foreach (array_unique(['ar', 'en', app()->getLocale(), config('app.fallback_locale')]) as $locale) {
            if ($locale) {
                Cache::forget('home_page_data_' . $locale);
            }
        }

        $this->emit('placement-updated');
        $this->emit('show-toast', ['type' => 'success', 'message' => __('admin::home.Updated Successfully')]);
        $this->close();
    }

    protected function buildSettings(?string $backgroundPath = null): array
    {
        $type = PlacementTypeEnum::from($this->type);
        $settings = [];

        if (!in_array($type, [PlacementTypeEnum::PRODUCT_TABS, PlacementTypeEnum::PRODUCT_SLIDER, PlacementTypeEnum::PRODUCT_SIDEBAR])) {
            $settings['display'] = [
                'devices' => [
                    'desktop' => ['enabled' => true],
                    'tablet' => ['enabled' => true],
                    'mobile' => ['enabled' => true],
                ],
                'responsive' => true,
            ];

            $settings['actions'] = [
                'click_enabled' => true,
                'allowed_types' => ['url', 'product', 'category', 'merchant', 'none'],
            ];
        }

        if ($type === PlacementTypeEnum::SLIDER || $type === PlacementTypeEnum::CAROUSEL || $type === PlacementTypeEnum::PRODUCT_SLIDER) {
            $settings['slider_config'] = [
                'max_items' => $this->max_items,
                'autoplay' => $this->autoplay,
                'autoplay_speed' => $this->autoplay_speed,
                'loop' => $this->loop,
                'show_arrows' => true,
                'show_dots' => true,
            ];
        }

        if ($type === PlacementTypeEnum::GRID_BANNERS) {
            $settings['grid_config'] = [
                'columns' => ['desktop' => 4, 'tablet' => 2, 'mobile' => 1],
                'gap' => 20,
                'max_items' => $this->max_items ?? 8,
            ];
        }

        if ($type === PlacementTypeEnum::PRODUCT_SIDEBAR) {
            $settings['widget_config'] = [
                'max_items' => $this->max_items ?? 5,
            ];
        }

        if ($type === PlacementTypeEnum::PRODUCT_TABS) {
            $finalGroups = [];
            foreach ($this->tabConfig as $key => $config) {
                if ($config['enabled']) {
                    $finalGroups[] = [
                        'key' => $key,
                        'title' => [
                            'en' => $config['title_en'],
                            'ar' => $config['title_ar'],
                        ]
                    ];
                }
            }
            $settings['groups'] = $finalGroups;
        }

        if ($backgroundPath) {
            $settings['background_image'] = $backgroundPath;
        }

        return $settings;
    }

    public function render()
    {
        return view('admin::livewire.ad-placement.modal.update');
    }
}
