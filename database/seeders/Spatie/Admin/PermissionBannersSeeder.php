<?php

namespace Database\Seeders\Spatie\Admin;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;

class PermissionBannersSeeder extends Seeder
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
                'en'=> 'show',
                'ar'=> 'عرض'
            ],
            [
                'en'=> 'create',
                'ar'=> 'إنشاء'
            ],
            [
                'en'=> 'edit',
                'ar'=> 'تعديل'
            ],
            [
                'en'=> 'delete',
                'ar'=> 'حذف'
            ],
        ];

        $group_name = ['en' => 'banners', 'ar' => "اللافتات"];

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
