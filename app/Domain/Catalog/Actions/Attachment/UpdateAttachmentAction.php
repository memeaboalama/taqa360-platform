<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Data\Attachment\UpdateAttachmentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UpdateAttachmentAction
{
    public function execute(Attachment $attachment, UpdateAttachmentRequest $data): Attachment
    {
        return DB::transaction(function () use ($attachment, $data) {
            $attachment->update($data->toArray());

            // return $attachment->fresh('properties');
            return $attachment->fresh();
            
        });
    }
}