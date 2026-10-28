<?php

namespace Application\Project\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Project\Repositories\ProjectRepository;
use App\Application\Project\Requests\Project\UpdateAttachmentRequest;
use App\Application\Project\Requests\Task\UploadAttachmentRequest;
use App\Domain\Project\Actions\Project\DeleteAttachmentAction;
use App\Domain\Project\Actions\Project\StoreAttachmentAction;
use App\Domain\Project\Actions\Project\UpdateAttachmentAction;
use App\Domain\Project\Resources\Project\AttachmentResource;
use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectFile;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private ProjectRepository $projectRepository) {}

    public function fetch(Request $request, Project $project): JsonResponse
    {
        $attachments = $project->attachments;

        return $this->responseData(data: [
            'attachments' => AttachmentResource::collection($attachments),
        ]);
    }

    /**
     * Deliverables the specialist uploaded from educator/taskDetail.
     *
     * These are stored against the TASK (table task_files) while the project's
     * own attachments live in project_files, so $project->attachments above never
     * returned them and the admin had no way to see what was delivered.
     */
    public function deliverables(Request $request, Project $project): JsonResponse
    {
        $tasks = $project->tasks()
            ->with(['attachments' => fn ($q) => $q->where('collection_name', 'task_deliverables')->orderByDesc('uploaded_at')])
            ->orderByDesc('id')
            ->get();

        $total = 0;
        $deliverables = [];

        foreach ($tasks as $task) {
            $files = $task->attachments;
            $total += $files->count();

            $deliverables[] = [
                'task' => [
                    'id' => (string) $task->uuid,
                    'title' => $task->title,
                    'status' => $task->status instanceof \BackedEnum ? $task->status->value : $task->status,
                ],
                'count' => $files->count(),
                'files' => \App\Domain\Educator\Resource\Task\TaskFileResource::collection($files),
            ];
        }

        return $this->responseData(data: [
            'deliverables' => $deliverables,
            'counts' => [
                'tasks' => $tasks->count(),
                'files' => $total,
            ],
        ]);
    }


    public function store(UploadAttachmentRequest $request, Project $project, StoreAttachmentAction $storeAttachmentAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $attachment = $storeAttachmentAction->handle($request, $project);

            $logActivity->forProject($project, LogProjectActivityAction::PROJECT_ATTACHMENT_ADDED, [
                'title' => $project->title,
                'file' => $attachment->name ?? ($request->file('file')?->getClientOriginalName()),
            ]);

            return $this->responseData(
                data: ['attachment' => new AttachmentResource($attachment)],
                msg: "Attachment uploaded successfully"
            );

        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function update(UpdateAttachmentRequest $request, Project $project, ProjectFile $attachment, UpdateAttachmentAction $updateAttachmentAction): JsonResponse {
        try {
            $updatedAttachment = $updateAttachmentAction->handle($request, $project, $attachment);

            return $this->responseData(
                data: ['attachment' => new AttachmentResource($updatedAttachment)],
                msg: "Attachment updated successfully"
            );
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }

    public function destroy(Project $project, ProjectFile $attachment, DeleteAttachmentAction $deleteAttachmentAction): JsonResponse
    {
        try {
            $deleteAttachmentAction->handle($project, $attachment);

            return $this->responseData(msg: "Attachment deleted successfully");
        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }


}
