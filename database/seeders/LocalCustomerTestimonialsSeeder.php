<?php

namespace Database\Seeders;

use App\Domain\AppBase\Models\CustomerTestimonial;
use Illuminate\Database\Seeder;
use LogicException;

class LocalCustomerTestimonialsSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            throw new LogicException('Local customer testimonials can only be seeded in the local environment.');
        }

        $testimonials = [
            [
                'name' => 'عميل تجريبي 1',
                'content' => [
                    'ar' => 'هذا رأي تجريبي محلي للتأكد من ظهور قسم آراء العملاء.',
                    'en' => 'This is local demo feedback to verify the customer reviews section.',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'عميل تجريبي 2',
                'content' => [
                    'ar' => 'بيانات اختبارية فقط، وليست مراجعة حقيقية من عميل.',
                    'en' => 'Test data only; this is not a real customer review.',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'عميل تجريبي 3',
                'content' => [
                    'ar' => 'يمكن حذف هذه البيانات التجريبية بعد الانتهاء من التحقق.',
                    'en' => 'This demo record can be removed after verification.',
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            CustomerTestimonial::firstOrCreate(
                ['name' => $testimonial['name']],
                [
                    'position' => ['ar' => 'تجريبي', 'en' => 'Demo'],
                    'content' => $testimonial['content'],
                    'is_active' => true,
                    'sort_order' => $testimonial['sort_order'],
                ]
            );
        }

        $this->command->info('Local demo testimonials seeded.');
    }
}
