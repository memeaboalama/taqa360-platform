<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Data\Attachment\CreateAttachmentData;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Model;


class ReorderAttachmentsAction
{
    /**
     * @param Model $attachable The item whose attachments are being reordered.
     * @param array $orderedAttachmentIds An array of attachment IDs in the desired order.
     */
    public function execute(Model $attachable, array $orderedAttachmentIds): void
    {
        DB::transaction(function () use ($attachable, $orderedAttachmentIds) {
            foreach ($orderedAttachmentIds as $index => $attachmentId) {
                $attachable->attachments()
                     ->where('attachments.id', $attachmentId)
                     ->updateExistingPivot($attachmentId, ['sort_order' => $index]);
            }
        });
    }

    
    /**
     * @param Item $item The item whose attachments are being reordered.
     * @param array $orderedAttachmentIds An array of attachment IDs in the desired order.
     */
    public function executeItem(Item $item, array $orderedAttachmentIds): void
    {
        DB::transaction(function () use ($item, $orderedAttachmentIds) {
            foreach ($orderedAttachmentIds as $index => $attachmentId) {
                $item->attachments()
                     ->where('attachments.id', $attachmentId)
                     ->updateExistingPivot($attachmentId, ['sort_order' => $index]);
            }
        });
    }
}