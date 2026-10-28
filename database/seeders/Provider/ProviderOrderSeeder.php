<?php

namespace Database\Seeders\Provider;

use App\Domain\Provider\Models\ProviderOrder;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ProviderOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('provider_orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        ProviderOrder::create(
            [
                'user_id' => 1,
                'company_id' => 1,
                'provider_id' => 1,
                'provider_sub_services_id' => 1,
                'provider_vehicle_id' => 1,
                'provider_vehicle_plate_number' => '123AA',
                'provider_vehicle_type' => 'towing',
                'payment_type' => 'cash',
                'collected_price_by_provider' => 1.1,
                'total_order' => 120.00,
                'vat' => 11.00,
                'total_after_vat' => 10.00,
                'client_name' => 'ahmed',
                'client_full_phone' => '01011982831',
                'provider_name' => 'proivder name',
                'company_name' => 'companyName',
                'client_location' => new Point(29.9898014, 31.2529412, 4326),
                'date' => Carbon::tomorrow(),
                'from' => '12:00',
                'to' => '14:00',
                'status' => 'pending',
            ]
        );

    }
}
