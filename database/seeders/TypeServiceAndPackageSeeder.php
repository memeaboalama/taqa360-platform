<?php
// D:\Newtouch\Projects\taqa360\taqaNew19-10\edu-solutions-platform\database\seeders\TypeServiceAndPackageSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeServiceAndPackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // -------------------------------------------------
        // 8 diverse type‑services (arabic names only)
        // -------------------------------------------------
        $typeServices = [
            'خدمة التوصيل',
            'خدمة الصيانة',
            'خدمة التركيب',
            'خدمة الاستشارة',
            'خدمة الاشتراك',
            'خدمة الدعم الفني',
            'خدمة النسخ الاحتياطي',
            'خدمة التخصيص',
        ];

        foreach ($typeServices as $name) {
            // idempotent: insert only if not exists by name
            $exists = DB::table('type_services')->where('name', $name)->exists();
            if (! $exists) {
                DB::table('type_services')->insert([
                    'name' => $name,
                    'status' => 'active',
                    'desc' => null,
                ]);
            }
        }

        // -------------------------------------------------
        // 6 packages with commission / status (no discount_rate column)
        // -------------------------------------------------
        $packages = [
            ['title' => 'باقة الأساسية', 'commission' => 5, 'status' => 'active'],
            ['title' => 'باقة البلاتين', 'commission' => 10, 'status' => 'active'],
            ['title' => 'باقة الذهب', 'commission' => 15, 'status' => 'active'],
            ['title' => 'باقة الفضة', 'commission' => 12, 'status' => 'inactive'],
            ['title' => 'باقة البرونز', 'commission' => 8, 'status' => 'active'],
            ['title' => 'باقة النحاس', 'commission' => 3, 'status' => 'inactive'],
        ];

        foreach ($packages as $pkg) {
            // idempotent: insert only if a package with same arabic title does not exist
            $exists = DB::table('packages')->where('title', $pkg['title'])->exists();
            if (! $exists) {
                DB::table('packages')->insert([
                    'title' => $pkg['title'],
                    'commission' => $pkg['commission'],
                    'status' => $pkg['status'],
                    'terms_en' => null,
                    'terms_ar' => null,
                ]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}