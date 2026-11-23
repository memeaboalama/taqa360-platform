<?php

namespace Database\Seeders\Educator;

use App\Support\Enum\StatusEnum;
use App\Support\Services\TaqaServiceRequestConfiguration;
use Domain\AppBase\Models\Edu\Service;
use Illuminate\Database\Seeder;

class TaqaEnergyServiceSeeder extends Seeder
{
    public function run(): void
    {
        $requestConfigurations = TaqaServiceRequestConfiguration::defaults();

        foreach ($this->services() as $data) {
            $seedKey = $data['name']['en'];
            $service = Service::withTrashed()
                ->where('request_config->_seed_key', $seedKey)
                ->first() ?? Service::withTrashed()
                ->where('name->ar', $data['name']['ar'])
                ->first() ?? new Service();
            $requestConfig = $requestConfigurations[$seedKey] ?? null;

            if ($requestConfig === null) {
                throw new \LogicException('Missing request configuration for ' . $data['name']['en']);
            }
            $requestConfig['_seed_key'] = $seedKey;
            $isNew = ! $service->exists;

            if ($service->trashed()) {
                $service->restore();
            }

            if (! $isNew) {
                foreach (['name', 'description', 'scope_of_work'] as $field) {
                    $data[$field] = $this->fillMissingTranslations(
                        $data[$field],
                        $service->getTranslations($field)
                    );
                }
                $requestConfig = $this->fillMissingValues($requestConfig, $service->request_config ?? []);
            }

            $service->fill([
                'name' => $data['name'],
                'description' => $data['description'],
                'scope_of_work' => $data['scope_of_work'],
                'request_config' => $requestConfig,
            ]);
            if ($isNew) {
                $service->status = StatusEnum::ACTIVE;
            }
            $service->save();
        }
    }

    private function fillMissingValues(array $defaults, array $current): array
    {
        foreach ($defaults as $key => $value) {
            if (! array_key_exists($key, $current)) {
                $current[$key] = $value;
            }
        }

        return $current;
    }

    private function fillMissingTranslations(array $defaults, array $current): array
    {
        foreach ($defaults as $locale => $value) {
            if (! isset($current[$locale]) || $current[$locale] === '') {
                $current[$locale] = $value;
            }
        }

        return $current;
    }

