<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Data\Attachment\CreateAttachmentData;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BulkDeleteAttachmentsAction
{
    /**
     * @param array $attachmentIds An array of attachment IDs to delete.
     */
    public function execute(array $attachmentIds): void
    {
        $attachments = Attachment::whereIn('id', $attachmentIds)->get();

        foreach ($attachments as $attachment) {
            if ($attachment->path && $attachment->storage) {
                Storage::disk($attachment->storage)->delete($attachment->path);
            }
        }

        // This will also cascade delete the entries in the 'attachables' pivot table.
        Attachment::whereIn('id', $attachmentIds)->delete();
    }
}