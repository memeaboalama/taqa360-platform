<?php

namespace Database\Seeders\Educator;

use Domain\AppBase\Models\Edu\Service;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $services = [
            [
                'en' => [
                    'name' => 'Academic Research Preparation',
                    'description' => 'Helping students prepare high-quality academic research according to university standards.'
                ],
                'ar' => [
                    'name' => 'إعداد البحوث الأكاديمية',
                    'description' => 'مساعدة الطلاب في إعداد البحوث الأكاديمية بجودة عالية وفقًا لمعايير الجامعات.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Solving Assignments',
                    'description' => 'Providing assistance in solving assignments and academic tasks accurately and efficiently.'
                ],
                'ar' => [
                    'name' => 'حل الواجبات والتكاليف',
                    'description' => 'تقديم المساعدة في حل الواجبات والتكاليف الأكاديمية بدقة وكفاءة.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Graduation Projects & Theses',
                    'description' => 'Guidance and support for preparing graduation projects and master/PhD theses professionally.'
                ],
                'ar' => [
                    'name' => 'مشاريع التخرج والرسائل',
                    'description' => 'تقديم الإرشاد والدعم في إعداد مشاريع التخرج والرسائل العلمية باحترافية.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Presentation Preparation',
                    'description' => 'Assisting students in preparing professional academic presentations using modern tools.'
                ],
                'ar' => [
                    'name' => 'إعداد العروض التقديمية',
                    'description' => 'مساعدة الطلاب في إعداد عروض تقديمية أكاديمية احترافية باستخدام أحدث الأدوات.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Translation Services',
                    'description' => 'Providing academic translation services for research papers and scientific documents.'
                ],
                'ar' => [
                    'name' => 'خدمات الترجمة',
                    'description' => 'تقديم خدمات الترجمة الأكاديمية للأبحاث والوثائق العلمية بدقة عالية.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Private Tutoring',
                    'description' => 'Offering private academic tutoring sessions tailored to student needs.'
                ],
                'ar' => [
                    'name' => 'التدريس الخصوصي',
                    'description' => 'تقديم جلسات تدريس خصوصي أكاديمية مصممة لتلبية احتياجات الطلاب.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Academic Consultation',
                    'description' => 'Providing academic consultation and guidance to improve research and study outcomes.'
                ],
                'ar' => [
                    'name' => 'الاستشارات الأكاديمية',
                    'description' => 'تقديم الاستشارات الأكاديمية والإرشادات لتحسين نتائج الأبحاث والدراسات.'
                ]
            ],
            [
                'en' => [
                    'name' => 'Code Review',
                    'description' => 'Helping students review and improve their programming codes for better performance.'
                ],
                'ar' => [
                    'name' => 'مراجعة الأكواد البرمجية',
                    'description' => 'مساعدة الطلاب في مراجعة وتحسين الأكواد البرمجية للحصول على أداء أفضل.'
                ]
            ],
        ];

        foreach ($services as $service) {
            Service::create([
                'name' => [
                    'en' => $service['en']['name'],
                    'ar' => $service['ar']['name'],
                ],
                'description' => [
                    'en' => $service['en']['description'],
                    'ar' => $service['ar']['description'],
                ],
                'status' => StatusEnum::ACTIVE,
            ]);
        }
    }
}
