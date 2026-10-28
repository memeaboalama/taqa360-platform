<?php

namespace App\Application\Educator\Controllers\Api\Task;

use App\Application\Project\Requests\Task\UploadAttachmentRequest;
use App\Domain\Educator\Resource\Task\SubTaskResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\Project\Models\SubTask;
use Domain\Project\Models\Task;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Admin\Models\Admin;
use Domain\Project\Actions\Task\SubTask\CreateSubTaskAction;
use Domain\Project\Actions\Task\SubTask\FetchSubTaskAction;
use Domain\Project\Actions\Task\SubTask\StoreAttachmentAction;
use Domain\Project\Actions\Task\SubTask\UpdateSubTaskAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SubTaskController extends Controller
{
    use ApiResponseTraitV2;

    protected function assertEducatorOwnsSubTask(Request $request, SubTask $subTask): bool
    {
        return $subTask->creatable_id === $request->user()->id && $subTask->creatable_type === get_class($request->user());
    }

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
            $createor = $request->user();
            $subTask = $createSubTaskAction->handle($createor,['title' => $request->title,'task_id' => $task->id]);

            return $this->responseData(data: [
                'subTask' => new SubTaskResource($subTask),
            ]);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to create sub-task", code: 500);
        }
    }

    public function update(Request $request, SubTask $subTask, UpdateSubTaskAction $updateSubTaskAction): JsonResponse
    {
        try {
            $subTask = $updateSubTaskAction->handle($subTask->id, $request->only('title'));
            return $this->responseData(['subTask' => SubTaskResource::make($subTask)], 200, 'Sub-task updated successfully');
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to update sub-task", code: 500);
        }
    }

    public function destroy(Request $request, SubTask $subTask): JsonResponse
    {
        try {
            $subTask->delete();
            return $this->responseData(data: [], msg: "Sub-task Deleted successfully", code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to delete sub-task", code: 400);
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

    public function toggleComplete(Request $request, SubTask $subTask)
    {
        try {
            $subTask->completed = !$subTask->completed;
            $subTask->completed_at = $subTask->completed ? now() : null;
            $subTask->save();

            return $this->responseData(['subTask' => new SubTaskResource($subTask)], msg:'Subtask toggled');

        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to toggle Complete sub-task", code: 400);
        }
    }
}
