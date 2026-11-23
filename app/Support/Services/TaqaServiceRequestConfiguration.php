<?php

namespace App\Support\Services;

use Domain\AppBase\Models\Edu\Service;

class TaqaServiceRequestConfiguration
{
    public static function siteSurvey(): array
    {
        $optionalHelp = [
            'ar' => 'اختياري؛ اتركه فارغًا إذا لم تتوفر المعلومة.',
            'en' => 'Optional; leave blank if you do not have this information.',
        ];

        return [
            'mode' => 'onsite',
            'solar' => true,
            'fields' => [
                [
                    'key' => 'goal',
                    'label' => ['ar' => 'ما الهدف من المعاينة؟', 'en' => 'What is the goal of the survey?'],
                    'type' => 'select',
                    'options' => [
                        'ar' => ['تجهيز منظومة جديدة', 'تقييم منظومة قائمة', 'تحديد احتياج الموقع', 'لست متأكدًا'],
                        'en' => ['Plan a new system', 'Assess an existing system', 'Assess the site needs', 'Not sure'],
                    ],
                    'help' => $optionalHelp,
                    'required' => false,
                ],
                [
                    'key' => 'existing',
                    'label' => ['ar' => 'هل توجد منظومة حاليًا؟', 'en' => 'Is there an existing system?'],
                    'type' => 'select',
                    'options' => [
                        'ar' => ['نعم', 'لا', 'لا أعرف'],
                        'en' => ['Yes', 'No', 'Not sure'],
                    ],
                    'help' => $optionalHelp,
                    'required' => false,
                ],
                [
                    'key' => 'loads',
                    'label' => ['ar' => 'الأجهزة أو الأحمال المهمة', 'en' => 'Important appliances or loads'],
                    'type' => 'textarea',
                    'options' => [],
                    'help' => $optionalHelp,
                    'required' => false,
                ],
            ],
        ];
    }

