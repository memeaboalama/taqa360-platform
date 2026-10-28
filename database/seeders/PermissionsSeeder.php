<?php

namespace Database\Seeders;

use Database\Seeders\Spatie\Admin\PermissionAdminGeneralSeeder;
use Database\Seeders\Spatie\Admin\PermissionBannersSeeder;
use Database\Seeders\Spatie\Admin\PermissionBrandsSeeder;
use Database\Seeders\Spatie\Admin\PermissionCarTypesSeeder;
use Database\Seeders\Spatie\Admin\PermissionCategoriesSeeder;
use Database\Seeders\Spatie\Admin\PermissionCitiesSeeder;
use Database\Seeders\Spatie\Admin\PermissionCountriesSeeder;
use Database\Seeders\Spatie\Admin\PermissionRegionsSeeder;
use Database\Seeders\Spatie\Admin\PermissionCompaniesSeeder;
use Database\Seeders\Spatie\Admin\PermissionCouponsSeeder;
use Database\Seeders\Spatie\Admin\PermissionCustomersSeeder;
use Database\Seeders\Spatie\Admin\PermissionDistrictsSeeder;
use Database\Seeders\Spatie\Admin\PermissionDriversSeeder;
use Database\Seeders\Spatie\Admin\PermissionEmployeesSeeder;
use Database\Seeders\Spatie\Admin\PermissionMerchantCouponsSeeder;
use Database\Seeders\Spatie\Admin\PermissionMerchantPackagesSeeder;
use Database\Seeders\Spatie\Admin\PermissionMerchantsSeeder;
use Database\Seeders\Spatie\Admin\PermissionMessagesSeeder;
use Database\Seeders\Spatie\Admin\PermissionOrdersSeeder;
use Database\Seeders\Spatie\Admin\PermissionPrivacyPoliciesSeeder;
use Database\Seeders\Spatie\Admin\PermissionReportsSeeder;
use Database\Seeders\Spatie\Admin\PermissionRolesSeeder;
use Database\Seeders\Spatie\Admin\PermissionSalesAgentSeeder;
use Database\Seeders\Spatie\Admin\PermissionSettingsSeeder;
use Database\Seeders\Spatie\Admin\PermissionTagsSeeder;
use Database\Seeders\Spatie\Admin\PermissionTermsSeeder;
use Database\Seeders\Spatie\Admin\PermissionWalletCouponsSeeder;
use Database\Seeders\Spatie\Admin\PermissionWalletSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionAdditionGroupSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionBranchSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionChoiceSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionEmployeeSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionGeneralSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionProfileSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionRoleSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionMenuSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;


class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call([
            /** SPATIE MARKETPLACE */
            PermissionMenuSeeder::class,
            PermissionChoiceSeeder::class,
            PermissionAdditionGroupSeeder::class,
            PermissionRoleSeeder::class,
            PermissionEmployeeSeeder::class,
            PermissionGeneralSeeder::class,
            PermissionBranchSeeder::class,
            PermissionProfileSeeder::class,
            /** END SPATIE MARKETPLACE */

            /** SPATIE ADMIN */
            PermissionBannersSeeder::class,
            PermissionCategoriesSeeder::class,
            PermissionBrandsSeeder::class,
            PermissionCarTypesSeeder::class,
            PermissionTagsSeeder::class,
            PermissionCitiesSeeder::class,
            PermissionCountriesSeeder::class,
            PermissionRegionsSeeder::class,
            PermissionCompaniesSeeder::class,
            PermissionCouponsSeeder::class,
            PermissionMerchantCouponsSeeder::class,
            PermissionDistrictsSeeder::class,
            PermissionDriversSeeder::class,
            PermissionEmployeesSeeder::class,
            PermissionCustomersSeeder::class,
            PermissionMerchantsSeeder::class,
            PermissionMerchantPackagesSeeder::class,
            PermissionRolesSeeder::class,
            PermissionMessagesSeeder::class,
            PermissionOrdersSeeder::class,
            PermissionPrivacyPoliciesSeeder::class,
            PermissionTermsSeeder::class,
            PermissionAdminGeneralSeeder::class,
            PermissionReportsSeeder::class,
            PermissionSettingsSeeder::class,
            PermissionSalesAgentSeeder::class,
            PermissionWalletSeeder::class,
            PermissionWalletCouponsSeeder::class,

            /** END SPATIE ADMIN */

            /** SPATIE COMPANY */
            \Database\Seeders\Spatie\Company\PermissionDriversSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionEmployeesSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionRolesSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionOrdersSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionGeneralSeeder::class,
            /** END SPATIE COMPANY */

        ]);
    }
}
