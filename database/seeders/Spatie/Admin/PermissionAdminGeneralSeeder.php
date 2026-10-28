<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionAdminGeneralSeeder extends Seeder
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
                'en'=> 'show homepage',
                'ar'=> 'عرض الصفحة الرئيسية'
            ],

            [
                'en'=> 'send notifications',
                'ar'=> 'ارسال الاشعارات'
            ],
        ];

        $group_name = ['en' => 'general', 'ar' => "عام"];

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
