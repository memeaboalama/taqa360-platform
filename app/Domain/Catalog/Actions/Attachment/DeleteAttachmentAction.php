<?php

namespace App\Domain\Catalog\Actions\Attachment;

use App\Domain\Catalog\Models\Attachment;
use App\Domain\Catalog\Data\Attachment\DeleteAttachmentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DeleteAttachmentAction
{
    public function execute(Attachment $attachment): void
    {
        DB::transaction(function () use ($attachment) {

            // If it's a local file, delete it from storage
            if ($attachment->path && $attachment->storage) {
                Storage::disk($attachment->storage)->delete($attachment->path);
            }
    
            // The polymorphic relationship in the 'attachables' table will be deleted automatically
            // if the foreign key has onDelete('cascade').
            $attachment->delete();
            
        });
    }
}