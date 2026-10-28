<?php

namespace App\Application\Educator\Requests\Bids;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class CreateBidRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => 'required|string|exists:projects,uuid',
            'proposal' => 'nullable|string',
            'delivery_days' => 'nullable|integer|min:1',
            'links' => 'nullable|array',

            'attachments' => 'nullable|array',
            'attachments.*.title' => 'required_with:attachments|string|max:255',
            'attachments.*.file' => 'required_with:attachments|file|max:10240',

            'bid_tasks' => 'required|array|min:1',
            'bid_tasks.*.task_id' => 'required|string|exists:tasks,uuid',
            'bid_tasks.*.amount' => 'required|numeric|min:0',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        foreach ($validated['attachments'] ?? [] as $key => $attachment) {
            if ($attachment['file'] instanceof UploadedFile) {
                $validated['attachments'][$key]['file'] = $attachment['file'];
            }
        }

        return $validated;
    }
}
