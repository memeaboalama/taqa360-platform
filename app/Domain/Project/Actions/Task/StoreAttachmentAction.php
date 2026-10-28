<?php

namespace Domain\Project\Actions\Task;

use App\Support\Services\ImagesServices\ImageService;
use Domain\Admin\Models\Admin;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class StoreAttachmentAction
{
    public function __construct(
        protected TaskRepository $taskRepository,
        protected ImageService $imageService
    ){}

    public function handle(Request $request, Task $task, string $type = "TASK_ATTACHMENT", ?string $collectionName = null, $uploader = null): Task
    {
        $path = "projects/tasks/attachments";
        $disk = 'fileDisk';

        $uploader = $uploader ?? Admin::find(1);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file instanceof UploadedFile) {

                    $fileDetails = $this->imageService->storeImage($file, $path, $type, $disk, 'webp');
                    $fileData = array_merge([
                        'title' => $request->title ?? $fileDetails['original_name'],
                        'collection_name' => $collectionName ?? Str::lower($type),
                        'uploader_type' => get_class($uploader),
                        'uploader_id' => $uploader?->id,
                    ], $fileDetails);

                    $task = $this->taskRepository->storeAttachment($task, $fileData);
                    // event(new TaskUpdated($task));
                    return $task->load('attachments');
            }
        }
        throw new \Exception("No attachment found in the request");
    }
}
