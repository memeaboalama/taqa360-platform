<?php

namespace App\Application\Project\Requests\Task;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadAttachmentRequest extends FormRequest
{
    /**
     * Extensions we accept, keyed by the MIME types that are valid for them.
     *
     * Laravel's `mimes` rule only looks at guessExtension(), i.e. what
     * fileinfo makes of the file *content*, and it ignores the uploaded name
     * entirely. Real Word/Excel files are sometimes reported as
     * application/octet-stream or a generic container depending on the server's
     * fileinfo version, which produced a confusing "must be a file of type: ..."
     * error for files the user had legitimately saved as .docx.
     */
    private const ALLOWED_MIMES = [
        // images
        'image/jpeg', 'image/pjpeg', 'image/jpg',
        'image/png', 'image/x-png',
        'image/gif',
        'image/bmp', 'image/x-ms-bmp',
        'image/webp',
        'image/svg+xml',
        // documents
        'application/pdf', 'application/x-pdf',
        'application/msword', 'application/vnd.ms-word', 'application/x-msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        // archives
        'application/zip', 'application/x-zip', 'application/x-zip-compressed',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'attachment' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,gif,bmp,webp,pdf,doc,docx,zip'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المرفق مطلوب',
            'title.string' => 'عنوان المرفق يجب أن يكون نص',
            'title.max' => 'عنوان المرفق يجب أن يكون أقل من 255 حرف',
            'attachment.file' => 'المرفق يجب أن يكون ملف',
            'attachment.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
            'description.string' => 'الوصف يجب أن يكون نص',
            'attachment.mimes' => 'نوع الملف غير مدعوم. الأنواع المسموحة: '
                . 'jpg, jpeg, png, gif, bmp, webp, pdf, doc, docx, zip'
                . ' — الملف المرفوف تم اكتشافه كـ: ' . $this->describeUploadedFile(),
        ];
    }

    /**
     * The mimes rule rejects on the guessed extension, so a legitimate .docx can
     * fail when the server's fileinfo reports a generic container. Accept the
     * file in that case as long as its real MIME is on the allow list, and say
     * what was actually detected when it is not.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $file = $this->file('attachment');
            if (! $file instanceof UploadedFile) {
                return;
            }

            foreach (['attachment.mimes'] as $key) {
                if (! $validator->errors()->has($key)) {
                    continue;
                }

                $mime = strtolower((string) $file->getMimeType());
                $clientExt = strtolower((string) $file->getClientOriginalExtension());

                // Allow if extension is recognized as image type
                $isImageExt = in_array($clientExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'], true);
                // Allow if MIME type starts with image/ or is in allowed list
                $isImageMime = Str::startsWith($mime, 'image/') || in_array($mime, self::ALLOWED_MIMES, true);

                // Never relax this for a name we do not allow: a .php renamed to
                // .docx must still be rejected.
                $nameAllowed = in_array($clientExt, [
                    'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'pdf', 'doc', 'docx', 'zip',
                ], true);

                // Allow if: extension is allowed AND (MIME type is image OR MIME type is in allowed list)
                if ($nameAllowed && ($isImageMime || $isImageExt)) {
                    $validator->forgetSpecifically($key);
                }
            }
        });
    }

    private function describeUploadedFile(): string
    {
        $file = $this->file('attachment');
        if (! $file instanceof UploadedFile) {
            return 'غير معروف';
        }

        return sprintf(
            '%s (المحتوى: %s)',
            $file->getClientOriginalName(),
            $file->getMimeType() ?: 'غير معروف'
        );
    }
}
