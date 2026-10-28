<?php

namespace Database\Seeders\AppBase;

use App\Domain\Otp\Models\OtpSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtpSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('otp_setting')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        OtpSetting::firstOrCreate([
            'user_name' => 'Eats',
            'api_key' => 'D9hpJli5FUU7BgJsgEpdz4hS0kiFR',
            'sender_id' => 'Eats',
            'service_name' => 'unifonic',
            'default' => true,
        ]);
    }
}
