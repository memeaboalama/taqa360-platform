<?php

namespace App\Application\Educator\Controllers\Api;

use App\Application\Project\Enums\AssignmentStatus;
use App\Domain\Educator\Resource\EducatorProjectListResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Project\ProjectResource;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Project\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponseTraitV2;

    public function index(Request $request): JsonResponse
    {
        $educatorId = auth()->id();
        $status = $request->query('status', 'active');

        $projects = Project::whereHas('tasks.assignments', function ($query) use ($educatorId, $status) {
            $query->where('assigned_to', $educatorId)
                ->where('status', AssignmentStatus::ACCEPTED)
                ->when($status === 'active', function ($q) {
                    $q->whereHas('task', fn($taskQ) => $taskQ->where('status', '!=', 'COMPLETED'));
                })
                ->when($status === 'completed', function ($q) {
                    $q->whereHas('task', fn($taskQ) => $taskQ->where('status', 'COMPLETED'));
                });
        })
            ->with(['tasks' => function ($query) use ($educatorId, $status) {
                $query->whereHas('assignments', function ($q) use ($educatorId) {
                    $q->where('assigned_to', $educatorId)
                        ->where('status', AssignmentStatus::ACCEPTED);
                })
                    ->when($status === 'active', function ($q) {
                        $q->where('status', '!=', 'COMPLETED');
                    })
                    ->when($status === 'completed', function ($q) {
                        $q->where('status', 'COMPLETED');
                    });
            }])
            ->paginate($request->get('per_page', 15));

        return $this->responseData([
            'myProjects' => EducatorProjectListResource::collection($projects)
        ], 200, 'My projects retrieved successfully');
    }


    public function myProjects(Request $request)
    {
        $educator = $request->user();
        $per = (int) $request->get('per_page', 15);
        $page = (int) $request->get('page', 1);

        $query = Project::whereHas('tasks.assignments', function ($q) use ($educator) {
            $q->where('assigned_to', $educator->id);
        })->with(['tasks' => function($q) use ($educator){ $q->with(['assignments' => function($q2) use ($educator){ $q2->where('assigned_to',$educator->id); }]); }])->latest();

        $paginator = $query->paginate($per, ['*'], 'page', $page);

        $payload = ['projects' => $paginator->items()];

        $pagination = ['meta' => ['page' => ['current' => $paginator->currentPage(), 'per' => $paginator->perPage(), 'total' => $paginator->total()]], 'links' => ['path' => url('/api/educator/my-projects')]];

        return response()->json(['status' => true, 'message' => null, 'data' => $payload, 'pagination' => $pagination]);
    }
}
