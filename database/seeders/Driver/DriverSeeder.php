<?php

namespace Database\Seeders\Driver;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Models\CompanyProviderVehicle;
use Domain\Driver\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('drivers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $driver = Driver::create([
            'name' => 'driver',
            'email' => 'driver@gmail.com',
            'phone' => '555222222',
            'password' => "password",
            "location" => new Point(24.714969, 46.6805916, 4326),

        ]);

        $driver = Driver::create([
            'name' => 'driver1',
            'email' => 'driver1@gmail.com',
            'phone' => '555222221',
            'password' => "password",
            "location" => new Point(24.714969, 46.6805916, 4326),

        ]);

        $driver = Driver::create([
            'name' => 'driver2',
            'email' => 'driver2@gmail.com',
            'phone' => '555222223',
            'password' => "password",
            "location" => new Point(24.714969, 46.6805916, 4326),

        ]);
    }
}
