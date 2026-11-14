<?php

namespace Database\Seeders\AppBase;

use App\Domain\AppBase\Models\PackageTypeServices;
use App\Domain\AppBase\Models\TypeService;
use App\Domain\MarketPlace\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $types = [
                1 => ['name' => 'with_delivery', 'desc' => 'مع التوصيل'],
                2 => ['name' => 'without_delivery', 'desc' => 'الاستلام من المتجر'],
            ];

            foreach ($types as $id => $attributes) {
                $typeService = TypeService::query()->firstOrNew(['id' => $id]);
                $typeService->name = $attributes['name'];
                $typeService->desc = $attributes['desc'];
                $typeService->status = 'active';
                $typeService->save();
            }

            foreach (Package::query()->where('status', 'active')->get() as $package) {
                foreach ($types as $typeId => $attributes) {
                    $packageTypeService = PackageTypeServices::query()->firstOrNew([
                        'package_id' => $package->id,
                        'type_service_id' => $typeId,
                    ]);

                    if (!$packageTypeService->exists) {
                        $packageTypeService->commission = $package->commission;
                        $packageTypeService->discount_rate = 0;
                        $packageTypeService->desc = '';
                        $packageTypeService->status = 'active';
                        $packageTypeService->save();
                    }
                }
            }
        });
    }
}
