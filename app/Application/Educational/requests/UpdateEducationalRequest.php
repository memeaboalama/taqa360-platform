<?php

namespace App\Application\Educational\Requests;

use App\Application\Educational\Enums\EducationalRequestType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEducationalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'academic_major_id' => ['nullable', 'exists:academic_majors,uuid'],
            'education_level' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:'.implode(',', EducationalRequestType::values())],
            'expected_delivery_date' => ['nullable', 'date', 'after:today'],
            'additional_notes' => ['nullable', 'string'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الطلب مطلوب',
            'title.string' => 'عنوان الطلب يجب أن يكون نص',
            'title.max' => 'عنوان الطلب يجب أن يكون أقل من 255 حرف',
            'description.required' => 'وصف الطلب مطلوب',
            'description.string' => 'وصف الطلب يجب أن يكون نص',
            'description.min' => 'وصف الطلب يجب أن يكون على الأقل 50 حرف',
            'education_level.string' => 'المستوى التعليمي يجب أن يكون نص',
            'education_level.max' => 'المستوى التعليمي يجب أن يكون أقل من 100 حرف',
            'budget.numeric' => 'الميزانية يجب أن تكون رقم',
            'budget.min' => 'الميزانية يجب أن تكون أكبر من صفر',
            'type.in' => 'نوع الطلب غير صالح',
            'expected_delivery_date.date' => 'تاريخ التسليم المتوقع يجب أن يكون تاريخ صحيح',
            'expected_delivery_date.after' => 'تاريخ التسلي�� المتوقع يجب أن يكون بعد اليوم',
            'attachments.array' => 'المرفقات يجب أن تكون مصفوفة',
            'attachments.*.file' => 'كل مرفق يجب أن يكون ملف',
            'attachments.*.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
        ];
    }
}
