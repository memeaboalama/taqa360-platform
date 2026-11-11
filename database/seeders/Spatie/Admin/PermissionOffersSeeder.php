<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionOffersSeeder extends Seeder
{
    public function __construct(private PermissionRepository $permissionRepository)
    {
    }

    public function run(): void
    {
        $permissions = [
            ['en' => 'show', 'ar' => 'عرض'],
            ['en' => 'create', 'ar' => 'إنشاء'],
            ['en' => 'edit', 'ar' => 'تعديل'],
            ['en' => 'delete', 'ar' => 'حذف'],
        ];

        foreach ($permissions as $permission) {
            $this->permissionRepository->store([
                'team_type' => Admin::class,
                'name' => $permission['en'] . '_offers',
                'name_ar' => $permission['ar'] . '_العروض',
                'guard_name' => 'admin',
                'group_name' => 'offers',
                'group_name_ar' => 'العروض',
            ]);
        }
    }
}
