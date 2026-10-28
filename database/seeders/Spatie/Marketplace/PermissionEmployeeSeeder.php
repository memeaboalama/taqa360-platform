<?php

namespace Database\Seeders\Spatie\Marketplace;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;

class PermissionEmployeeSeeder extends Seeder
{
    public $team_type;
    public $permissionRepository;
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->team_type = Merchant::class;
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

        $group_name = ['en' => 'employees', 'ar' => "الموظفين"];

        foreach ($permissions as $key => $permission) {
            $this->permissionRepository->store([
                'team_type' => $this->team_type
                ,'name' => $permission['en'] . '_' .$group_name['en']
                ,'name_ar' => $permission['ar']. '_' .$group_name['ar']
                ,'guard_name'=> 'merchant_employee'
                ,'group_name'=>$group_name['en']
                ,'group_name_ar'=>$group_name['ar']
            ]);
        }
    }
}
