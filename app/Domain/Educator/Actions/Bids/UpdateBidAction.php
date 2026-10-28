<?php

namespace App\Domain\Educator\Actions\Bids;

use App\Domain\Project\Exceptions\BidException;
use App\Support\Services\ImagesServices\ImageService;
use Domain\Project\Models\Bid;
use Domain\Project\Models\BidTask;
use Domain\Project\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UpdateBidAction
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    public function handle(Bid $bid, array $data): Bid
    {
        return DB::transaction(function () use ($bid, $data) {
            $this->validateBidCanBeUpdated($bid);

            $this->updateBidDetails($bid, $data);

            if (isset($data['links'])) {
                $this->updateLinks($bid, $data['links']);
            }

            if (isset($data['bid_tasks'])) {
                $this->updateBidTasks($bid, $data['bid_tasks']);
            }

            if (isset($data['attachments'])) {
                $this->updateAttachments($bid, $data['attachments']);
            }

            if (isset($data['deleted_attachments'])) {
                $this->deleteAttachments($bid, $data['deleted_attachments']);
            }

            if (isset($data['deleted_links'])) {
                $this->deleteLinks($bid, $data['deleted_links']);
            }

            return $bid->fresh()->load(['project', 'bidTasks.task', 'attachments', 'links']);
        });
    }

    private function validateBidCanBeUpdated(Bid $bid): void
    {
        throw_unless($bid->isUpdatable(), BidException::class, 'Bid cannot be updated if any task is not in PENDING status.', 403);
    }

    private function updateBidDetails(Bid $bid, array $data): void
    {
        $updateData = array_filter([
            'proposal' => $data['proposal'] ?? null,
            'delivery_days' => $data['delivery_days'] ?? null,
        ], fn($value) => !is_null($value));

        if (!empty($updateData)) {
            $bid->update($updateData);
        }
    }

    private function updateLinks(Bid $bid, array $links): void
    {
        foreach ($links as $linkData) {
            if (isset($linkData['id'])) {
                $this->updateExistingLink($bid, $linkData);
            } else {
                $this->createNewLink($bid, $linkData);
            }
        }
    }

    private function updateExistingLink(Bid $bid, array $linkData): void
    {
        $link = $bid->links()->find($linkData['id']);
        if ($link) {
            $link->update([
                'title' => $linkData['title'] ?? $link->title,
                'url' => $linkData['url'] ?? $link->url
            ]);
        }
    }

    private function createNewLink(Bid $bid, array $linkData): void
    {
        $bid->links()->create([
            'title' => $linkData['title'],
            'url' => $linkData['url'],
        ]);
    }

    private function deleteLinks(Bid $bid, array $linkIds): void
    {
        $bid->links()->whereIn('id', $linkIds)->delete();
    }

    private function updateBidTasks(Bid $bid, array $bidTasks): void
    {
        foreach ($bidTasks as $taskData) {
            if (isset($taskData['task_id'])) {
                $taskId = Task::where('uuid', $taskData['task_id'])->first();
                if($taskId) {
                    $bidTaskId = $bid->bidTasks()->where('task_id', $taskId->id)->value('id');
                    if($bidTaskId) {
                        $taskData['id'] = $bidTaskId;
                        $this->updateExistingTask($bid, $taskData);
                    } else {
                        $this->createNewTask($bid, $taskData);
                    }
                }
            } else {
                $this->createNewTask($bid, $taskData);
            }
        }
    }

    private function updateExistingTask(Bid $bid, array $taskData): void
    {
        $bidTask = $bid->bidTasks()->find($taskData['id']);
        Log::info('Updating Existing Task', ['bid_task' => $bidTask, 'task_data' => $taskData]);
        if ($bidTask) {
            $bidTask->update([
                'amount' => $taskData['amount'] ?? $bidTask->amount
            ]);
        }
    }

    private function createNewTask(Bid $bid, array $taskData): void
    {
        $task = Task::where('uuid', $taskData['task_id'])->first();
        if ($task) {
            $bid->bidTasks()->create([
                'task_id' => $task->id,
                'amount' => $taskData['amount']
            ]);
        }
    }

    private function updateAttachments(Bid $bid, array $attachments): void
    {
        foreach ($attachments as $attachmentData) {
            if (isset($attachmentData['id'])) {
                $this->updateExistingAttachment($bid, $attachmentData);
            } elseif (isset($attachmentData['file']) && $attachmentData['file'] instanceof UploadedFile) {
                $this->createNewAttachment($bid, $attachmentData);
            }
        }
    }

    private function updateExistingAttachment(Bid $bid, array $attachmentData): void
    {
        $file = $bid->attachments()->find($attachmentData['id']);
        if ($file && isset($attachmentData['title'])) {
            $file->update(['title' => $attachmentData['title']]);
        }
    }

    private function createNewAttachment(Bid $bid, array $attachmentData): void
    {
        $path = "bids/attachments";
        $disk = 'fileDisk';

        $fileDetails = $this->imageService->storeImage($attachmentData['file'], $path, "BIDS", $disk, 'webp' );

        $fileData = array_merge([
            'collection_name' => 'bid_attachments',
            'title' => $attachmentData['title'] ?? null,
            'uploader_type' => get_class($bid->educator),
            'uploader_id' => $bid->educator->id,
        ], $fileDetails);

        $bid->attachments()->create($fileData);
    }

    private function deleteAttachments(Bid $bid, array $attachmentIds): void
    {
        $files = $bid->attachments()->whereIn('id', $attachmentIds)->get();

        foreach ($files as $file) {
            $this->imageService->destroyImg($file->file_path, $file->disk);
            $file->delete();
        }
    }
}
