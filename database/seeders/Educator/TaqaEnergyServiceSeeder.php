<?php

namespace Database\Seeders\Educator;

use App\Support\Enum\StatusEnum;
use Domain\AppBase\Models\Edu\Service;
use Illuminate\Database\Seeder;

class TaqaEnergyServiceSeeder extends Seeder
{
    public function run(): void
    {
        $requestConfigurations = $this->requestConfigurations();
        $serviceDetails = $this->serviceDetails();

        foreach ($this->services() as $data) {
            $seedKey = $data['name']['en'];
            $service = Service::withTrashed()
                ->where('details->_seed_key', $seedKey)
                ->first() ?? Service::withTrashed()
                ->where('name->ar', $data['name']['ar'])
                ->first() ?? new Service();
            $requestConfig = $requestConfigurations[$seedKey] ?? null;

            if ($requestConfig === null) {
                throw new \LogicException('Missing request configuration for ' . $data['name']['en']);
            }
            $details = $serviceDetails[$seedKey] ?? null;
            if ($details === null) {
                throw new \LogicException('Missing details for ' . $seedKey);
            }
            $details['_seed_key'] = $seedKey;
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
                $details = $this->fillMissingValues($details, $service->details ?? []);
            }

            $service->fill([
                'name' => $data['name'],
                'description' => $data['description'],
                'scope_of_work' => $data['scope_of_work'],
                'request_config' => $requestConfig,
                'details' => $details,
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

    private function requestConfigurations(): array
    {
        $optionalHelp = 'اختياري؛ اتركه فارغًا إذا لم تتوفر المعلومة.';

        return [
            'Site Survey & Energy Assessment' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    $this->field('goal', 'ما الهدف من المعاينة؟', 'select', ['تجهيز منظومة جديدة', 'تقييم منظومة قائمة', 'تحديد احتياج الموقع', 'لست متأكدًا'], $optionalHelp),
                    $this->field('existing', 'هل توجد منظومة حاليًا؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    $this->field('loads', 'الأجهزة أو الأحمال المهمة', 'textarea', [], $optionalHelp),
                ],
            ],
            'Solar Energy System Design' => [
                'mode' => 'both',
                'solar' => true,
                'fields' => [
                    $this->field('existing', 'حالة المشروع', 'select', ['مشروع جديد', 'منظومة قائمة', 'لا أعرف'], $optionalHelp),
                    $this->field('goal', 'ما الذي تريد تحقيقه؟', 'text', [], $optionalHelp),
                    $this->field('consumption', 'الاستهلاك أو الأحمال إن عُرفت', 'text', [], $optionalHelp),
                    $this->field('space', 'المساحة المتاحة للتركيب إن عُرفت', 'text', [], $optionalHelp),
                ],
            ],
            'Solar Energy System Installation' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    $this->field('design', 'هل يوجد تصميم للمنظومة؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    $this->field('equipment', 'هل المعدات متوفرة؟', 'select', ['متوفرة بالكامل', 'متوفرة جزئيًا', 'غير متوفرة', 'لا أعرف'], $optionalHelp),
                    $this->field('mount', 'مكان التركيب', 'select', ['سطح مستوٍ', 'سطح مائل', 'أرض مفتوحة', 'مكان آخر', 'لا أعرف'], $optionalHelp),
                    $this->field('capacity', 'القدرة المستهدفة إن عُرفت', 'text', [], 'اكتب القيمة والوحدة، مثل 10 كيلوواط. المعلومة اختيارية.'),
                ],
            ],
            'Solar System Inspection & Maintenance' => [
                'mode' => 'onsite',
                'solar' => true,
                'show_urgency' => true,
                'fields' => [
                    $this->field('component', 'المكوّن المتأثر', 'select', ['المنظومة بالكامل', 'الألواح', 'العاكس', 'البطاريات', 'التوصيلات أو الحماية', 'لا أعرف'], $optionalHelp),
                    $this->field('when', 'متى ظهرت المشكلة؟', 'text', [], $optionalHelp),
                    $this->field('error', 'رمز أو رسالة العطل إن وجدت', 'text', [], $optionalHelp),
                    $this->field('model', 'الشركة والموديل إن عُرفا', 'text', [], $optionalHelp),
                ],
            ],
            'Solar Panel Cleaning' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    $this->field('count', 'العدد التقريبي للألواح', 'number', [], $optionalHelp),
                    $this->field('mount', 'مكان الألواح', 'select', ['سطح مبنى', 'أرض مفتوحة', 'موقف أو مظلة', 'مكان آخر', 'لا أعرف'], $optionalHelp),
                    $this->field('access', 'سهولة الوصول إلى الألواح', 'select', ['وصول مباشر', 'يحتاج ترتيبات خاصة', 'لا أعرف'], $optionalHelp),
                    $this->field('last', 'آخر تنظيف إن عُرف', 'text', [], $optionalHelp),
                ],
            ],
            'Inverter Installation & Configuration' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    $this->field('job', 'نوع العمل', 'select', ['تركيب عاكس جديد', 'استبدال عاكس', 'إعادة تهيئة الإعدادات', 'لا أعرف'], $optionalHelp),
                    $this->field('equipment', 'هل العاكس متوفر؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    $this->field('model', 'الشركة والموديل إن عُرفا', 'text', [], $optionalHelp),
                    $this->field('system', 'معلومات المنظومة المرتبطة', 'text', [], $optionalHelp),
                ],
            ],
            'Battery & Storage Installation' => [
                'mode' => 'onsite',
                'solar' => false,
                'fields' => [
                    $this->field('job', 'نوع العمل', 'select', ['تركيب جديد', 'استبدال بطاريات', 'إضافة وحدات تخزين', 'إعادة تهيئة', 'لا أعرف'], $optionalHelp),
                    $this->field('equipment', 'هل البطاريات متوفرة؟', 'select', ['نعم', 'جزئيًا', 'لا', 'لا أعرف'], $optionalHelp),
                    $this->field('battery', 'نوع البطاريات وعددها إن عُرفا', 'text', [], $optionalHelp),
                    $this->field('model', 'بيانات العاكس المرتبط إن توفرت', 'text', [], $optionalHelp),
                ],
            ],
            'Solar Grid Connection' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    $this->field('existing', 'هل المنظومة مركبة؟', 'select', ['نعم', 'لا', 'قيد التنفيذ', 'لا أعرف'], $optionalHelp),
                    $this->field('design', 'هل يوجد تصميم؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    $this->field('capacity', 'القدرة الحالية إن عُرفت', 'text', [], $optionalHelp),
                    $this->field('documents', 'ما مستندات الربط المتوفرة؟', 'textarea', [], $optionalHelp),
                ],
            ],
            'Solar System Expansion & Upgrade' => [
                'mode' => 'onsite',
                'solar' => true,
                'fields' => [
                    $this->field('current', 'معلومات المنظومة الحالية', 'text', [], $optionalHelp),
                    $this->field('goal', 'هدف التوسعة أو التطوير', 'text', [], $optionalHelp),
                    $this->field('components', 'المكونات المطلوب تطويرها', 'textarea', [], $optionalHelp),
                    $this->field('capacity', 'القدرة الحالية والمستهدفة إن عُرفتا', 'text', [], $optionalHelp),
                ],
            ],
            'EV Charger Installation' => [
                'mode' => 'onsite',
                'solar' => false,
                'fields' => [
                    $this->field('place', 'مكان تركيب الشاحن', 'select', ['موقف منزل', 'موقف منشأة', 'موقف عام', 'مكان آخر'], $optionalHelp),
                    $this->field('count', 'عدد نقاط الشحن المطلوبة', 'number', [], $optionalHelp),
                    $this->field('equipment', 'هل الشاحن متوفر؟', 'select', ['نعم', 'لا', 'لا أعرف'], $optionalHelp),
                    $this->field('model', 'موديل الشاحن أو متطلبات المركبة إن عُرفت', 'text', [], $optionalHelp),
                ],
            ],
        ];
    }

    private function field(string $key, string $label, string $type, array $options, string $help): array
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

    private function serviceDetails(): array
    {
        return [
            'Site Survey & Energy Assessment' => $this->detail(
                'المعاينة والتقييم',
                'Site assessment',
                ['ملخص احتياجات الموقع والأحمال المتاحة', 'ملاحظات المعاينة والبيانات التي جُمعت', 'خطوات أولية مقترحة لاستكمال الدراسة'],
                ['Summary of site needs and available loads', 'Survey notes and collected information', 'Initial recommended next steps'],
                ['شراء أو توريد المعدات ما لم يذكر في عرض مستقل', 'الأعمال التنفيذية خارج نطاق المعاينة'],
                ['Equipment supply unless specified in a separate offer', 'Implementation work outside the survey scope'],
                ['عنوان الموقع ومعلومات الوصول', 'فاتورة كهرباء أو بيانات استهلاك إن توفرت', 'صور للموقع أو المنظومة الحالية إن وجدت'],
                ['Site address and access information', 'An electricity bill or consumption data if available', 'Site or existing-system photos if available']
            ),
            'Solar Energy System Design' => $this->detail(
                'التصميم',
                'System design',
                ['تصور فني للمنظومة المقترحة', 'قائمة أولية بالمكونات والقدرات', 'ملاحظات ومتطلبات مرتبطة بالتنفيذ'],
                ['Technical concept for the proposed system', 'Initial component and capacity schedule', 'Implementation notes and requirements'],
                ['توريد المعدات وأعمال التركيب ما لم يتفق عليها بشكل مستقل', 'اعتماد المخططات من الجهات المختصة'],
                ['Equipment supply and installation unless separately agreed', 'Approval of plans by relevant authorities'],
                ['بيانات الموقع والمساحة المتاحة', 'فواتير أو بيانات الاستهلاك المتاحة', 'الهدف من المنظومة وأي قيود معروفة'],
                ['Site details and available area', 'Available bills or consumption data', 'System goals and known constraints']
            ),
            'Solar Energy System Installation' => $this->detail(
                'التركيب',
                'Installation',
                ['تركيب المكونات المتفق عليها', 'ربط المنظومة واختبار التشغيل', 'تسليم الأعمال المنفذة ضمن النطاق'],
                ['Installation of agreed components', 'System connection and operational testing', 'Handover of work within the agreed scope'],
                ['التصميم أو المعدات غير المشمولة في عرض التنفيذ', 'أعمال الموقع المدنية غير المحددة في الاتفاق'],
                ['Design or equipment not included in the installation offer', 'Civil site work not specified in the agreement'],
                ['تصميم معتمد أو معلومات المنظومة إن توفرت', 'تحديد نقطة التركيب وإمكانية الوصول', 'توفير تصاريح دخول الموقع عند الحاجة'],
                ['Approved design or system information if available', 'Installation location and access details', 'Site access permissions when required']
            ),
            'Solar System Inspection & Maintenance' => $this->detail(
                'الفحص والصيانة',
                'Inspection & maintenance',
                ['نتائج فحص المكونات ضمن النطاق', 'تشخيص أولي للمشكلة ومسبباتها المحتملة', 'تقرير بالأعمال المنفذة والتوصيات'],
                ['Inspection findings for components in scope', 'Initial diagnosis and possible causes', 'Report of completed work and recommendations'],
                ['قطع الغيار والاستبدال ما لم يشملها عرض معتمد', 'إصلاحات لا يمكن تحديدها قبل الفحص'],
                ['Spare parts and replacements unless included in an approved offer', 'Repairs that cannot be scoped before inspection'],
                ['وصف العطل ووقت ظهوره إن عُرف', 'صور أو رسائل تنبيه من النظام', 'بيانات الطراز والضمان إن توفرت'],
                ['Fault description and when it started if known', 'System photos or alert messages', 'Model and warranty information if available']
            ),
            'Solar Panel Cleaning' => $this->detail(
                'التنظيف',
                'Cleaning',
                ['تنظيف الألواح ضمن العدد والموقع المتفق عليهما', 'مراجعة المظهر العام للألواح أثناء العمل', 'إبلاغ العميل بالملاحظات الظاهرة'],
                ['Cleaning panels within the agreed quantity and site', 'Visual review of panels during service', 'Notification of visible issues'],
                ['إصلاح الأعطال أو استبدال الألواح', 'ترتيبات الرفع أو الوصول الخاصة غير المتفق عليها'],
                ['Fault repair or panel replacement', 'Special lifting or access arrangements not agreed in advance'],
                ['العدد التقريبي للألواح وموقعها', 'توضيح وسائل الوصول ومتطلبات السلامة', 'صور للموقع إن توفرت'],
                ['Approximate panel count and location', 'Access method and safety requirements', 'Site photos if available']
            ),
            'Inverter Installation & Configuration' => $this->detail(
                'العواكس',
                'Inverters',
                ['تركيب العاكس أو تهيئته حسب الاتفاق', 'ربط الإعدادات بالمكونات المتوافقة', 'اختبار التشغيل وتوثيق الملاحظات'],
                ['Inverter installation or configuration as agreed', 'Configuration with compatible components', 'Operational testing and recorded observations'],
                ['توريد العاكس أو المكونات الإضافية ما لم يشملها العرض', 'تعديل بقية المنظومة خارج النطاق'],
                ['Inverter or additional component supply unless included in the offer', 'Changes to other system components outside scope'],
                ['الشركة والطراز والقدرة المطلوبة أو الحالية', 'معلومات البطارية والألواح المرتبطة', 'صور التوصيلات ومكان التركيب إن توفرت'],
                ['Manufacturer, model, and required or current capacity', 'Information about connected batteries and panels', 'Connection and installation photos if available']
            ),
            'Battery & Storage Installation' => $this->detail(
                'البطاريات والتخزين',
                'Batteries & storage',
                ['تركيب وحدات التخزين المشمولة بالاتفاق', 'ربطها وتهيئة إعداداتها المتوافقة', 'اختبار التشغيل وتسليم الملاحظات'],
                ['Installation of storage units included in the agreement', 'Connection and compatible configuration', 'Operational testing and handover notes'],
                ['توريد البطاريات أو العاكس ما لم يذكر في العرض', 'تعديلات كهربائية إضافية خارج النطاق'],
                ['Battery or inverter supply unless specified in the offer', 'Additional electrical modifications outside scope'],
                ['نوع البطارية وعددها إن عُرفا', 'بيانات العاكس والمنظومة الحالية', 'الموقع المقترح ومتطلبات الوصول'],
                ['Battery type and quantity if known', 'Existing inverter and system details', 'Proposed location and access requirements']
            ),
            'Solar Grid Connection' => $this->detail(
                'الربط بالشبكة',
                'Grid connection',
                ['مراجعة معلومات المنظومة ومستندات الربط المتاحة', 'تحديد متطلبات الحماية والربط ضمن النطاق', 'اختبار الأعمال المنفذة وتوثيقها'],
                ['Review of system information and available connection documents', 'Identification of protection and connection needs in scope', 'Testing and documentation of completed work'],
                ['رسوم الجهات أو الموافقات الرسمية', 'الأعمال التي تتطلب اعتماداً خارجياً ما لم يتفق عليها'],
                ['Authority fees or official approvals', 'Work requiring external approval unless agreed'],
                ['مستندات المنظومة والربط المتوفرة', 'بيانات العاكس والقدرة الحالية', 'الملاحظات أو الاشتراطات الصادرة من الجهة المختصة'],
                ['Available system and connection documents', 'Inverter details and current capacity', 'Notes or requirements from the relevant authority']
            ),
            'Solar System Expansion & Upgrade' => $this->detail(
                'التوسعة والتطوير',
                'Expansion & upgrades',
                ['مراجعة حالة المنظومة الحالية', 'تحديد توافق ومتطلبات التوسعة المقترحة', 'تنفيذ واختبار الأعمال المعتمدة'],
                ['Review of the existing system', 'Assessment of compatibility and expansion needs', 'Implementation and testing of approved work'],
                ['المكونات الجديدة ما لم يشملها العرض', 'استبدال مكونات سليمة خارج نطاق التحديث'],
                ['New components unless included in the offer', 'Replacement of functional components outside the upgrade scope'],
                ['بيانات المكونات والقدرات الحالية', 'الهدف من التوسعة أو التطوير', 'صور أو مخططات المنظومة إن توفرت'],
                ['Existing components and capacity details', 'Expansion or upgrade goals', 'System photos or drawings if available']
            ),
            'EV Charger Installation' => $this->detail(
                'شواحن المركبات الكهربائية',
                'EV charging',
                ['مراجعة موقع الشاحن ونقطة التغذية', 'تركيب وتوصيل الشاحن المشمول بالاتفاق', 'اختبار التشغيل وتسليم إرشادات الاستخدام الأساسية'],
                ['Review of charger location and power supply point', 'Installation and connection of the charger included in the agreement', 'Operational testing and basic usage guidance'],
                ['توريد الشاحن أو ترقية التغذية الكهربائية ما لم يشملها العرض', 'أعمال المواقف أو المسارات المدنية غير المتفق عليها'],
                ['Charger supply or power-supply upgrades unless included in the offer', 'Parking or civil pathway work not agreed in advance'],
                ['نوع الشاحن وعدد نقاط الشحن', 'معلومات المركبة أو متطلبات الشحن', 'صور للموقف ولوحة الكهرباء إن توفرت'],
                ['Charger type and number of charging points', 'Vehicle information or charging requirements', 'Parking and electrical-panel photos if available']
            ),
        ];
    }

    private function detail(
        string $categoryAr,
        string $categoryEn,
        array $deliverablesAr,
        array $deliverablesEn,
        array $exclusionsAr,
        array $exclusionsEn,
        array $requirementsAr,
        array $requirementsEn
    ): array {
        return [
            'category' => ['ar' => $categoryAr, 'en' => $categoryEn],
            'icon' => 'fa-sun-o',
            'deliverables' => ['ar' => $deliverablesAr, 'en' => $deliverablesEn],
            'exclusions' => ['ar' => $exclusionsAr, 'en' => $exclusionsEn],
            'requirements' => ['ar' => $requirementsAr, 'en' => $requirementsEn],
            'requirements_note' => [
                'ar' => 'قد تختلف المتطلبات حسب الموقع ونطاق الخدمة.',
                'en' => 'Requirements may vary by site and service scope.',
            ],
            'process' => [
                [
                    'icon' => 'fa-file-text-o',
                    'title' => ['ar' => 'تقديم الطلب', 'en' => 'Submit the request'],
                    'description' => ['ar' => 'أرسل تفاصيل الخدمة والموقع والموعد المناسب.', 'en' => 'Share the service, site, and preferred timing.'],
                ],
                [
                    'icon' => 'fa-comments-o',
                    'title' => ['ar' => 'مراجعة التفاصيل', 'en' => 'Review the details'],
                    'description' => ['ar' => 'تتم مراجعة نطاق العمل والمتطلبات والتنسيق معك.', 'en' => 'The scope and requirements are reviewed and coordinated with you.'],
                ],
                [
                    'icon' => 'fa-check-circle-o',
                    'title' => ['ar' => 'تنفيذ الخدمة', 'en' => 'Service delivery'],
                    'description' => ['ar' => 'تُنفذ الأعمال المتفق عليها وفق نطاق الخدمة.', 'en' => 'The agreed work is completed within the service scope.'],
                ],
            ],
            'cta_title' => [
                'ar' => 'اطلب هذه الخدمة',
                'en' => 'Request this service',
            ],
            'cta_help' => [
                'ar' => 'أرسل طلبك ليتم مراجعته والتواصل معك بشأن التفاصيل.',
                'en' => 'Submit your request for review and follow-up.',
            ],
            'disclaimer' => [
                'ar' => 'تحدد التفاصيل النهائية بعد مراجعة الطلب.',
                'en' => 'Final details are confirmed after reviewing the request.',
            ],
            'duration' => [
                'ar' => 'تحدد المدة بعد مراجعة الموقع ونطاق الأعمال.',
                'en' => 'The timeline is agreed after reviewing the site and scope of work.',
            ],
            'pricing' => [
                'ar' => 'يحدد السعر بعد مراجعة الموقع والمكونات ونطاق العمل.',
                'en' => 'Pricing is determined after reviewing the site, components, and scope of work.',
            ],
        ];
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
