<?php

namespace Database\Seeders\Spatie\Marketplace;

use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;

class PermissionProfileSeeder extends Seeder
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
                'en'=> 'show basic info',
                'ar'=> 'عرض البيانات الاساسية'
            ],
            [
                'en'=> 'edit basic info',
                'ar'=> 'تعديل البيانات الاساسية'
            ],
            [
                'en'=> 'show files',
                'ar'=> 'عرض الملفات'
            ],

            [
                'en'=> 'edit files',
                'ar'=> 'تعديل الملفات'
            ],

            [
                'en'=> 'show iban info',
                'ar'=> 'عرض بيانات الآيبان'
            ],

            [
                'en'=> 'edit iban info',
                'ar'=> 'تعديل بيانات الآيبان'
            ],

            [
                'en'=> 'show packages',
                'ar'=> 'عرض الباقات'
            ],

            [
                'en'=> 'choose packages',
                'ar'=> 'اختيار الباقات'
            ],
        ];

        $group_name = ['en' => 'profile', 'ar' => "الملف الشخصي"];

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
