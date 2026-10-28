<?php

namespace Database\Seeders\Finance;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\User\Models\User;
use App\Domain\User\Models\UserAddress;
use App\Support\Enum\PaymentMethod;
use App\Support\Enum\StatusEnum;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DriverInvoiceDeliverySeeder extends Seeder
{
    use WithoutModelEvents;

    private const DRIVERS = [
        ['name' => 'أحمد بن عبدالله الحربي', 'email' => 'driver.test1@test.sa', 'phone' => '501234567'],
        ['name' => 'محمد بن سعد القحطاني', 'email' => 'driver.test2@test.sa', 'phone' => '502234567'],
        ['name' => 'خالد بن فهد العتيبي', 'email' => 'driver.test3@test.sa', 'phone' => '503234567'],
        ['name' => 'عبدالرحمن بن ناصر الشمري', 'email' => 'driver.test4@test.sa', 'phone' => '504234567'],
        ['name' => 'يوسف بن عمر الدوسري', 'email' => 'driver.test5@test.sa', 'phone' => '505234567'],
        ['name' => 'سعد بن ماجد الزهراني', 'email' => 'driver.test6@test.sa', 'phone' => '506234567'],
        ['name' => 'طارق بن فيصل الغامدي', 'email' => 'driver.test7@test.sa', 'phone' => '507234567'],
        ['name' => 'سلطان بن راشد المطيري', 'email' => 'driver.test8@test.sa', 'phone' => '508234567'],
    ];

    private const RIYAD_POINTS = [
        [24.7136, 46.6753],
        [24.7500, 46.7100],
        [24.6800, 46.7200],
        [24.8000, 46.6400],
    ];

    public function run(): void
    {
        $faker = \Faker\Factory::create('ar_SA');

        $drivers = $this->seedDrivers();
        $this->seedOrders($drivers, $faker);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedDrivers(): array
    {
        $drivers = [];
        $count = 0;

        foreach (self::DRIVERS as $row) {
            $driver = Driver::firstOrNew(['email' => $row['email']]);

            if (!$driver->exists) {
                $point = self::RIYAD_POINTS[$count % count(self::RIYAD_POINTS)];

                $driver->fill([
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'dialcode' => '966',
                    'password' => Hash::make('password'),
                    'location' => new Point($point[0], $point[1], 4326),
                    'status' => StatusEnum::ACTIVE,
                    'network_status' => 1,
                    'taken_commission' => 15,
                    'is_complete' => 1,
                ])->save();

                $count++;
            }

            $drivers[] = $driver;
        }

        $this->command?->info('Drivers available: ' . count($drivers));

        return $drivers;
    }

    private function seedOrders(array $drivers, $faker): void
    {
        $merchants = Merchant::query()->limit(15)->get();
        if ($merchants->isEmpty()) {
            $this->command?->error('No merchants found. Run MerchantSeeder first.');
            return;
        }

        $branchIds = MerchantBranch::query()
            ->whereIn('merchant_id', $merchants->pluck('id'))
            ->pluck('id', 'merchant_id');

        $userIds = User::query()->pluck('id');
        if ($userIds->isEmpty()) {
            $this->command?->error('No users found. Run UserSeeder first.');
            return;
        }

        $addressId = UserAddress::query()->value('id');

        $payments = array_column(PaymentMethod::cases(), 'value');
        $otherStatuses = [
            MerchantOrderStatuses::PENDING->value,
            MerchantOrderStatuses::ON_WAY->value,
            MerchantOrderStatuses::READY->value,
            MerchantOrderStatuses::CANCELLED->value,
            MerchantOrderStatuses::UPCOMING->value,
        ];

        $created = 0;
        $prefix = 'INV-TEST-' . now()->format('YmdHis') . '-';

        for ($i = 1; $i <= 20; $i++) {
            $merchant = $merchants[$i % $merchants->count()];
            $branchId = $branchIds[$merchant->id] ?? null;
            $driver = $drivers[$i % count($drivers)];

            $subtotal = round($faker->numberBetween(2000, 45000) / 100, 2);
            $vat = round($subtotal * 0.15, 2);
            $deliveryFee = round($faker->numberBetween(1200, 6500) / 100, 2);
            $commission = $faker->randomElement([10, 15, 20]);

            MerchantOrder::create([
                'code' => $prefix . str_pad($i, 3, '0', STR_PAD_LEFT),
                'user_id' => $userIds[$i % $userIds->count()],
                'merchant_id' => $merchant->id,
                'merchant_branch_id' => $branchId,
                'address_id' => $addressId,
                'addressInOrder' => 'الرياض - حي ' . $faker->randomElement(['النرجس', 'الملقا', 'الروضة', 'العليا', 'النخيل']) . ' - شارع ' . $faker->numberBetween(1, 90),
                'order_type' => $branchId ? 'fixed' : 'mobile',
                'channel' => 'mobile',
                'dialcode' => '966',
                'schedulable' => 0,
                'date' => now()->subDays($i % 30)->toDateString(),
                'from' => '09:00:00',
                'to' => '22:00:00',
                'client_name' => $faker->name(),
                'client_phone' => '5' . $faker->numerify('########'),
                'client_lat' => (string) $faker->latitude(24.60, 24.85),
                'client_long' => (string) $faker->longitude(46.55, 46.80),
                'merchant_name' => $merchant->company_name,
                'payment_method' => $payments[array_rand($payments)],
                'payment_type' => $faker->randomElement(['Visa', 'MasterCard', 'Mada', 'Apple Pay']),
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total_after_vat' => round($subtotal + $vat, 2),
                'total_addon' => 0,
                'total_service' => 0,
                'discount' => 0,
                'total_after_discount' => round($subtotal + $vat, 2),
                'delivery_fee' => $deliveryFee,
                'delivery_fee_discount' => 0,
                'delivery_distance' => round($faker->numberBetween(150, 8500) / 100, 2),
                'commission_from_driver' => $commission,
                'driver_id' => $driver->id,
                'has_assign_driver' => 1,
                'assign_driver_id' => $driver->id,
                'ask_receipt' => 1,
                'service_type_id' => 1,
                'mark' => 'white',
                'status' => MerchantOrderStatuses::PREVIOUS->value,
                'created_at' => now()->subDays($i % 30)->subHours($i),
                'updated_at' => now()->subDays($i % 30),
            ]);

            $created++;
        }

        $this->command?->info('Completed orders created: ' . $created);

        for ($j = 1; $j <= 5; $j++) {
            $merchant = $merchants[$j % $merchants->count()];
            $branchId = $branchIds[$merchant->id] ?? null;
            $driver = $drivers[$j % count($drivers)];

            MerchantOrder::create([
                'code' => $prefix . 'OTH-' . str_pad($j, 3, '0', STR_PAD_LEFT),
                'user_id' => $userIds[$j % $userIds->count()],
                'merchant_id' => $merchant->id,
                'merchant_branch_id' => $branchId,
                'address_id' => $addressId,
                'addressInOrder' => 'الرياض - حي الياسمين',
                'order_type' => $branchId ? 'fixed' : 'mobile',
                'channel' => 'mobile',
                'dialcode' => '966',
                'client_name' => $faker->name(),
                'client_phone' => '5' . $faker->numerify('########'),
                'merchant_name' => $merchant->company_name,
                'payment_method' => $payments[array_rand($payments)],
                'subtotal' => 100,
                'vat' => 15,
                'total_after_vat' => 115,
                'total_after_discount' => 115,
                'delivery_fee' => 25,
                'delivery_distance' => 4.5,
                'commission_from_driver' => 15,
                'driver_id' => $driver->id,
                'has_assign_driver' => 1,
                'assign_driver_id' => $driver->id,
                'service_type_id' => 1,
                'mark' => 'white',
                'status' => $otherStatuses[$j % count($otherStatuses)],
                'created_at' => now()->subDays($j),
                'updated_at' => now()->subDays($j),
            ]);
        }

        $this->command?->info('Other-status orders created: 5 (should NOT appear in the report)');
    }
}