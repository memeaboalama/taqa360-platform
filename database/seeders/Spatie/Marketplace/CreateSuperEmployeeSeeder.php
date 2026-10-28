<?php

namespace Database\Seeders\Spatie\Marketplace;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Support\Repositories\Spatie\PermissionRepository;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSuperEmployeeSeeder extends Seeder
{
    public $permissionRepository;
    public $merchantEmployeeRepository;
    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository, PermissionRepository $permissionRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        MerchantEmployee::truncate();
        Schema::enableForeignKeyConstraints();
        $employee = $this->merchantEmployeeRepository->store([
            'name' => 'Super Employee',
            'email' => 'super@emp.com',
            'password' => bcrypt('password'),
            'country_name' => "Saudi Arabia ,(المملكة العربية السعودية)",
            'country_code' => "sa",
            'dialcode' => "966",
            'status' => "active",
            'merchant_id' => 1,
            'country_id' => 1,
        ]);
        $role = Role::create([
            'name' => 'Super Admin'
            ,'guard_name' => "merchant_employee"
            ,'team_id' => 1
        ]);
        $permissions = $this->permissionRepository->getIds(Merchant::class);

        $role->syncPermissions($permissions);

        $employee->assignRole([$role->id]);
    }
}
