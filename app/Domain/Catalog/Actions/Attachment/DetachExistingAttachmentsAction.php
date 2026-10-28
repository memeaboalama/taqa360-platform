<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Data\Attachment\CreateAttachmentData;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetachExistingAttachmentsAction
{

    /**
     * @param Model $attachable
     * @param array $attachmentIds
     */
    public function execute(Model $attachable, array $attachmentIds): void
    {
        // syncWithoutDetaching ensures we don't remove existing attachments
        // and only adds the new, unique ones from the array.
        $attachable->attachments()->detach($attachmentIds);
    }
    /**
     * @param Item $item
     * @param array $attachmentIds
     */
    public function executeItem(Item $item, array $attachmentIds): void
    {
        // syncWithoutDetaching ensures we don't remove existing attachment
        // and only adds the new, unique ones from the array.
        $item->attachments()->detach($attachmentIds);
    }
}