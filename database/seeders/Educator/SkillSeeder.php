<?php

namespace Database\Seeders\Educator;

use Domain\AppBase\Models\Edu\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Skill::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $skills = [
            ['en' => 'Laravel', 'ar' => 'لارافل'],
            ['en' => 'Python', 'ar' => 'بايثون'],
            ['en' => 'JavaScript', 'ar' => 'جافاسكريبت'],
            ['en' => 'Vue.js', 'ar' => 'فيو جي إس'],
            ['en' => 'React', 'ar' => 'رياكت'],
            ['en' => 'Data Analysis', 'ar' => 'تحليل البيانات'],
            ['en' => 'Machine Learning', 'ar' => 'تعلم الآلة'],
            ['en' => 'Database Management', 'ar' => 'إدارة قواعد البيانات'],
            ['en' => 'UI/UX Design', 'ar' => 'تصميم واجهات وتجربة المستخدم'],
            ['en' => 'Statistical Analysis', 'ar' => 'التحليل الإحصائي'],
        ];

        foreach ($skills as $skill) {
            Skill::create([
                'name' => [
                    'en' => $skill['en'],
                    'ar' => $skill['ar']
                ],
                'status' => 'active'
            ]);
        }
    }
}
