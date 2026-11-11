<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionNotificationsSeeder extends Seeder
{
    public function __construct(private PermissionRepository $permissionRepository)
    {
    }

    public function run(): void
    {
        $permissions = [
            ['en' => 'show', 'ar' => 'عرض'],
            ['en' => 'send', 'ar' => 'إرسال'],
        ];

        foreach ($permissions as $permission) {
            $this->permissionRepository->store([
                'team_type' => Admin::class,
                'name' => $permission['en'] . '_notifications',
                'name_ar' => $permission['ar'] . '_الإشعارات',
                'guard_name' => 'admin',
                'group_name' => 'notifications',
                'group_name_ar' => 'الإشعارات',
            ]);
        }
    }
}
