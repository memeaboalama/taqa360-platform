<?php

namespace App\Application\Project\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'attachment' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,gif,bmp,webp,pdf,doc,docx,zip'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'عنوان المرفق يجب أن يكون نص.',
            'title.max' => 'عنوان المرفق يجب أن يكون أقل من 255 حرف.',
            'attachment.file' => 'المرفق يجب أن يكون ملف.',
            'attachment.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت.',
            'description.string' => 'الوصف يجب أن يكون نص.',
        ];
    }
}
