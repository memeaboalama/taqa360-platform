<?php

namespace Database\Seeders\Educator;

use Domain\AppBase\Models\Edu\AcademicMajor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicMajorSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AcademicMajor::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $majors = [
            ['en' => 'Software Engineering', 'ar' => 'هندسة البرمجيات'],
            ['en' => 'Computer Science', 'ar' => 'علوم الحاسوب'],
            ['en' => 'Artificial Intelligence', 'ar' => 'الذكاء الاصطناعي'],
            ['en' => 'Data Science', 'ar' => 'علم البيانات'],
            ['en' => 'Civil Engineering', 'ar' => 'الهندسة المدنية'],
            ['en' => 'Medicine', 'ar' => 'الطب'],
            ['en' => 'Business Administration', 'ar' => 'إدارة الأعمال'],
            ['en' => 'Law', 'ar' => 'القانون'],
            ['en' => 'Education', 'ar' => 'التربية'],
            ['en' => 'Graphic Design', 'ar' => 'التصميم الجرافيكي'],
        ];

        foreach ($majors as $major) {
            AcademicMajor::create([
                'name' => [
                    'en' => $major['en'],
                    'ar' => $major['ar']
                ],
                'status' => 'active'
            ]);
        }
    }
}