    public static function defaults(): array
    {
        $optionalHelp = 'اختياري؛ اتركه فارغًا إذا لم تتوفر المعلومة.';

        return [
            'Site Survey & Energy Assessment' => self::siteSurvey(),
            'Solar Energy System Design' => [
                'mode' => 'both',
                'solar' => true,
                'fields' => [
                    self::field('existing', 'حالة المشروع', 'select', ['مشروع جديد', 'منظومة قائمة', 'لا أعرف'], $optionalHelp),
                    self::field('goal', 'ما الذي تريد تحقيقه؟', 'text', [], $optionalHelp),
                    self::field('consumption', 'الاستهلاك أو الأحمال إن عُرفت', 'text', [], $optionalHelp),
                    self::field('space', 'المساحة المتاحة للتركيب إن عُرفت', 'text', [], $optionalHelp),
                ],
            ],
            'Solar Energy System Installation' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    self::field('design', 'هل يوجد تصميم للمنظومة؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    self::field('equipment', 'هل المعدات متوفرة؟', 'select', ['متوفرة بالكامل', 'متوفرة جزئيًا', 'غير متوفرة', 'لا أعرف'], $optionalHelp),
                    self::field('mount', 'مكان التركيب', 'select', ['سطح مستوٍ', 'سطح مائل', 'أرض مفتوحة', 'مكان آخر', 'لا أعرف'], $optionalHelp),
                    self::field('capacity', 'القدرة المستهدفة إن عُرفت', 'text', [], 'اكتب القيمة والوحدة، مثل 10 كيلوواط. المعلومة اختيارية.'),
                ],
            ],
            'Solar System Inspection & Maintenance' => [
                'mode' => 'onsite',
                'solar' => true,
                'show_urgency' => true,
                'fields' => [
                    self::field('component', 'المكوّن المتأثر', 'select', ['المنظومة بالكامل', 'الألواح', 'العاكس', 'البطاريات', 'التوصيلات أو الحماية', 'لا أعرف'], $optionalHelp),
                    self::field('when', 'متى ظهرت المشكلة؟', 'text', [], $optionalHelp),
                    self::field('error', 'رمز أو رسالة العطل إن وجدت', 'text', [], $optionalHelp),
                    self::field('model', 'الشركة والموديل إن عُرفا', 'text', [], $optionalHelp),
                ],
            ],
            'Solar Panel Cleaning' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    self::field('count', 'العدد التقريبي للألواح', 'number', [], $optionalHelp),
                    self::field('mount', 'مكان الألواح', 'select', ['سطح مبنى', 'أرض مفتوحة', 'موقف أو مظلة', 'مكان آخر', 'لا أعرف'], $optionalHelp),
                    self::field('access', 'سهولة الوصول إلى الألواح', 'select', ['وصول مباشر', 'يحتاج ترتيبات خاصة', 'لا أعرف'], $optionalHelp),
                    self::field('last', 'آخر تنظيف إن عُرف', 'text', [], $optionalHelp),
                ],
            ],
            'Inverter Installation & Configuration' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    self::field('job', 'نوع العمل', 'select', ['تركيب عاكس جديد', 'استبدال عاكس', 'إعادة تهيئة الإعدادات', 'لا أعرف'], $optionalHelp),
                    self::field('equipment', 'هل العاكس متوفر؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    self::field('model', 'الشركة والموديل إن عُرفا', 'text', [], $optionalHelp),
                    self::field('system', 'معلومات المنظومة المرتبطة', 'text', [], $optionalHelp),
                ],
            ],
            'Battery & Storage Installation' => [
                'mode' => 'onsite',
                'solar' => false,
                'fields' => [
                    self::field('job', 'نوع العمل', 'select', ['تركيب جديد', 'استبدال بطاريات', 'إضافة وحدات تخزين', 'إعادة تهيئة', 'لا أعرف'], $optionalHelp),
                    self::field('equipment', 'هل البطاريات متوفرة؟', 'select', ['نعم', 'جزئيًا', 'لا', 'لا أعرف'], $optionalHelp),
                    self::field('battery', 'نوع البطاريات وعددها إن عُرفا', 'text', [], $optionalHelp),
                    self::field('model', 'بيانات العاكس المرتبط إن توفرت', 'text', [], $optionalHelp),
                ],
            ],
            'Solar Grid Connection' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    self::field('existing', 'هل المنظومة مركبة؟', 'select', ['نعم', 'لا', 'قيد التنفيذ', 'لا أعرف'], $optionalHelp),
                    self::field('design', 'هل يوجد تصميم؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    self::field('capacity', 'القدرة الحالية إن عُرفت', 'text', [], $optionalHelp),
                    self::field('documents', 'ما مستندات الربط المتوفرة؟', 'textarea', [], $optionalHelp),
                ],
            ],
            'Solar System Expansion & Upgrade' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    self::field('current', 'معلومات المنظومة الحالية', 'text', [], $optionalHelp),
                    self::field('goal', 'هدف التوسعة أو التطوير', 'text', [], $optionalHelp),
                    self::field('components', 'المكونات المطلوب تطويرها', 'textarea', [], $optionalHelp),
                    self::field('capacity', 'القدرة الحالية والمستهدفة إن عُرفتا', 'text', [], $optionalHelp),
                ],
            ],
            'EV Charger Installation' => [
                'mode' => 'onsite',
                'solar' => false,
                'fields' => [
                    self::field('place', 'مكان تركيب الشاحن', 'select', ['موقف منزل', 'موقف منشأة', 'موقف عام', 'مكان آخر'], $optionalHelp),
                    self::field('count', 'عدد نقاط الشحن المطلوبة', 'number', [], $optionalHelp),
                    self::field('equipment', 'هل الشاحن متوفر؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    self::field('model', 'موديل الشاحن أو متطلبات المركبة إن عُرفت', 'text', [], $optionalHelp),
                ],
            ],
        ];
    }

    private static function field(string $key, string $label, string $type, array $options, string $help): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => $type,
            'options' => $options,
            'help' => $help,
            'required' => false,
        ];
    }

    public static function forService(Service $service): array
    {
        $config = $service->request_config ?? [];
        unset($config['_seed_key']);
        $defaults = self::defaults();
        $seedKey = $service->request_config['_seed_key'] ?? null;
        $serviceKey = isset($defaults[$seedKey ?? ''])
            ? $seedKey
            : $service->getTranslation('name', 'en');
        $serviceDefaults = $defaults[$serviceKey] ?? (self::isSiteSurvey($service) ? self::siteSurvey() : null);
        if ($serviceDefaults === null) {
            return $config;
        }

        $config = array_merge($serviceDefaults, $config);
        $fields = is_array($config['fields'] ?? null) ? $config['fields'] : [];
        $fieldKeys = array_column($fields, 'key');

        foreach ($serviceDefaults['fields'] as $field) {
            if (! in_array($field['key'], $fieldKeys, true)) {
                $fields[] = $field;
            }
        }

        $config['fields'] = $fields;

        return $config;
    }

    public static function isSiteSurvey(Service $service): bool
    {
        return ($service->request_config['_seed_key'] ?? null) === 'Site Survey & Energy Assessment'
            || $service->getTranslation('name', 'en') === 'Site Survey & Energy Assessment'
            || $service->getTranslation('name', 'ar') === 'معاينة الموقع وتقييم احتياجات الطاقة';
    }
}
