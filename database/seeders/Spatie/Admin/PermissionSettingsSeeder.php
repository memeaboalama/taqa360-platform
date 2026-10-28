<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionSettingsSeeder extends Seeder
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
                'en'=> 'show profile',
                'ar'=> 'عرض الملف الشخصي'
            ],
            [
                'en'=> 'edit profile',
                'ar'=> 'تعديل الملف الشخصي'
            ],

            [
                'en'=> 'show login/ register page content',
                'ar'=> 'عرض محتوي صفحات التسجيل'
            ],
            [
                'en'=> 'edit login/ register page content',
                'ar'=> 'تعديل محتوي صفحات التسجيل'
            ],

            [
                'en'=> 'show search capacity',
                'ar'=> 'عرض سعة البحث'
            ],
            [
                'en'=> 'edit search capacity',
                'ar'=> 'تعديل سعة البحث'
            ],

            [
                'en'=> 'show delivery fees',
                'ar'=> 'عرض رسوم التوصيل'
            ],
            [
                'en'=> 'edit delivery fees',
                'ar'=> 'تعديل رسوم التوصيل'
            ],
        ];

        $group_name = ['en' => 'settings', 'ar' => "الأعدادات"];

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
