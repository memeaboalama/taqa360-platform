<?php

namespace Application\Admin\Livewire\BannerSpace\Modal;

use App\Domain\AppBase\Actions\AdPlacement\UpdatePlacementAction;
use App\Domain\AppBase\Models\AdPlacement;
use App\Support\Enum\PlacementTypeEnum;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateSpace extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public $placementId;
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
    public $backgroundImage;
    public $existingBackgroundImage;
    public $removeBackground = false;

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

    protected $listeners = ['openUpdateSpaceModal'];

    public function openUpdateSpaceModal($placementId)
    {
        $this->placementId = $placementId;
        $this->loadPlacement();
        $this->emit('openModal', '#updateSpaceModal');
    }

    public function loadPlacement()
    {
        $placement = AdPlacement::findOrFail($this->placementId);

        $this->pageId = $placement->page_id;
        $this->name_en = $placement->getTranslation('name', 'en');
        $this->name_ar = $placement->getTranslation('name', 'ar');
        $this->key = $placement->key;
        $this->location = $placement->location;
        $this->type = $placement->type->value;
        $this->status = $placement->status->value;
        $this->sort_order = $placement->sort_order;

        $settings = $placement->settings ?? [];
        $this->reset('backgroundImage');
        $this->existingBackgroundImage = $settings['background_image'] ?? null;
        $this->removeBackground = false;

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
    }

    protected function rules()
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'key' => 'nullable|string|max:100',
            'location' => 'required|string|max:50',
            'type' => 'required|in:' . implode(',', PlacementTypeEnum::values()),
            'status' => 'required|in:' . implode(',', StatusEnum::values()),
            'sort_order' => 'required|integer|min:0',
            'autoplay_speed' => 'nullable|integer|min:100',
            'transition_speed' => 'nullable|integer|min:100',
            'loop' => 'boolean',
            'autoplay' => 'boolean',
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

    public function update(UpdatePlacementAction $action)
    {
        $this->validate();

        $placement = AdPlacement::findOrFail($this->placementId);
        $previousBackgroundPath = $placement->settings['background_image'] ?? null;
        $backgroundPath = $this->removeBackground ? null : $previousBackgroundPath;
        $uploadedBackgroundPath = null;

        if ($this->backgroundImage) {
            $uploadedBackgroundPath = $this->backgroundImage->store('ad-placements/backgrounds', 'fileDisk');
            if (!$uploadedBackgroundPath) {
                $this->alert('error', __('admin::lang.Error uploading image'));
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
            'type' => PlacementTypeEnum::from($this->type),
            'settings' => $settings,
            'status' => StatusEnum::from($this->status),
            'sort_order' => $this->sort_order,
        ];

        DB::beginTransaction();
        if (!$action->handle($this->placementId, $data)) {
            DB::rollBack();
            if ($uploadedBackgroundPath) {
                Storage::disk('fileDisk')->delete($uploadedBackgroundPath);
            }
            $this->alert('error', __('admin::lang.Error updating placement'));
            return;
        }
        DB::commit();

        if ($previousBackgroundPath && $previousBackgroundPath !== $backgroundPath) {
            Storage::disk('fileDisk')->delete($previousBackgroundPath);
        }

        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_ar');

        $this->emit('refreshSpaces');
        $this->emit('closeModal', '#updateSpaceModal');
        $this->alert('success', __('admin::home.Updated Successfully'));
    }

    protected function buildSettings(?string $backgroundPath = null): array
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

        if ($backgroundPath) {
            $settings['background_image'] = $backgroundPath;
        }

        return $settings;
    }

    public function render()
    {
        return view('admin::livewire.banner-space.modal.update-space');
    }
}
