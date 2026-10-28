<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\AppBase\BankSeeder;
use Database\Seeders\AppBase\CurrencySeeder;
use Database\Seeders\AppBase\DayTableSeeder;
use Database\Seeders\AppBase\LanguageSeeder;
use Database\Seeders\AppBase\MainCategorySeeder;
use Database\Seeders\AppBase\NationalitySeeder;
use Database\Seeders\AppBase\OtpSettingSeeder;
use Database\Seeders\AppBase\PackageSeeder;
use Database\Seeders\AppBase\TypeServiceSeeder;
use Database\Seeders\AppBase\WorkDayTableSeeder;
use Database\Seeders\Car\BrandSeeder;
use Database\Seeders\Car\CarSeeder;
use Database\Seeders\Car\CarsTypeSeeder;
use Database\Seeders\Car\ModelSeeder;
use Database\Seeders\Driver\DriverSeeder;
use Database\Seeders\ecommerce\CategorySeeder;
use Database\Seeders\ecommerce\ChildCategorySeeder;
use Database\Seeders\ecommerce\SubCategorySeeder;
use Database\Seeders\Educator\AcademicMajorSeeder;
use Database\Seeders\Educator\ServiceSeeder;
use Database\Seeders\Educator\SkillSeeder;
use Database\Seeders\Location\CitySeeder;
use Database\Seeders\Location\CountrySeeder;
use Database\Seeders\Location\DistractSeeder;
use Database\Seeders\Location\RegionSeeder;
use Database\Seeders\Merchant\MarketPlaceCategorySeeder;
use Database\Seeders\Merchant\MerchantAddonCategorySeeder;
use Database\Seeders\Merchant\MerchantAddonSeeder;
use Database\Seeders\Merchant\MerchantBranchSeeder;
use Database\Seeders\Merchant\MerchantCategorySeeder;
use Database\Seeders\Merchant\MerchantEmployeeSeeder;
use Database\Seeders\Merchant\MerchantOrderDetailsSeeder;
use Database\Seeders\Merchant\MerchantOrderSeeder;
use Database\Seeders\Merchant\MerchantOrderServiceSeeder;
use Database\Seeders\Merchant\MerchantSeeder;
use Database\Seeders\Merchant\MerchantServiceSeeder;
use Database\Seeders\Provider\CompanyProviderVehicleSeeder;
use Database\Seeders\Provider\CompanySeeder;
use Database\Seeders\Provider\ProviderCategorySeeder;
use Database\Seeders\Provider\ProviderMainServiceSeeder;
use Database\Seeders\Provider\ProviderOrderSeeder;
use Database\Seeders\Provider\ProviderSeeder;
use Database\Seeders\Provider\ProviderSubServiceSeeder;
use Database\Seeders\Spatie\Admin\PermissionAdminGeneralSeeder;
use Database\Seeders\Spatie\Admin\PermissionBannersSeeder;
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
use Database\Seeders\Spatie\Marketplace\CreateSuperEmployeeSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionAdditionGroupSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionBranchSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionCategorySeeder;
use Database\Seeders\Spatie\Marketplace\PermissionChoiceSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionContactSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionEmployeeSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionGeneralSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionHomeSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionOrderSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionProfileSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionRoleSeeder;
use Database\Seeders\Spatie\Marketplace\PermissionMenuSeeder;
use Database\Seeders\Towing\TowingCitySeeder;
use Database\Seeders\Towing\TowingLineSeeder;
use Database\Seeders\Towing\TowingTypeSeeder;
use Database\Seeders\User\UserAddressSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();
        $this->call([
            MainCategorySeeder::class,
            LanguageSeeder::class,
            OtpSettingSeeder::class,
            CurrencySeeder::class,
            TypeServiceSeeder::class,
            PackageSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            RegionSeeder::class,
            DistractSeeder::class,
            UserSeeder::class,
            UserAddressSeeder::class,
            AdminSeeder::class,
            // BrandSeeder::class,
            // ModelSeeder::class,
            // CarSeeder::class,
            CarsTypeSeeder::class,
            NationalitySeeder::class,
            // //////////////////MarketPlace////////////////
            MarketPlaceCategorySeeder::class,
            MerchantSeeder::class,
            MerchantCategorySeeder::class,
            MerchantBranchSeeder::class,
            DayTableSeeder::class,
            WorkDayTableSeeder::class,
            MerchantServiceSeeder::class,
            MerchantAddonCategorySeeder::class,
            MerchantAddonSeeder::class,
            MerchantEmployeeSeeder::class,
            MerchantOrderSeeder::class,
            MerchantOrderServiceSeeder::class,
            // ////////////////MarketPlace////////////////
            ProviderCategorySeeder::class,
            ProviderCategorySeeder::class,
            ProviderMainServiceSeeder::class,
            ProviderSubServiceSeeder::class,
            CompanySeeder::class,
            // ProviderSeeder::class,
            CompanyProviderVehicleSeeder::class,
            // ProviderOrderSeeder::class,
            // /** SPATIE MARKETPLACE */
            PermissionMenuSeeder::class,
            PermissionChoiceSeeder::class,
            PermissionAdditionGroupSeeder::class,
            PermissionRoleSeeder::class,
            PermissionEmployeeSeeder::class,
            PermissionGeneralSeeder::class,
            PermissionBranchSeeder::class,
            PermissionProfileSeeder::class,
            // /** END SPATIE MARKETPLACE */

            // /** SPATIE ADMIN */
            PermissionBannersSeeder::class,
            PermissionCategoriesSeeder::class,
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

            // /** END SPATIE ADMIN */

            // /** SPATIE COMPANY */
            \Database\Seeders\Spatie\Company\PermissionDriversSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionEmployeesSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionRolesSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionOrdersSeeder::class,
            \Database\Seeders\Spatie\Company\PermissionGeneralSeeder::class,
            // /** END SPATIE COMPANY */


            //    PermissionCategorySeeder::class,
            //    PermissionEmployeeSeeder::class,
            //    PermissionOrderSeeder::class,
            //    PermissionRoleSeeder::class,
            //    PermissionServiceSeeder::class,
            //    DriverSeeder::class,
            //    BankSeeder::class,
            //    CreateSuperEmployeeSeeder::class,

            // Edcutore
            AcademicMajorSeeder::class,
            ServiceSeeder::class,
            SkillSeeder::class,

            // Ecommerce
            CategorySeeder::class,
            SubCategorySeeder::class,
            ChildCategorySeeder::class,


            // Home Page Sections
            AdPagesSeeder::class,
            AdProductPlacementsSeeder::class,
            AdBannerPlacementsSeeder::class,
            AdContentSeeder::class,
            MerchantSliderSeeder::class,
            FooterPlacementSeeder::class,
            HeaderContentSeeder::class,

        ]);

    }
}
