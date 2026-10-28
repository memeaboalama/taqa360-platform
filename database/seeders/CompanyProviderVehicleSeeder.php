<?php

namespace Database\Seeders;

use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\CompanyProviderVehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyProviderVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('company_provider_vehicles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $company = Company::first();
        CompanyProviderVehicle::create([
            'company_id'    =>  $company->id,
            'provider_id'   =>  $company->providers->first()?->id,
            'vehicle_id'    =>  $company->vehicles->first()?->id,
        ]);

    }
}
