<?php

namespace Application\Project\Controllers\Api;

use App\Application\Project\Requests\Task\UploadAttachmentRequest;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Admin\Models\Admin;
use Domain\Project\Actions\Task\SubTask\StoreAttachmentAction;
use Illuminate\Http\Request;

use Domain\Project\Actions\Task\SubTask\CreateSubTaskAction;
use Domain\Project\Actions\Task\SubTask\FetchSubTaskAction;
use Domain\Project\Models\SubTask;
use Domain\Project\Models\Task;
use Domain\Project\Resources\Task\SubTaskResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SubTaskController extends Controller
{
    use ApiResponseTrait;

    public function fetch(Task $task, FetchSubTaskAction $fetchSubTaskAction): JsonResponse
    {
        $subTasks = $fetchSubTaskAction->handle($task);

        return $this->responseData(data: [
            'subTasks' => SubTaskResource::collection($subTasks),
        ]);
    }

    public function store(Request $request, Task $task, CreateSubTaskAction $createSubTaskAction): JsonResponse
    {
        try {
            $createor = Admin::first();
            $subTask = $createSubTaskAction->handle($createor,['title' => $request->title,'task_id' => $task->id]);

            return $this->responseData(data: [
                'subTask' => new SubTaskResource($subTask),
            ]);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to create sub-task", code: 500);
        }
    }

    public function changeCompletedStatus(SubTask $subTask): JsonResponse
    {
        try {
            $subTask->completed = !$subTask->completed;
            $subTask->completed_at = $subTask->completed ? now() : null;
            $subTask->save();

            return $this->responseData(data: [
                'subTask' => new SubTaskResource($subTask)
            ]);
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }


    public function update(Request $request, SubTask $subTask): JsonResponse
    {
        try {
            $subTask->title = $request->title;
            $subTask->modified_at = now();
            $subTask->save();

            return $this->responseData(data: [
                'subTask' => new SubTaskResource($subTask)
            ]);
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }


    public function destroy(SubTask $subTask): JsonResponse
    {
        try {
            $subTask->delete();

            return $this->responseData(data: [], msg: "Sub-task Deleted successfully", code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to delete sub-task", code: 500);
        }
    }


    public function uploadAttachment(UploadAttachmentRequest $request, SubTask $subTask, StoreAttachmentAction $storeAttachmentAction): JsonResponse
    {
        try {
            $subTask = $storeAttachmentAction->handle($request, $subTask);
            return $this->responseData(
                data: ['subTask' => new SubTaskResource($subTask)],
                msg: "Attachment uploaded successfully"
            );

        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }
}
