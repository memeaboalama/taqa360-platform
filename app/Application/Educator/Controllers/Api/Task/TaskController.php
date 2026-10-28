<?php

namespace App\Application\Educator\Controllers\Api\Task;

use App\Actions\Project\Task\CreateSubTaskAction;
use App\Actions\Project\Task\CreateTaskNoteAction;
use App\Actions\Project\Task\UpdateSubTaskAction;
use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\TaskStatus;
use App\Application\Project\Requests\Task\UploadAttachmentRequest;
use App\Domain\Educator\Resource\Task\TaskDetailResource;
use App\Domain\Educator\Resource\Task\TaskFileResource;
use App\Domain\Educator\Resource\Task\TaskResource;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\V1\Educator\Project\Task\StoreTaskNoteRequest;
use App\Http\Requests\Api\V1\Educator\Project\Task\UpdateSubTaskRequest;

use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Project\Actions\Task\StoreAttachmentAction;
use Domain\Project\Models\SubTask;
use Domain\Project\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    use ApiResponseTraitV2;

    protected function assertEducatorOwnsTask(Request $request, Task $task): bool
    {
        return $task->assignments()->where('assigned_to', $request->user()->id)->where('status', 'accepted')->exists();
    }

    protected function assertEducatorCanViewTask(Request $request, Task $task): bool
    {
        // a cancelled assignment withdraws the relationship, so the task must not
        // be viewable by that specialist any more
        return $task->assignments()
            ->where('assigned_to', $request->user()->id)
            ->where('status', '!=', \App\Application\Project\Enums\AssignmentStatus::CANCELLED)
            ->exists();
    }

    public function fetch(Request $request): JsonResponse
    {
        $educatorId = auth()->id();
        $tasks = Task::whereHas('assignments', function ($query) use ($educatorId) {
                $query->where('assigned_to', $educatorId)
                    ->where('status', AssignmentStatus::ACCEPTED);
            })
            ->with(['assignments' => function ($query) use ($educatorId) {
                $query->where('assigned_to', $educatorId)
                    ->whereIn('status', [
                        AssignmentStatus::ACCEPTED,
                        AssignmentStatus::COMPLETED,
                        AssignmentStatus::REJECTED,
                        AssignmentStatus::REJECTED_BY_EDUCATOR,
                        AssignmentStatus::CANCELLED
                    ]);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->responseData([
            'tasks' => TaskResource::collection($tasks->load(['project']))
        ], 200, 'Tasks retrieved successfully');
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        if (!$this->assertEducatorCanViewTask($request, $task))
            return $this->responseError([], 403, 'You are not authorized to view this task.');

        return $this->responseData([
            'task' => TaskDetailResource::make($task->load(['project','subTasks', 'notes.creatable', 'attachments', 'assignments', 'activityLog']))]
            , 200, 'Task details retrieved successfully'
        );
    }

    public function complete(Request $request, Task $task): JsonResponse
    {
        if (! $this->assertEducatorOwnsTask($request, $task)) {
            return $this->responseError([], 403, 'Unauthorized');
        }

        $task->update(['status' => TaskStatus::COMPLETED, 'completed_at' => now()]);

        // the message was passed as the 2nd positional argument, which is $code
        // (an int), so completing a task always ended in a 500 TypeError
        return $this->responseData(['task' => $task->fresh(['assignments','subTasks','attachments','notes'])], 200, 'Task completed');
    }

    public function uploadAttachment(UploadAttachmentRequest $request, Task $task, StoreAttachmentAction $storeAttachmentAction): JsonResponse
    {
        try {
            if (!$this->assertEducatorOwnsTask($request, $task))
                return $this->responseError([], 403, 'You are not authorized to view this task.');

            $task = $storeAttachmentAction->handle($request, $task, "TASK_DELIVERABLES", null, auth()->user());

            $deliverable = $task->attachments()->where('collection_name', 'task_deliverables')->orderByDesc('id')->first();

            return $this->responseData(
                data: ['deliverable' => new TaskFileResource($deliverable)],
                msg: "Deliverable uploaded successfully"
            );

        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "File upload failed.", code: 400);
        }
    }

    public function destroyDeliverable(Task $task, string $deliverableId): JsonResponse
    {
        $educatorId = auth()->id();
        $isAssigned = $task->assignments()->where('assigned_to', $educatorId)->where('status', AssignmentStatus::ACCEPTED)->exists();

        if (!$isAssigned) {
            return $this->responseError([], 403, 'You are not authorized to delete deliverables from this task.');
        }

        $file = $task->attachments()->where('uuid', $deliverableId)->where('collection_name', 'task_deliverables')->firstOrFail();

        Storage::disk($file->storage)->delete($file->path);
        $file->delete();

        return $this->responseData([], 200, 'Deliverable deleted successfully');
    }

    // public function storeTaskNote(StoreTaskNoteRequest $request, Task $task, CreateTaskNoteAction $createTaskNoteAction): JsonResponse
    // {
    //     $educatorId = auth()->id();
    //     $isAssigned = $task->assignments()->where('assigned_to', $educatorId)->where('status', AssignmentStatus::ACCEPTED)->exists();

    //     if (!$isAssigned) {
    //         return $this->responseError([], 403, 'You are not authorized to add notes to this task.');
    //     }

    //     $note = $createTaskNoteAction->execute($task, $request->validated(), $educatorId);

    //     return $this->responseData([
    //         'note' => TaskNoteResource::make($note)
    //     ], 201, 'Note added successfully');
    // }
}
