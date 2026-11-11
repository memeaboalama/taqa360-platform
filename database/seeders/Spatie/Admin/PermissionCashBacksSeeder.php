<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionCashBacksSeeder extends Seeder
{
    public function __construct(private PermissionRepository $permissionRepository)
    {
    }

    public function run(): void
    {
        $groups = [
            [
                'name' => 'cashbacks',
                'name_ar' => 'الكاش باك',
                'permissions' => [
                    ['en' => 'show', 'ar' => 'عرض'],
                    ['en' => 'create', 'ar' => 'إنشاء'],
                    ['en' => 'edit', 'ar' => 'تعديل'],
                    ['en' => 'delete', 'ar' => 'حذف'],
                    ['en' => 'customize merchants', 'ar' => 'تخصيص المتاجر'],
                ],
            ],
            [
                'name' => 'merchant cashbacks',
                'name_ar' => 'كاش باك المتاجر',
                'permissions' => [
                    ['en' => 'show', 'ar' => 'عرض'],
                    ['en' => 'edit', 'ar' => 'تعديل'],
                    ['en' => 'delete', 'ar' => 'حذف'],
                ],
            ],
        ];

        foreach ($groups as $group) {
            foreach ($group['permissions'] as $permission) {
                $this->permissionRepository->store([
                    'team_type' => Admin::class,
                    'name' => $permission['en'] . '_' . $group['name'],
                    'name_ar' => $permission['ar'] . '_' . $group['name_ar'],
                    'guard_name' => 'admin',
                    'group_name' => $group['name'],
                    'group_name_ar' => $group['name_ar'],
                ]);
            }
        }
    }
}
