<?php

namespace App\Application\Project\Requests\Task;

use App\Application\Project\Enums\AssignmentType;
use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'educators' => 'required|array',
            'educators.*' => 'required|exists:educators,uuid',
            'assigned_type' => 'required|in:'.implode(',', AssignmentType::values()),
            'amount' => ['nullable', 'numeric', 'min:0'],
            'agreement_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'educators.required' => 'قائمة المختصين مطلوبة',
            'educators.array' => 'قائمة المختصين يجب أن تكون مصفوف',
            'educators.id.required' => 'معرف المختص مطلوب',
            'educators.id.exists' => 'المختص غير موجود',
            'assigned_type.required' => 'نوع التعيين مطلوب',
            'assigned_type.in' => 'نوع التعيين يجب أن يكون دعوة أو مباشر أو من عطاء',
            'amount.numeric' => 'المبلغ يجب أن يكون رقم',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من أو يساوي صفر',
            'agreement_notes.string' => 'ملاحظات الاتفاق يجب أن تكون نص',
            'agreement_notes.max' => 'ملاحظات الاتفاق يجب أن تكون أقل من 1000 حرف',
        ];
    }
}
