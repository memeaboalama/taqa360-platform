<?php

namespace Application\Project\Controllers\Api;

use App\Application\Project\Requests\Project\ChangeProjectStatusRequest;
use App\Application\Project\Requests\Project\StoreProjectRequest;
use App\Domain\Project\Actions\Project\CreateProjectAction;
use Domain\Project\Actions\Project\LogProjectActivityAction;
use App\Domain\Project\Actions\Project\DeleteProjectAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Project\Repositories\ProjectRepository;



use App\Application\Project\Requests\Project\FetchProjectRequest;
use App\Application\Project\Requests\Project\UpdateProjectRequest;
use App\Application\Project\Requests\Task\UploadAttachmentRequest;
use App\Domain\Project\Actions\Project\FetchProjectAction;
use App\Domain\Project\Actions\Project\ProjectChangeStatusAction;
use App\Domain\Project\Actions\Project\StoreAttachmentAction;
use App\Domain\Project\Actions\Project\UpdateProjectAction;
use App\Domain\Project\Resources\Bids\ProjectBidTaskResource;
use Domain\Project\Models\Project;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Project\Resources\Project\ProjectResource;
use Domain\Project\Resources\Project\ProjectListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProjectController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private ProjectRepository $projectRepository)
    {
        // $this->authorizeResource(Project::class, 'project');
    }

    public function fetch(FetchProjectRequest $request, FetchProjectAction $fetchProjectAction): JsonResponse
    {
        $projects = $fetchProjectAction->handle($request->validated());
        return $this->responseData(data: [
            'projects' => ProjectListResource::collection($projects),
        ]);
    }

    public function tabCounts(FetchProjectRequest $request): JsonResponse
    {
        return $this->responseData(data: [
            'counts' => $this->projectRepository->tabCounts($request->validated()),
        ]);
    }

    public function store(StoreProjectRequest $request, CreateProjectAction $action, LogProjectActivityAction $logActivity): JsonResponse
    {
        $project = $action->handle($request->validated());

        $logActivity->forProject($project, LogProjectActivityAction::PROJECT_CREATED, [
            'title' => $project->title,
        ]);

        return $this->responseData(data: [
            'project' => new ProjectListResource($project),
        ]);
    }

    public function show(Project $project, LogProjectActivityAction $logActivity): JsonResponse
    {
        $this->loadProjectDetails($project, includeAttachments: true, includeBids: true);

        // Read-side trace: separates "never written" from "written but not returned".
        $logActivity->trace('ProjectController@show', [
            'project_id' => $project->id,
            'rows_in_db' => $project->activityLog->count(),
            'resource_has_key' => array_key_exists(
                'activityLog',
                (new ProjectResource($project))->resolve(request())
            ),
        ]);

        return $this->responseData(data: [
            'project' => new ProjectResource($project)
        ]);
    }

    private function loadProjectDetails(
        Project $project,
        bool $includeAttachments = false,
        bool $includeBids = false
    ): Project {
        $relations = ['createdBy', 'tasks.assignments.educator', 'educationalRequest'];
        if (config('app.brand') === 'taqa360') {
            $relations[] = 'educationalRequest.service';
        }

        if ($includeAttachments) {
            $relations[] = 'attachments';
        }

        if ($includeBids) {
            $relations[] = 'bids';
        }

        $project->load($relations);

        if (Schema::hasTable('project_activity_logs')) {
            $project->load([
                'activityLog' => fn ($query) => $query->orderByDesc('timestamp')->orderByDesc('id'),
            ]);
        } else {
            $project->setRelation('activityLog', collect());
        }

        return $project;
    }


    public function update(UpdateProjectRequest $request, Project $project, UpdateProjectAction $updateProjectAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        $updatedProject = $updateProjectAction->handle($project, $request->validated());

        $logActivity->forProject($updatedProject, LogProjectActivityAction::PROJECT_UPDATED, [
            'title' => $updatedProject->title,
        ]);

        return $this->responseData(data: [
            'project' => new ProjectResource($this->loadProjectDetails($project))
        ]);
    }

    public function changeStatus(ChangeProjectStatusRequest $request, Project $project, ProjectChangeStatusAction $changeProjectStatusAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        $previous = $project->status instanceof \BackedEnum ? $project->status->value : $project->status;
        $updatedProject = $changeProjectStatusAction->handle($project, $request->validated());

        $logActivity->forProject($project, LogProjectActivityAction::PROJECT_STATUS_CHANGED, [
            'from' => (string) $previous,
            'to' => (string) ($updatedProject->status instanceof \BackedEnum ? $updatedProject->status->value : $updatedProject->status),
            'reason' => $request->input('reason'),
        ]);

        return $this->responseData(data: [
            'project' => new ProjectResource($this->loadProjectDetails($updatedProject))
        ]);
    }

    public function publish(Project $project, LogProjectActivityAction $logActivity)
    {
        try {
            $project->update(['is_published' => true]);
            $logActivity->forProject($project, LogProjectActivityAction::PROJECT_PUBLISHED, ['title' => $project->title]);
            return $this->responseData(
                data: ['project' => new ProjectResource($this->loadProjectDetails($project))],
                msg: 'Project publish successfully'
            );

        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to publish project", code: 500);
        }
    }

    public function unpublish(Project $project, LogProjectActivityAction $logActivity)
    {
        try {
            $project->update(['is_published' => false]);
            $logActivity->forProject($project, LogProjectActivityAction::PROJECT_UNPUBLISHED, ['title' => $project->title]);
            return $this->responseData(
                data: ['project' => new ProjectResource($this->loadProjectDetails($project))],
                msg: 'Project unpublish successfully'
            );

        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to unpublish project", code: 500);
        }
    }


    public function destroy(Project $project, DeleteProjectAction $deleteProjectAction): JsonResponse
    {
        try {
            $deleteProjectAction->handle($project->id);
            return $this->responseData(data: [],msg:"Project Deleted successfully" , code: 200);
        } catch (\Exception $e) {
            return $this->responseError(data: ['error' => $e->getMessage()], msg: "Failed to delete project", code: 500);
        }
    }

    public function uploadAttachment(UploadAttachmentRequest $request, Project $project, StoreAttachmentAction $storeAttachmentAction, LogProjectActivityAction $logActivity): JsonResponse
    {
        try {
            $project = $storeAttachmentAction->handle($request, $project);

            $logActivity->forProject($project, LogProjectActivityAction::PROJECT_ATTACHMENT_ADDED, [
                'title' => $project->title,
                'file' => $request->input('name') ?? ($request->file('file')?->getClientOriginalName()),
            ]);

            return $this->responseData(
                data: ['project' => new ProjectResource($project)],
                msg: "Attachment uploaded successfully"
            );

        } catch (\Exception $e) {
            return $this->responseError(msg: $e->getMessage(), code: 400);
        }
    }


    public function getBids(Request $request, Project $project)
    {
        $bidsTasks = $project?->bid->map(function($bid) {
            return $bid->bidTasks;
        });

        return $this->responseData(
            data: ['bidsTasks' => ProjectBidTaskResource::collection($bidsTasks)],
            msg: "Attachment uploaded successfully"
        );

    }
}
