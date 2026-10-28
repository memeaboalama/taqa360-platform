<?php

namespace Application\Project\Controllers\Api;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Requests\Project\ChangeProjectStatusRequest;
use App\Application\Project\Requests\Task\AssignmentRequest;
use App\Application\Project\Requests\Task\StoreTaskNoteRequest;
use App\Application\Project\Requests\Task\StoreTaskRequest;
use App\Application\Project\Requests\Task\UpdateTaskRequest;
use App\Application\Project\Requests\Task\UploadAttachmentRequest;
use App\Domain\Project\Actions\Task\Assignmts\AssignTaskToEducatorAction;
use App\Domain\Project\Actions\Task\Assignmts\CancelAssignmentAction;
use App\Domain\Project\Actions\Task\Bidding\CloseBaddingTaskAction;
use App\Domain\Project\Actions\Task\Bidding\OpenBaddingTaskAction;
use App\Domain\Project\Actions\Task\CreateTaskNoteAction;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use App\Domain\Project\Actions\Task\UpdateTaskNoteAction;
use Domain\Project\Resources\Task\TaskNoteResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;

use Domain\Project\Actions\Task\CreateTaskAction;
use Domain\Project\Actions\Task\TaskChangeStatusAction;
use Domain\Educator\Models\Educator;
use Domain\Project\Actions\Task\DeleteTaskAction;
use Domain\Project\Actions\Task\StoreAttachmentAction;
use Domain\Project\Actions\Task\SubTask\FetchSubTaskAction;
use Domain\Project\Actions\Task\UpdateTaskAction;
use Domain\Project\Models\Assignment;
use Domain\Project\Models\Task;
use Domain\Project\Models\TaskNote;
use Domain\Project\Resources\Task\AssignmentResource;
use Domain\Project\Resources\Task\SubTaskResource;
use Domain\Project\Resources\Task\TaskDetailResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponseTrait;

    public function store(StoreTaskRequest $request, CreateTaskAction $createTaskAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        $taskData = $request->validated();
        $task = $createTaskAction->handle($taskData);

        $logActivity->forTask($task, LogProjectActivityAction::TASK_CREATED, [
            'title' => $task->title,
        ]);

        return $this->responseData(data: [
            'task' => new TaskDetailResource($task->load(['assignments.educator']))
        ]);
    }

    public function show(Task $task): JsonResponse
    {
        return $this->responseData(data: [
            'task' => new TaskDetailResource($task->load(['assignments.educator', 'subTasks', 'attachments', 'notes.creatable', 'activityLog']))
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task, UpdateTaskAction $updateTaskAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $updatedTask = $updateTaskAction->handle($task->id, $request->validated());
            $logActivity->forTask($task, LogProjectActivityAction::TASK_UPDATED, [
                'title' => $updatedTask->title,
            ]);
            return $this->responseData(data: ['task' => new TaskDetailResource($updatedTask)],msg:"Task Updated successfully" , code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to update task", code: 500);
        }
    }

    public function destroy(Task $task, DeleteTaskAction $deleteTaskAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            // log before deleting: the row cascades away with the task
            $logActivity->forTask($task, LogProjectActivityAction::TASK_DELETED, [
                'title' => $task->title,
            ]);
            $deleteTaskAction->handle($task);
            return $this->responseData(data: [],msg:"Task Deleted successfully" , code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to delete task", code: 500);
        }
    }


    public function changeStatus(ChangeProjectStatusRequest $request, Task $task, TaskChangeStatusAction $taskChangeStatusAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $previous = $task->status instanceof \BackedEnum ? $task->status->value : $task->status;
            $updatedTask = $taskChangeStatusAction->handle($task, $request->validated());
            $logActivity->forTask($task, LogProjectActivityAction::TASK_STATUS_CHANGED, [
                'from' => (string) $previous,
                'to' => (string) ($updatedTask->status instanceof \BackedEnum ? $updatedTask->status->value : $updatedTask->status),
                'title' => $task->title,
                'reason' => $request->input('reason'),
            ]);

            return $this->responseData(data: [
                'task' => new TaskDetailResource($updatedTask->load(['assignments.educator']))
            ]);
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function openBidding(Request $request, Task $task, OpenBaddingTaskAction $openBaddingTaskAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $openBaddingTaskAction->handle($task);
            $logActivity->forTask($task, LogProjectActivityAction::TASK_BIDDING_OPENED, [
                'title' => $task->title,
            ]);
            return $this->responseData(data: ['task' => new TaskDetailResource($task)]);
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function closeBidding(Request $request, Task $task, CloseBaddingTaskAction $openBaddingTaskAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $openBaddingTaskAction->handle($task);
            $logActivity->forTask($task, LogProjectActivityAction::TASK_BIDDING_CLOSED, [
                'title' => $task->title,
            ]);
            return $this->responseData(data: ['task' => new TaskDetailResource($task)]);
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function assignEducators(AssignmentRequest $request, Task $task, AssignTaskToEducatorAction $assignTaskToEducatorAction, LogProjectActivityAction $logActivity)
    {
        $assignmentData = $request->validated();

        $logActivity->trace('TaskController@assignEducators', [
            'task_id' => $task->id,
            'educators_requested' => $assignmentData['educators'] ?? [],
        ]);

        try {
            foreach ($assignmentData['educators'] as $educatorUuid) {
                $educator = Educator::where('uuid', $educatorUuid)->first();

                if (! $educator) {
                    // Silently skipped before - this is why a 200 could hide a no-op.
                    $logActivity->trace('educator NOT FOUND (skipped silently)', [
                        'step' => 'assignEducators',
                        'uuid' => $educatorUuid,
                        'task_id' => $task->id,
                    ]);
                    continue;
                }

                $assignTaskToEducatorAction->handle($task, $educator, $assignmentData);
                $logActivity->forTask($task, LogProjectActivityAction::EDUCATOR_ASSIGNED, [
                    'educator' => $educator->name,
                    'title' => $task->title,
                ]);
            }

            return $this->responseData(data: [
                'task' => new TaskDetailResource($task->load(['assignments.educator'])),
            ]);
        } catch (\Exception $e) {
            $logActivity->trace('assignEducators FAILED', ['task_id' => $task->id], $e);
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function cancelAssignment(Request $request, Assignment $assignment, CancelAssignmentAction $cancelAssignmentAction, LogProjectActivityAction $logActivity)
    {
        try {
            // the reason is optional, so it arrives as null when the admin leaves
            // the field empty; reading it straight off the request used to pass
            // null into a string-typed parameter and 500 the whole request
            $reason = $request->input('reason') ?? '';
            $task = $assignment->task;
            $educatorName = $assignment->educator?->name;
            $logActivity->forTask($task, LogProjectActivityAction::ASSIGNMENT_CANCELLED, [
                'educator' => $educatorName,
                'title' => $task?->title,
                'reason' => $reason ?: null,
            ]);
            $assignment = $cancelAssignmentAction->handle($reason, $assignment->id);

            return $this->responseData(data: [
                'assignment' => new AssignmentResource($assignment->load('educator')),
            ]);
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }


    public function fetchSubTask(Task $task, FetchSubTaskAction $fetchSubTaskAction): JsonResponse
    {
        $subTasks = $fetchSubTaskAction->handle($task);

        return $this->responseData(data: [
            'subTasks' => SubTaskResource::collection($subTasks),
        ]);
    }

    public function uploadAttachment(UploadAttachmentRequest $request, Task $task, StoreAttachmentAction $storeAttachmentAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $userId = auth()->id();

            if(get_class(auth()->user()) != 'Domain\Admin\Models\Admin') {

                $isAssigned = $task->assignments()->where('assigned_to', $userId)->where('status', AssignmentStatus::ACCEPTED)->exists();

                if (!$isAssigned) {
                    return $this->responseError([], 403, 'You are not authorized to add notes to this task.');
                }
            }

            $task = $storeAttachmentAction->handle($request, $task, "TASK_ATTACHMENT", null, auth()->user());
            $logActivity->forTask($task, LogProjectActivityAction::TASK_ATTACHMENT_ADDED, [
                'title' => $task->title,
                'file' => $request->input('name') ?? ($request->file('file')?->getClientOriginalName()),
            ]);
            return $this->responseData(
                data: ['task' => new SubTaskResource($task)],
                msg: "Attachment uploaded successfully"
            );

        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function storeTaskNote(StoreTaskNoteRequest $request, Task $task, CreateTaskNoteAction $action, LogProjectActivityAction $logActivity): JsonResponse
    {
        $userId = auth()->id();

        if(get_class(auth()->user()) !== 'Domain\Admin\Models\Admin') {

            $isAssigned = $task->assignments()->where('assigned_to', $userId)->where('status', AssignmentStatus::ACCEPTED)->exists();

            if (!$isAssigned) {
                return $this->responseError([], 403, 'You are not authorized to add notes to this task.');
            }
        }

        $note = $action->execute($task, $request->validated(), $userId);

        $logActivity->forTask($task, LogProjectActivityAction::TASK_NOTE_ADDED, [
            'title' => $task->title,
        ]);

        return $this->responseData([
            'note' => TaskNoteResource::make($note)
        ], 201, 'Note added successfully');
    }

    public function updateTaskNote(StoreTaskNoteRequest $request, TaskNote $taskNote, UpdateTaskNoteAction $action): JsonResponse
    {
        $userId = auth()->id();

        if(get_class(auth()->user()) != 'Domain\Admin\Models\Admin') {

            $isAssigned = $taskNote->assignments()->where('assigned_to', $userId)->where('status', AssignmentStatus::ACCEPTED)->exists();

            if (!$isAssigned) {
                return $this->responseError([], 403, 'You are not authorized to add notes to this task.');
            }
        }

        $note = $action->execute($taskNote, $request->validated());

        return $this->responseData([
            'note' => TaskNoteResource::make($note)
        ], 201, 'Note Updated successfully');
    }

    // public function storeDeliverable(StoreTaskDeliverableRequest $request, Task $task): JsonResponse
    // {
    //     $educatorId = auth()->id();
    //     $isAssigned = $task->assignments()->where('assigned_to', $educatorId)->where('status', AssignmentStatus::ACCEPTED)->exists();

    //     if (!$isAssigned) {
    //         return $this->responseError([], 403, 'You are not authorized to add deliverables to this task.');
    //     }

    //     if ($request->hasFile('file') && $request->file('file')->isValid()) {
    //         $file = $request->file('file');
    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
    //         $path = $file->storeAs('task_deliverables/' . $task->id, $fileName, 'public');

    //         $taskFile = $task->files()->create([
    //             'title' => $request->input('title', $file->getClientOriginalName()),
    //             'file_name' => $fileName,
    //             'original_name' => $file->getClientOriginalName(),
    //             'mime_type' => $file->getMimeType(),
    //             'collection_name' => 'task_deliverables',
    //             'type' => 'deliverable',
    //             'storage' => 'public',
    //             'path' => $path,
    //             'size' => $file->getSize(),
    //             'uploader_type' => get_class(auth()->user()),
    //             'uploader_id' => auth()->id(),
    //         ]);

    //         return $this->responseData([
    //             'deliverable' => TaskFileResource::make($taskFile)
    //         ], 201, 'Deliverable uploaded successfully');
    //     }

    //     return $this->responseError([], 400, 'File upload failed.');
    // }

    // public function destroyDeliverable(Task $task, string $deliverableId): JsonResponse
    // {
    //     $educatorId = auth()->id();
    //     $isAssigned = $task->assignments()->where('assigned_to', $educatorId)->where('status', AssignmentStatus::ACCEPTED)->exists();

    //     if (!$isAssigned) {
    //         return $this->responseError([], 403, 'You are not authorized to delete deliverables from this task.');
    //     }

    //     $file = $task->files()->where('uuid', $deliverableId)->where('collection_name', 'task_deliverables')->firstOrFail();

    //     Storage::disk($file->storage)->delete($file->path);
    //     $file->delete();

    //     return $this->responseData([], 200, 'Deliverable deleted successfully');
    // }
}
