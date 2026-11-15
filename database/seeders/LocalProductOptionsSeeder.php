<?php

namespace Database\Seeders;

use App\Domain\Catalog\Models\Specification;
use App\Domain\MarketPlace\Models\Brand;
use Domain\MarketPlace\Models\Catalog\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalProductOptionsSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            throw new \RuntimeException('LocalProductOptionsSeeder may only be run in the local environment.');
        }

        DB::transaction(function (): void {
            $cableCategory = Category::where('slug', 'Cables')->firstOrFail();

            $brand = Brand::withTrashed()
                ->whereJsonContains('tags', 'local_product_form_options')
                ->first() ?? new Brand();
            $brand->fill([
                'name' => [
                    'ar' => 'علامة تجارية تجريبية للكابلات',
                    'en' => 'Local Test Cable Brand',
                ],
                'description' => [
                    'ar' => 'بيانات محلية لاختبار نموذج إضافة المنتجات فقط.',
                    'en' => 'Local-only data for testing the product form.',
                ],
                'tags' => ['local_product_form_options'],
                'is_active' => true,
            ]);
            if ($brand->trashed()) {
                $brand->restore();
            }
            $brand->save();
            $brand->categories()->sync([$cableCategory->id]);

            $specification = Specification::firstOrNew(['code' => 'local-cable-length-options']);
            $specification->fill([
                'item_id' => null,
                'name' => 'طول الكابل',
                'description' => 'خيارات أطوال للاختبار المحلي.',
                'status' => 'active',
                'meta' => ['local_test_fixture' => true],
            ]);
            $specification->save();
            $specification->properties()->delete();
            $specification->properties()->createMany([
                ['code' => 'local-cable-length-5m', 'name' => '5 أمتار', 'value' => '5 م', 'status' => 'active', 'meta' => ['local_test_fixture' => true]],
                ['code' => 'local-cable-length-10m', 'name' => '10 أمتار', 'value' => '10 م', 'status' => 'active', 'meta' => ['local_test_fixture' => true]],
                ['code' => 'local-cable-length-20m', 'name' => '20 مترًا', 'value' => '20 م', 'status' => 'active', 'meta' => ['local_test_fixture' => true]],
            ]);
            $specification->categories()->sync([$cableCategory->id]);
        });
    }
}
