<?php

namespace App\Domain\Project\Repositories;

use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\Project\Models\Bid;
use Domain\Project\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class BidRepository extends EloquentMainRepository
{
    public function __construct(Bid $model)
    {
        parent::__construct($model);
    }

    public function getEducatorBids(int $educatorId, array $filters = []): LengthAwarePaginator|Collection
    {
        $query = $this->modal->where('educator_id', $educatorId)
            ->with(['project', 'bidTasks.task', 'attachments']);

        $this->applyFilters($query, $filters);
        $this->applySorting($query, Arr::get($filters, 'sorting', []));

        $perPage = Arr::get($filters, 'per_page', 15);
        $paginated = Arr::get($filters, 'paginated', true);

        return $paginated ? $query->paginate($perPage) : $query->get();
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['status'])) {
            $query->whereHas('bidTasks', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        if (!empty($filters['project_id'])) {
            if (is_string($filters['project_id']) && strlen($filters['project_id']) === 36) {
                $query->whereHas('project', function ($q) use ($filters) {
                    $q->where('uuid', $filters['project_id']);
                });
            } else {
                $query->where('project_id', $filters['project_id']);
            }
        }

        if (!empty($filters['search'])) {
            $query->where('proposal', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }
    }

    private function applySorting($query, array $sorting): void
    {
        foreach ($sorting as $sort) {
            $field = $sort['field'] ?? 'created_at';
            $direction = isset($sort['direction']) && in_array(strtolower($sort['direction']), ['asc', 'desc'])
                ? $sort['direction']
                : 'desc';

            switch ($field) {
                case 'total_amount':
                    $query->orderByRaw('(SELECT SUM(amount) FROM bid_tasks WHERE bid_id = bids.id) ' . $direction);
                    break;

                case 'project_name':
                    $query->join('projects', 'bids.project_id', '=', 'projects.id')
                        ->orderBy('projects.name', $direction)
                        ->select('bids.*');
                    break;

                default:
                    $query->orderBy($field, $direction);
                    break;
            }
        }
    }

    public function findByProjectAndEducator(int $projectId, int $educatorId): ?Bid
    {
        return $this->findByCols([
            'project_id' => $projectId,
            'educator_id' => $educatorId
        ], ['project', 'bidTasks.task', 'files']);
    }

    public function storeBid(array $data): Bid
    {
        $bid = $this->modal->create([
            'project_id' => $data['project_id'],
            'educator_id' => $data['educator_id'],
            'proposal' => Arr::get($data, 'proposal'),
            'delivery_days' => $data['delivery_days'],
            'links' => Arr::get($data, 'links', []),
        ]);

        if (isset($data['bid_tasks'])) {
            $this->storeBidTasks($bid, $data['bid_tasks']);
        }

        return $bid->load(['project', 'bidTasks.task', 'files']);
    }

    public function updateBid(Bid $bid, array $data): Bid
    {
        $updateData = array_filter([
            'proposal' => Arr::get($data, 'proposal'),
            'delivery_days' => Arr::get($data, 'delivery_days'),
            'links' => Arr::get($data, 'links'),
        ], fn($value) => !is_null($value));

        if (!empty($updateData)) {
            $bid->update($updateData);
        }

        if (isset($data['bid_tasks'])) {
            $bid->bidTasks()->delete();
            $this->storeBidTasks($bid, $data['bid_tasks']);
        }

        return $bid->fresh()->load(['project', 'bidTasks.task', 'files']);
    }

    public function storeBidTasks(Bid $bid, array $bidTasks): void
    {
        foreach ($bidTasks as $taskData) {
            $this->storeBidTask($bid, $taskData);
        }
    }

    public function storeBidTask(Bid $bid, array $data): void
    {
        $task = Task::where('uuid', $data['task_id'])->first();

        if (!$task) {
            throw new \InvalidArgumentException("Task not found with UUID: {$data['task_id']}");
        }

        $bid->bidTasks()->create([
            'task_id' => $task->id,
            'amount' => $data['amount'],
            'status' => $data['status'] ?? 'pending',
        ]);
    }

    public function storeAttachment(Bid $bid, array $attachment): Bid {
        $bid->attachments()->create($attachment);
        return $bid;
    }

    public function getBidStats(int $educatorId, array $filters = []): array
    {
        $query = $this->modal->where('educator_id', $educatorId);
        $this->applyFilters($query, $filters);

        return [
            'total_bids' => $query->count(),
            'total_amount' => $this->getTotalBidAmount($educatorId, $filters),
            'pending_bids' => $this->countBidsByStatus($educatorId, 'pending', $filters),
            'approved_bids' => $this->countBidsByStatus($educatorId, 'approved', $filters),
            'rejected_bids' => $this->countBidsByStatus($educatorId, 'rejected', $filters),
        ];
    }

    private function getTotalBidAmount(int $educatorId, array $filters): float
    {
        $query = $this->modal->where('educator_id', $educatorId);
        $this->applyFilters($query, $filters);

        return $query->withSum('bidTasks', 'amount')->get()->sum('bid_tasks_sum_amount');
    }

    private function countBidsByStatus(int $educatorId, string $status, array $filters): int
    {
        $query = $this->modal->where('educator_id', $educatorId)
            ->whereHas('bidTasks', function ($q) use ($status) {
                $q->where('status', $status);
            });

        $this->applyFilters($query, $filters);

        return $query->count();
    }

    public function restoreBid(Bid $bid): bool
    {
        return $bid->restore();
    }
}
