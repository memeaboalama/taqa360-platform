<?php

namespace App\Application\Educator\Requests\Bids;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class UpdateBidRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal' => 'sometimes|nullable|string',
            'delivery_days' => 'sometimes|nullable|integer|min:1',

            'links' => 'sometimes|nullable|array',
            'links.*.id' => 'sometimes|nullable|integer|exists:links,id',
            'links.*.title' => 'sometimes|required_with:links.*.url|string|max:255',
            'links.*.url' => 'sometimes|required_with:links.*.title|url|max:500',

            'deleted_links' => 'sometimes|nullable|array',
            'deleted_links.*' => 'sometimes|integer|exists:links,id',

            'bid_tasks' => 'sometimes|nullable|array',
            'bid_tasks.*.id' => 'sometimes|nullable|integer|exists:bid_tasks,id',
            'bid_tasks.*.task_id' => 'sometimes|required_without:bid_tasks.*.id|string|exists:tasks,uuid',
            'bid_tasks.*.amount' => 'sometimes|required|numeric|min:0',

            'attachments' => 'sometimes|nullable|array',
            'attachments.*.id' => 'sometimes|nullable|integer|exists:files,id',
            'attachments.*.title' => 'sometimes|required_with:attachments|string|max:255',
            'attachments.*.file' => 'sometimes|required_without:attachments.*.id|file|max:10240',

            'deleted_attachments' => 'sometimes|nullable|array',
            'deleted_attachments.*' => 'sometimes|integer|exists:files,id',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        foreach ($validated['attachments'] ?? [] as $key => $attachment) {
            if (isset($attachment['file']) && $attachment['file'] instanceof UploadedFile) {
                $validated['attachments'][$key]['file'] = $attachment['file'];
            }
        }

        return $validated;
    }
}
