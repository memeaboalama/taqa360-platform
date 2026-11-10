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
        $filters = $request->validate([
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'min_budget' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'max_budget' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $minimum = $request->input('min_budget');
                    if ($value !== null && $minimum !== null && (float) $value < (float) $minimum) {
                        $fail('The max budget must be greater than or equal to the min budget.');
                    }
                },
            ],
            'date_filter' => ['sometimes', 'nullable', 'in:all,last_7_days'],
            'sort_by' => ['sometimes', 'nullable', 'in:created_at,oldest,budget_asc,budget_desc'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $projects = Project::published()->whereHas('tasks', function ($query) {
                $query->where('is_public', true)
                    ->where('bidding_status', 'OPEN');
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            })
            ->when(isset($filters['min_budget']), fn ($query) => $query->where('budget', '>=', $filters['min_budget']))
            ->when(isset($filters['max_budget']), fn ($query) => $query->where('budget', '<=', $filters['max_budget']))
            ->when(($filters['date_filter'] ?? 'all') === 'last_7_days', fn ($query) => $query->where('created_at', '>=', now()->subDays(7)))
            ->when($request->has('tags'), function ($query) use ($request) {
                $query->whereJsonContains('tags', $request->tags);
            })
            ->when(($filters['sort_by'] ?? 'created_at') === 'oldest', fn ($query) => $query->oldest())
            ->when(($filters['sort_by'] ?? 'created_at') === 'budget_asc', fn ($query) => $query->orderByRaw('CASE WHEN budget IS NULL THEN 1 ELSE 0 END')->orderBy('budget')->latest('created_at'))
            ->when(($filters['sort_by'] ?? 'created_at') === 'budget_desc', fn ($query) => $query->orderByRaw('CASE WHEN budget IS NULL THEN 1 ELSE 0 END')->orderByDesc('budget')->latest('created_at'))
            ->when(($filters['sort_by'] ?? 'created_at') === 'created_at', fn ($query) => $query->latest())
            ->with(['tasks' => function ($query) {
                $query->where('is_public', true)
                    ->where('bidding_status', 'OPEN');
            }])
            ->paginate($filters['per_page'] ?? 15);

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
