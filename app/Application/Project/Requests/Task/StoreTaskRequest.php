<?php

namespace App\Application\Project\Requests\Task;

use App\Application\Project\Enums\CreationType;
use Domain\Project\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,uuid',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'deadline' => 'required|date|after:today',
            'is_public' => 'nullable|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'معرف المشروع مطلوب',
            'project_id.exists' => 'المشروع غير موجود',
            'title.required' => 'عنوان المهمة مطلوب',
            'title.string' => 'عنوان المهمة يجب أن يكون نص',
            'title.max' => 'عنوان المهمة يجب أن يكون أقل من 255 حرف',
            'description.required' => 'وصف المهمة مطلوب',
            'description.string' => 'وصف المهمة يجب أن يكون نص',
            'budget.numeric' => 'الميزانية يجب أن تكون رقم',
            'budget.min' => 'الميزانية يجب أن تكون أكبر من أو تساوي صفر',
            'deadline.required' => 'الموعد النهائي مطلوب',
            'deadline.date' => 'الموعد النهائي يجب أن يكون تاريخ صحيح',
            'deadline.after' => 'الموعد النهائي يجب أن يكون بعد اليوم',
            'is_public.boolean' => 'حالة النشر يجب أن تكون صحيح أو خطأ'
        ];
    }
}
