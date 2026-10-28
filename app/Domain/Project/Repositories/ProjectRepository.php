<?php

namespace Domain\Project\Repositories;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\BidStatus;
use App\Application\Project\Enums\CreationType;
use App\Application\Project\Enums\TaskStatus;
use Domain\Project\Models\Project;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Project\Models\ProjectFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository extends EloquentMainRepository
{

    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function filter(array $filters = [], array $with = [], array $cols = [], array $conditions = []): Builder
    {
        $query = $this->modal
            ->with($with)
            ->select($cols)
            ->where($conditions)
            ->newQuery();
        return $this->applyFilters($query, $filters);
    }

    public function filterAndPaginate(array $filters, array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->modal->with($relations)
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($q) {
                $q->where('status', TaskStatus::COMPLETED);
            }]);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($perPage);
    }

    public function applyFilters($query, array $filters)
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['creation_type'])) {
            $query->where('creation_type', $filters['creation_type']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['is_published'])) {
            $query->where('is_published', $filters['is_published']);
        }

        if (isset($filters['has_open_bidding_tasks'])) {
            $query->whereHas('tasks', function ($q) {
                $q->where('is_public', true)->where('bidding_status', 'OPEN');
            });
        }

        if (isset($filters['tags'])) {
            $query->whereJsonContains('tags', $filters['tags']);
        }

        if (isset($filters['min_budget'])) {
            $query->where('budget', '>=', $filters['min_budget']);
        }

        if (isset($filters['max_budget'])) {
            $query->where('budget', '<=', $filters['max_budget']);
        }

        if (isset($filters['educator_id_assigned'])) {
            $educatorId = $filters['educator_id_assigned'];
            $status = $filters['assignment_status'] ?? null;

            $query->whereHas('tasks.assignments', function ($query) use ($educatorId, $status) {
                $query->where('assigned_to', $educatorId)
                    ->where('status', BidStatus::ASSIGNED)
                    ->when($status === 'active', function ($q) {
                        $q->whereHas('task', fn($taskQ) => $taskQ->where('status', '!=', 'COMPLETED'));
                    })
                    ->when($status === 'completed', function ($q) {
                        $q->whereHas('task', fn($taskQ) => $taskQ->where('status', 'COMPLETED'));
                    });
            });
        }

        return $query;
    }

    public function getProjectsWithOpenTasksCount(array $filters = [], array $relations = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->modal->newQuery();
        $this->applyFilters($query, $filters);

        return $query->withCount(['tasks as open_tasks_count' => function ($query) {
            $query->where('is_public', true)->where('bidding_status', 'OPEN');
        }])
            ->paginate($perPage);
    }

    public function getProjectPublicTasks(string $projectId): ?Project
    {
        return $this->modal->with(['tasks' => function ($query) {
            $query->where('is_public', true);
        }])->find($projectId);
    }

    public function getEducatorAssignedProjects(string $educatorId, string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->modal->whereHas('tasks.assignments', function ($query) use ($educatorId, $status) {
            $query->where('assigned_to', $educatorId)
                ->where('status', AssignmentStatus::ACCEPTED)
                ->when($status === 'active', function ($q) {
                    $q->whereHas('task', fn($taskQ) => $taskQ->where('status', '!=', AssignmentStatus::CANCELLED));
                })
                ->when($status === 'completed', function ($q) {
                    $q->whereHas('task', fn($taskQ) => $taskQ->where('status', AssignmentStatus::CANCELLED));
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
            ->paginate($perPage);
    }

    public function newProject(array $data)
    {
        $project = $this->modal->create($data);
        $this->initialTask($project);

        return $project;
    }

    public function initialTask($project)
    {
        $task = $project->tasks()->create([
            'title' => __('lang.project.initial_task_for', ['title' => $project->title]),
            'description' => $project->description ?? '',
            'tags' => $project->tags ?? null,
            'created_by' => $project->created_by,
            'is_public' => $project->creation_type == CreationType::PUBLIC ? true : false,
        ]);

        return $task;
    }

    public function storeAttachment(Project $project, array $data) {
        return $project->attachments()->create($data);
    }

    public function updateAttachment(ProjectFile $attachment, array $data): ProjectFile{
        $attachment->update($data);
        return $attachment->fresh();
    }

    public function deleteAttachment(ProjectFile $attachment): bool
    {
        return $attachment->delete();
    }


}
