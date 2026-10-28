<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Catalog\Data\Attachment\CreateAttachmentData;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;


class SetPrimaryAttachmentAction
{
    public function execute(Model $attachable, Attachment $attachmentToMakePrimary): void
    {
        DB::transaction(function () use ($attachable, $attachmentToMakePrimary) {
            // First, set all other attachments for this attachable to not be primary
            $attachable->attachments()->updateExistingPivot(null, ['is_primary' => false]);

            // Then, set the specified attachment as primary
            $attachable->attachments()->updateExistingPivot($attachmentToMakePrimary->id, ['is_primary' => true]);
        });
    }
    
    public function executeItem(Item $item, Attachment $attachmentToMakePrimary): void
    {
        DB::transaction(function () use ($item, $attachmentToMakePrimary) {
            // First, set all other attachments for this item to not be primary
            $item->attachments()->updateExistingPivot(null, ['is_primary' => false]);

            // Then, set the specified attachment as primary
            $item->attachments()->updateExistingPivot($attachmentToMakePrimary->id, ['is_primary' => true]);
        });
    }
}