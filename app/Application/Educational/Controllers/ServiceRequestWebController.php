<?php

namespace App\Application\Educational\Controllers;

use App\Domain\EducationalRequest\Actions\CreateEducationalRequestAction;
use App\Http\Controllers\Controller;
use App\Support\Enum\StatusEnum;
use App\Support\Services\TaqaServiceRequestConfiguration;
use Domain\AppBase\Models\Edu\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServiceRequestWebController extends Controller
{
    public function store(Request $request, CreateEducationalRequestAction $createAction)
    {
        $isTaqaForm = config('app.brand') === 'taqa360' && $request->has('delivery_mode');
        $requestedService = Service::query()
            ->where('uuid', $request->input('service_id'))
            ->where('status', StatusEnum::ACTIVE->value)
            ->first();
        $requestConfig = $requestedService
            ? TaqaServiceRequestConfiguration::forService($requestedService)
            : [];
        $availableModes = match ($requestConfig['mode'] ?? 'both') {
            'onsite', 'remote' => [$requestConfig['mode']],
            default => ['onsite', 'remote'],
        };
        $isOnsite = $isTaqaForm && $request->input('delivery_mode') === 'onsite';
        $isFlexible = $request->boolean('is_flexible', true);
        $fieldDefinitions = $requestConfig['fields'] ?? [];
        $formRules = [];

        if ($isTaqaForm) {
            $formRules = [
                'delivery_mode' => ['required', Rule::in($availableModes)],
                'property_type' => [$isOnsite ? 'required' : 'nullable', Rule::in(['home', 'commercial', 'industrial', 'farm', 'public', 'other'])],
                'site_city' => [$isOnsite ? 'required' : 'nullable', Rule::in(['riyadh', 'jeddah', 'dammam', 'other'])],
                'site_city_other' => [$isOnsite && $request->input('site_city') === 'other' ? 'required' : 'nullable', 'string', 'max:100'],
                'service_address' => [$isOnsite ? 'required' : 'nullable', 'string', 'max:2000'],
                'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
                'access_notes' => ['nullable', 'string', 'max:2000'],
                'is_flexible' => ['required', 'boolean'],
                'preferred_date' => array_merge(
                    [$isFlexible ? 'nullable' : 'required', 'date'],
                    $isFlexible ? [] : ['after_or_equal:today']
                ),
                'preferred_period' => ['required', Rule::in(['flexible', 'morning', 'evening'])],
                'contact_name' => ['required', 'string', 'max:255'],
                'contact_phone' => ['required', 'string', 'max:30', 'regex:/^\+?[\d ()-]{9,30}$/'],
                'service_answers' => [
                    'nullable',
                    'array',
                    function ($attribute, $value, $fail) use ($fieldDefinitions) {
                        if (! is_array($value)) {
                            return;
                        }

                        $allowedKeys = array_column($fieldDefinitions, 'key');
                        if (array_diff(array_keys($value), $allowedKeys)) {
                            $fail('تتضمن إجابات الخدمة حقولاً غير معروفة.');
                        }
                    },
                ],
            ];

            foreach ($fieldDefinitions as $field) {
                if (empty($field['key'])) {
                    continue;
                }

                $rules = [! empty($field['required']) ? 'required' : 'nullable', 'string', 'max:5000'];
                if (($field['type'] ?? '') === 'number') {
                    $rules = array_merge($rules, ['numeric', 'min:0']);
                } elseif (($field['type'] ?? '') === 'select' && ! empty($field['options'])) {
                    $options = $field['options'];
                    if (is_array($options) && (isset($options['ar']) || isset($options['en']))) {
                        $localizedOptions = $options[app()->getLocale()] ?? [];
                        $fallbackOptions = $options['ar'] ?? [];
                        if (! is_array($fallbackOptions) || ! $fallbackOptions) {
                            $fallbackOptions = $options['en'] ?? [];
                        }
                        $options = is_array($localizedOptions) && $localizedOptions
                            ? $localizedOptions
                            : $fallbackOptions;
                    }
                    $rules[] = Rule::in(is_array($options) ? $options : []);
                }
                $formRules['service_answers.' . $field['key']] = $rules;
            }
        }

        $brandFields = config('app.brand') === 'huloolcom'
            ? [
                'academic_major_id' => ['nullable', 'exists:academic_majors,id'],
                'education_level' => ['nullable', 'string', 'max:100'],
            ]
            : [
                'project_site_type' => ['nullable', 'in:residential,commercial,agricultural,industrial,medical,educational,other'],
                'solar_system_type' => ['nullable', 'in:off-grid,on-grid,hybrid,not_sure'],
            ];

        $validated = $request->validate(array_merge([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['nullable', 'string'],
            'service_id' => ['required', 'uuid', function ($attribute, $value, $fail) {
                $available = Service::query()
                    ->where('uuid', $value)
                    ->where('status', StatusEnum::ACTIVE->value)
                    ->exists();

                if (!$available) {
                    $fail('الخدمة المحددة غير متاحة للطلب الجديد');
                }
            }],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'urgency_level' => ['nullable', 'in:normal,urgent,very_urgent'],
            'additional_notes' => ['nullable', 'string'],
            'attachments' => array_merge(['nullable', 'array'], $isTaqaForm ? ['max:5'] : []),
            'attachments.*' => array_merge(['file', 'max:10240'], $isTaqaForm ? ['mimes:jpg,jpeg,png,webp,pdf,docx'] : []),
        ], $brandFields, $formRules), [
            'title.required' => 'عنوان الطلب مطلوب',
            'title.max' => 'عنوان الطلب يجب ألا يتجاوز 255 حرفاً',
            'description.required' => 'وصف الطلب مطلوب',
            'service_id.required' => 'نوع الخدمة مطلوب',
            'service_id.exists' => 'الخدمة المحددة غير متاحة للطلب الجديد',
            'budget.numeric' => 'الميزانية يجب أن تكون رقماً',
            'budget.min' => 'الميزانية يجب أن تكون أكبر من أو تساوي 0',
            'attachments.*.max' => 'حجم كل ملف يجب ألا يتجاوز 10 ميجابايت',
            'attachments.*.uploaded' => 'تعذر استلام الملف من الخادم. تحقق من حجم الملف وحدود الرفع في الخادم، ثم أعد المحاولة.',
            'delivery_mode.required' => 'اختر طريقة تقديم الخدمة.',
            'property_type.required' => 'اختر نوع الموقع.',
            'site_city.required' => 'اختر المدينة.',
            'site_city_other.required' => 'اكتب اسم المدينة.',
            'service_address.required' => 'اكتب عنوان الموقع.',
            'preferred_date.required' => 'اختر التاريخ المفضل أو فعّل الموعد المرن.',
            'contact_name.required' => 'اسم جهة التواصل مطلوب.',
            'contact_phone.required' => 'رقم الجوال مطلوب.',
            'contact_phone.regex' => 'أدخل رقم جوال صالحاً.',
            'attachments.max' => 'يمكن إرفاق 5 ملفات كحد أقصى.',
        ]);

        try {
            $data = array_merge($validated, [
                'user_id' => auth()->id() ?? 1,
                'service_id' => Service::where('uuid', $validated['service_id'])->value('id'),
            ]);

            if (!empty($data['type'])) {
                $typeStr = strtolower($data['type']);
                $validTypes = \App\Application\Educational\Enums\EducationalRequestType::values();
                if (!in_array($typeStr, $validTypes)) {
                    $map = [
                        'graduation_project' => 'project',
                        'consultation' => 'general',
                        'tutoring' => 'explanation',
                    ];
                    $data['type'] = $map[$typeStr] ?? \App\Application\Educational\Enums\EducationalRequestType::GENERAL->value;
                } else {
                    $data['type'] = $typeStr;
                }
            } else {
                $data['type'] = \App\Application\Educational\Enums\EducationalRequestType::GENERAL->value;
            }

            if ($isTaqaForm) {
                $siteTypes = [
                    'home' => 'residential',
                    'commercial' => 'commercial',
                    'industrial' => 'industrial',
                    'farm' => 'agricultural',
                    'public' => 'other',
                    'other' => 'other',
                ];
                $data['project_site_type'] = $data['delivery_mode'] === 'onsite'
                    ? ($siteTypes[$data['property_type']] ?? null)
                    : null;

                if (!empty($data['project_site_type'])) {
                    $data['project_site_type'] = strtolower($data['project_site_type']);
                }

                if (!empty($data['solar_system_type'])) {
                    $data['solar_system_type'] = strtolower($data['solar_system_type']);
                }

                if (empty($requestConfig['solar'])) {
                    $data['solar_system_type'] = null;
                }

                if (empty($requestConfig['show_urgency'])) {
                    $data['urgency_level'] = 'normal';
                }

                $data['is_flexible'] = $request->boolean('is_flexible', true);
            }

            $educationalRequest = $createAction->handle($data);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تقديم طلب الخدمة بنجاح، وسيتم مراجعته من قبل الإدارة.',
                    'request' => $educationalRequest,
                ]);
            }

            return redirect()->back()->with('success', 'تم تقديم طلب الخدمة بنجاح، وسيتم مراجعته من قبل الإدارة.');
        } catch (\Exception $e) {
            Log::error('ServiceRequestWebController Store Error: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تقديم الطلب: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'حدث خطأ أثناء تقديم الطلب. الرجاء المحاولة مرة أخرى.');
        }
    }
}
