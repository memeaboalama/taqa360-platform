<?php

namespace App\Application\Educator\Controllers\Api;

use App\Application\Project\Enums\BidStatus;
use App\Application\Project\Enums\ProjectStatus;
use App\Domain\Educator\Resource\ProjectOpportunityListResource;
use App\Domain\Educator\Resource\ProjectOpportunityResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Project\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    use ApiResponseTraitV2;

    public function index(Request $request): JsonResponse
    {
        $projects = Project::published()->latest()->whereHas('tasks', function ($query) {
                $query->where('is_public', true)
                    ->where('bidding_status', 'OPEN');
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->has('tags'), function ($query) use ($request) {
                $query->whereJsonContains('tags', $request->tags);
            })
            ->with(['tasks' => function ($query) {
                $query->where('is_public', true)
                    ->where('bidding_status', 'OPEN');
            }])
            ->paginate($request->get('per_page', 15));

        return $this->responseData([
            'projects' => ProjectOpportunityListResource::collection($projects)
        ], 200, 'Opportunities retrieved successfully');
    }

    public function show(Project $project): JsonResponse
    {
        $opportunity = Project::with([
            'tasks' => function ($query) {
                $query->where('is_public', true);
            },
            'attachments',
            'bids.bidTasks.task',
            'bids.attachments',
            'bids.links',
        ])
            ->where('id',$project->id)->first();


        return $this->responseData([
            'project' => ProjectOpportunityResource::make($opportunity)
        ], 200, 'Opportunity retrieved successfully');
    }
}
