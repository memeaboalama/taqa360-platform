<?php

namespace Database\Seeders\Merchant;

use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Illuminate\Database\Seeder;

class MarketPlaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['en' => 'Renewable Energy', 'ar' => 'الطاقة المتجددة'],
            ['en' => 'Solar Energy', 'ar' => 'الطاقة الشمسية'],
            ['en' => 'Batteries', 'ar' => 'البطاريات'],
            ['en' => 'Inverters', 'ar' => 'المحولات'],
            ['en' => 'Wind Energy', 'ar' => 'طاقة الرياح'],
            ['en' => 'Electrical Equipment', 'ar' => 'معدات كهربائية'],
            ['en' => 'Water Pumps', 'ar' => 'مضخات المياه'],
            ['en' => 'Safety Equipment', 'ar' => 'معدات السلامة'],
            ['en' => 'Building Materials', 'ar' => 'مواد البناء'],
            ['en' => 'Thermal Insulation', 'ar' => 'العزل الحراري'],
        ];

        $existingTitles = MarketPlaceCategory::query()->get()
            ->map(static fn (MarketPlaceCategory $category): string => $category->getTranslation('title', 'en'))
            ->all();

        foreach ($categories as $index => $title) {
            if (in_array($title['en'], $existingTitles, true)) {
                continue;
            }

            MarketPlaceCategory::create([
                'title' => $title,
                'status' => StatusEnum::ACTIVE->value,
                'sort' => $index + 1,
            ]);

            $existingTitles[] = $title['en'];
        }
    }
}
