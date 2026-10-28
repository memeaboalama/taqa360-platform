<?php

namespace App\Application\Educational\Controllers;

use App\Domain\EducationalRequest\Actions\CreateEducationalRequestAction;
use App\Http\Controllers\Controller;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceRequestWebController extends Controller
{
    public function store(Request $request, CreateEducationalRequestAction $createAction)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'academic_major_id' => ['nullable'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string'],
            'budget' => ['nullable', 'numeric', 'min:0'],
            'urgency_level' => ['nullable', 'in:normal,urgent,very_urgent'],
            'additional_notes' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ], [
            'title.required' => 'عنوان الطلب مطلوب',
            'title.max' => 'عنوان الطلب يجب ألا يتجاوز 255 حرفاً',
            'description.required' => 'وصف الطلب مطلوب',
            'budget.numeric' => 'الميزانية يجب أن تكون رقماً',
            'budget.min' => 'الميزانية يجب أن تكون أكبر من أو تساوي 0',
            'attachments.*.max' => 'حجم كل ملف يجب ألا يتجاوز 10 ميجابايت',
            'attachments.*.uploaded' => 'تعذر استلام الملف من الخادم. تحقق من حجم الملف وحدود الرفع في الخادم، ثم أعد المحاولة.',
        ]);

        try {
            $data = array_merge($validated, [
                'user_id' => auth()->id() ?? 1,
            ]);

            if (!empty($data['academic_major_id'])) {
                $major = AcademicMajor::where('id', $data['academic_major_id'])
                    ->orWhere('uuid', $data['academic_major_id'])
                    ->first();
                if ($major) {
                    $data['academic_major_id'] = $major->id;
                } else {
                    $data['academic_major_id'] = null;
                }
            }

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
