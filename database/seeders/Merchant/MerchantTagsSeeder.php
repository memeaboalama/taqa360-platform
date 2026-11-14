<?php

namespace Database\Seeders\Merchant;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Support\Enum\MainCategoryType;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantTagsSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            'renewable_energy' => ['ar' => 'الطاقة المتجددة', 'en' => 'Renewable Energy'],
            'solar_energy' => ['ar' => 'الطاقة الشمسية', 'en' => 'Solar Energy'],
            'batteries' => ['ar' => 'البطاريات', 'en' => 'Batteries'],
            'inverters' => ['ar' => 'المحولات', 'en' => 'Inverters'],
            'wind_energy' => ['ar' => 'طاقة الرياح', 'en' => 'Wind Energy'],
            'electrical_equipment' => ['ar' => 'معدات كهربائية', 'en' => 'Electrical Equipment'],
            'water_pumps' => ['ar' => 'مضخات المياه', 'en' => 'Water Pumps'],
            'safety_equipment' => ['ar' => 'معدات السلامة', 'en' => 'Safety Equipment'],
            'building_materials' => ['ar' => 'مواد البناء', 'en' => 'Building Materials'],
            'insulation' => ['ar' => 'العزل الحراري', 'en' => 'Thermal Insulation'],
        ];

        $tagIds = [];
        foreach ($definitions as $key => $title) {
            $tag = MainCategory::query()
                ->where('type', MainCategoryType::MARKET->value)
                ->where('title->en', $title['en'])
                ->first();

            if (!$tag && $key === 'renewable_energy') {
                $tag = MainCategory::query()
                    ->where('type', MainCategoryType::MARKET->value)
                    ->where('title->en', 'Category3')
                    ->first();
            }

            if ($tag) {
                if ($tag->getTranslation('title', 'en') !== $title['en']) {
                    $tag->title = $title;
                    $tag->save();
                }
            } else {
                $tag = MainCategory::create([
                    'title' => $title,
                    'type' => MainCategoryType::MARKET->value,
                    'status' => StatusEnum::ACTIVE->value,
                ]);
            }

            $tagIds[$key] = $tag->status === StatusEnum::ACTIVE ? $tag->id : null;
        }

        $assignments = [
            'demo.solar@demo.local' => ['renewable_energy', 'solar_energy', 'batteries', 'inverters'],
            'demo.wind@demo.local' => ['renewable_energy', 'wind_energy', 'electrical_equipment', 'water_pumps'],
            'demo.safety@demo.local' => ['safety_equipment', 'building_materials', 'insulation'],
        ];

        $merchants = Merchant::query()
            ->whereIn('email', array_keys($assignments))
            ->get()
            ->keyBy('email');

        foreach ($assignments as $email => $keys) {
            $merchant = $merchants->get($email);
            if (!$merchant) {
                continue;
            }

            $existingIds = array_filter(
                array_map('intval', explode(',', (string) $merchant->main_categories)),
                fn ($id) => $id > 0
            );
            $defaultIds = array_filter(array_map(fn ($key) => $tagIds[$key], $keys));
            $merchant->update([
                'main_categories' => implode(',', array_values(array_unique(array_merge($existingIds, $defaultIds)))),
            ]);
        }
    }
}
