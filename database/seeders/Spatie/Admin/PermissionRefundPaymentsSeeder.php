<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionRefundPaymentsSeeder extends Seeder
{
    public function __construct(private PermissionRepository $permissionRepository)
    {
    }

    public function run(): void
    {
        $permissions = [
            ['en' => 'show', 'ar' => 'عرض'],
            ['en' => 'create', 'ar' => 'إنشاء'],
        ];

        foreach ($permissions as $permission) {
            $this->permissionRepository->store([
                'team_type' => Admin::class,
                'name' => $permission['en'] . '_refundpayments',
                'name_ar' => $permission['ar'] . '_مرتجعات الدفع',
                'guard_name' => 'admin',
                'group_name' => 'refundpayments',
                'group_name_ar' => 'مرتجعات الدفع',
            ]);
        }
    }
}
