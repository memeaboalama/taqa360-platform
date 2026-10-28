<?php

namespace Domain\Project\Actions\Task\SubTask;

use App\Support\Services\ImagesServices\ImageService;
use Domain\Admin\Models\Admin;
use Domain\Project\Models\SubTask;
use Domain\Project\Models\Task;
use Domain\Project\Repositories\SubTaskRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class StoreAttachmentAction
{
    public function __construct(
        protected SubTaskRepository $subTaskRepository,
        protected ImageService $imageService
    ){}

    public function handle(Request $request, SubTask $subTask): SubTask
    {
        $path = "projects/tasks/subTasks";
        $disk = 'fileDisk';

        $uploader = Auth::user() ?? Admin::find(1);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            if ($file instanceof UploadedFile) {

                    $fileDetails = $this->imageService->storeImage($file, $path, "SUBTASK_ATTACHMENT", $disk, 'webp');
                    $fileData = array_merge([
                        'title' => $request->title ?? $fileDetails['original_name'],
                        'collection_name' => 'subTask_attachment',
                        'uploader_type' => $uploader ? get_class($uploader) : null,
                        'uploader_id' => $uploader?->id,
                    ], $fileDetails);

                    $subTask = $this->subTaskRepository->storeAttachment($subTask, $fileData);
                    return $subTask->load('attachments');
            }
        }
        throw new \Exception("No attachment found in the request");
    }
}