    private function services(): array
    {
        return [
            [
                'name' => [
                    'ar' => 'معاينة الموقع وتقييم احتياجات الطاقة',
                    'en' => 'Site Survey & Energy Assessment',
                ],
                'description' => [
                    'ar' => 'ابدأ بفهم احتياج موقعك والخيارات المناسبة له قبل اتخاذ قرار التنفيذ.',
                    'en' => 'Assess your site’s energy needs and suitable options before implementation.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'معاينة الموقع وجمع بيانات الاحتياج',
                        'مراجعة الاستهلاك والأحمال المتوفرة',
                        'تحديد المتطلبات الأولية والخطوات التالية',
                    ]),
                    'en' => implode("\n", [
                        'Survey the site and collect requirements',
                        'Review energy use and available loads',
                        'Identify initial requirements and next steps',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'تصميم منظومات الطاقة الشمسية',
                    'en' => 'Solar Energy System Design',
                ],
                'description' => [
                    'ar' => 'حوّل احتياج موقعك إلى تصميم واضح يحدد المنظومة ومكوناتها.',
                    'en' => 'Turn your site requirements into a clear system and component design.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'مراجعة بيانات الموقع والاستهلاك',
                        'إعداد التصميم وتحديد المكونات',
                        'توضيح متطلبات التنفيذ حسب نطاق الاتفاق',
                    ]),
                    'en' => implode("\n", [
                        'Review site and energy-use information',
                        'Prepare the design and specify components',
                        'Clarify implementation requirements within the agreed scope',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'تركيب منظومات الطاقة الشمسية',
                    'en' => 'Solar Energy System Installation',
                ],
                'description' => [
                    'ar' => 'تنفيذ أعمال تركيب المنظومة وفق تصميم ونطاق عمل متفق عليهما.',
                    'en' => 'Install the system according to an agreed design and scope of work.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'تركيب الألواح وهياكل التثبيت',
                        'تمديد وربط التوصيلات والمكونات',
                        'اختبار التشغيل وتسليم الأعمال المتفق عليها',
                    ]),
                    'en' => implode("\n", [
                        'Install panels and mounting structures',
                        'Route and connect wiring and components',
                        'Test operation and deliver the agreed work',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'فحص وصيانة منظومات الطاقة الشمسية',
                    'en' => 'Solar System Inspection & Maintenance',
                ],
                'description' => [
                    'ar' => 'تشخيص الأعطال ومشكلات الأداء وتحديد أعمال الصيانة المناسبة.',
                    'en' => 'Diagnose faults and performance issues and define required maintenance.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'فحص المكونات ضمن النطاق المتفق عليه',
                        'تشخيص العطل وتحديد الأعمال المطلوبة',
                        'تنفيذ الصيانة المعتمدة واختبار النتيجة',
                    ]),
                    'en' => implode("\n", [
                        'Inspect components within the agreed scope',
                        'Diagnose faults and identify required work',
                        'Perform approved maintenance and test the result',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'تنظيف الألواح الشمسية',
                    'en' => 'Solar Panel Cleaning',
                ],
                'description' => [
                    'ar' => 'طلب تنظيف الألواح وإزالة الأتربة والعوائق السطحية.',
                    'en' => 'Request cleaning to remove dust and surface obstructions from solar panels.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'تقييم الوصول إلى موضع الألواح',
                        'تنظيف الألواح ضمن نطاق الاتفاق',
                        'فحص الحالة الظاهرية وتوثيق الملاحظات',
                    ]),
                    'en' => implode("\n", [
                        'Assess access to the panels',
                        'Clean panels within the agreed scope',
                        'Check visible condition and document observations',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'تركيب وتهيئة العواكس',
                    'en' => 'Inverter Installation & Configuration',
                ],
                'description' => [
                    'ar' => 'تركيب العاكس وربطه وضبط إعداداته وفق متطلبات المنظومة.',
                    'en' => 'Install, connect, and configure the inverter for the system.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'مراجعة توافق التجهيزات ضمن الاتفاق',
                        'تركيب وربط العاكس',
                        'تهيئة الإعدادات واختبار التشغيل',
                    ]),
                    'en' => implode("\n", [
                        'Review equipment compatibility within the agreed scope',
                        'Install and connect the inverter',
                        'Configure settings and test operation',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'تركيب وتهيئة البطاريات وأنظمة التخزين',
                    'en' => 'Battery & Storage Installation',
                ],
                'description' => [
                    'ar' => 'تركيب وربط وحدات التخزين وضبط إعداداتها وتجربة تشغيلها.',
                    'en' => 'Install and connect storage units, configure settings, and test operation.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'مراجعة بيانات البطاريات والمنظومة',
                        'تركيب وربط وحدات التخزين',
                        'ضبط الإعدادات واختبار التشغيل',
                    ]),
                    'en' => implode("\n", [
                        'Review battery and system information',
                        'Install and connect storage units',
                        'Configure settings and test operation',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'ربط منظومات الطاقة الشمسية بالشبكة',
                    'en' => 'Solar Grid Connection',
                ],
                'description' => [
                    'ar' => 'تقييم وتنفيذ أعمال الربط ضمن نطاق الخدمة ومتطلباتها المعتمدة.',
                    'en' => 'Assess and perform grid-connection work within approved service requirements.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'مراجعة وضع المنظومة ومستندات الربط',
                        'تحديد أعمال الحماية والربط المطلوبة',
                        'تنفيذ واختبار الأعمال ضمن النطاق المعتمد',
                    ]),
                    'en' => implode("\n", [
                        'Review the system and grid-connection documents',
                        'Identify required protection and connection work',
                        'Perform and test work within the approved scope',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'توسعة وتطوير منظومات الطاقة الشمسية',
                    'en' => 'Solar System Expansion & Upgrade',
                ],
                'description' => [
                    'ar' => 'دراسة تحديث المنظومة أو زيادة قدرتها بما يلائم الاحتياج.',
                    'en' => 'Assess system upgrades or capacity expansion to fit your needs.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'مراجعة المنظومة القائمة',
                        'تحديد متطلبات التوسعة أو التحديث',
                        'تنفيذ الأعمال المعتمدة واختبارها',
                    ]),
                    'en' => implode("\n", [
                        'Review the existing system',
                        'Identify expansion or upgrade requirements',
                        'Perform and test the approved work',
                    ]),
                ],
            ],
            [
                'name' => [
                    'ar' => 'تركيب شواحن المركبات الكهربائية',
                    'en' => 'EV Charger Installation',
                ],
                'description' => [
                    'ar' => 'تجهيز موقع الشحن وتركيب الشاحن وتوصيلاته واختبار تشغيله.',
                    'en' => 'Prepare the charging site, install the charger and connections, and test operation.',
                ],
                'scope_of_work' => [
                    'ar' => implode("\n", [
                        'مراجعة موقع الشاحن والتجهيزات',
                        'تركيب الشاحن وتوصيلاته وحمايته',
                        'التهيئة واختبار التشغيل',
                    ]),
                    'en' => implode("\n", [
                        'Review the charger location and equipment',
                        'Install the charger, connections, and protection',
                        'Configure and test operation',
                    ]),
                ],
            ],
        ];
    }
}
