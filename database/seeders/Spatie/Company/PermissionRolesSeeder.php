<?php

namespace Database\Seeders\Spatie\Company;

use App\Domain\Provider\Models\Company;
use App\Support\Repositories\Spatie\PermissionRepository;
use Illuminate\Database\Seeder;

class PermissionRolesSeeder extends Seeder
{
    public $team_type;
    public $permissionRepository;
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->team_type = Company::class;
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
        ];

        $group_name = ['en' => 'roles', 'ar' => "الأدوار"];

        foreach ($permissions as $key => $permission) {
            $this->permissionRepository->store([
                'team_type' => $this->team_type
                ,'name' => $permission['en'] . '_' .$group_name['en']
                ,'name_ar' => $permission['ar']. '_' .$group_name['ar']
                ,'guard_name'=> 'company_employee'
                ,'group_name'=>$group_name['en']
                ,'group_name_ar'=>$group_name['ar']
            ]);
        }
    }
}
