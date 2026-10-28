<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionReportsSeeder extends Seeder
{
    public $team_type;
    public $permissionRepository;
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->team_type = Admin::class;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $permissions = [
            [
                'en'=> 'show merchant order report',
                'ar'=> 'عرض تقرير طلبات التاجر'
            ],

            [
                'en'=> 'export merchant order report',
                'ar'=> 'تصدير تقرير طلبات التاجر'
            ],

            [
                'en'=> 'show driver order report',
                'ar'=> 'عرض تقرير طلبات السائقيين'
            ],

            [
                'en'=> 'export driver order report',
                'ar'=> 'تصدير تقرير طلبات السائقيين'
            ],

            [
                'en'=> 'show invoice delivery report',
                'ar'=> 'عرض تقرير فواتير التوصيل '
            ],

        ];

        $group_name = ['en' => 'reports', 'ar' => "التقارير"];

        foreach ($permissions as $key => $permission) {
            $this->permissionRepository->store([
                'team_type' => $this->team_type
                ,'name' => $permission['en'] . '_' .$group_name['en']
                ,'name_ar' => $permission['ar']. '_' .$group_name['ar']
                ,'guard_name'=> 'admin'
                ,'group_name'=>$group_name['en']
                ,'group_name_ar'=>$group_name['ar']
            ]);
        }
    }
}
