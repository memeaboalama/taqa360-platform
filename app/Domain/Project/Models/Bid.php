<?php

namespace Domain\Project\Models;

use App\Application\Project\Enums\BidStatus;
use App\Domain\AppBase\Models\Edu\Link;
use Domain\Project\Traits\HasUuid;
use Domain\Educator\Models\Educator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Bid extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'project_id',
        'educator_id',
        'total_amount',
        'proposal',
        'delivery_days',
        'links',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_days' => 'integer',
        'links' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function educator()
    {
        return $this->belongsTo(Educator::class);
    }

    public function bidTasks()
    {
        return $this->hasMany(BidTask::class);
    }

    public function attachments()
    {
        return $this->morphMany(ProjectFile::class, 'fileable');
    }

    public function links()
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    public static function hasEducatorBidForTask(Educator $educator, string $taskUuid): bool
    {
        return static::whereHas('bidTasks.task', function ($query) use ($taskUuid) {
            $query->where('uuid', $taskUuid);
        })
        ->where('educator_id', $educator->id)
        ->exists();
    }

    public static function hasEducatorBidForProject(Educator $educator, string $projectId): bool
    {
        return static::whereHas('project', function ($query) use ($projectId) {
            $query->where('id', $projectId);
        })
        ->where('educator_id', $educator->id)
        ->exists();
    }

    public static function getEducatorBidForTask(Educator $educator, string $taskUuid): ?Bid
    {
        return static::whereHas('bidTasks.task', function ($query) use ($taskUuid) {
            $query->where('uuid', $taskUuid);
        })
        ->where('educator_id', $educator->id)
        ->first();
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->bidTasks()->sum('amount');
    }

    public function getApprovedTasksAttribute()
    {
        return $this->bidTasks()->approved()->with('task')->get();
    }

    public function getRejectedTasksAttribute()
    {
        return $this->bidTasks()->rejected()->with('task')->get();
    }

    public function getPendingTasksAttribute()
    {
        return $this->bidTasks()->pending()->with('task')->get();
    }

    public function hasApprovedTasks(): bool
    {
        return $this->bidTasks()->approved()->exists();
    }

    public function hasRejectedTasks(): bool
    {
        return $this->bidTasks()->rejected()->exists();
    }

    public function hasPendingTasks(): bool
    {
        return $this->bidTasks()->pending()->exists();
    }

    /**
     * A bid stays editable only while none of its tasks has left the PENDING
     * state. Once a task is approved/assigned/rejected the offer is locked and
     * must go through the admin revert-to-pending flow instead.
     */
    public function isUpdatable(): bool
    {
        return ! $this->bidTasks()
            ->where('status', '!=', BidStatus::PENDING)
            ->exists();
    }

    public function approveTask(string $taskId, string $message = null): void
    {
        $bidTask = $this->bidTasks()->where('task_id', $taskId)->first();
        if ($bidTask) {
            $bidTask->approve($message);
        }
    }

    public function rejectTask(string $taskId, string $reason): void
    {
        $bidTask = $this->bidTasks()->where('task_id', $taskId)->first();
        if ($bidTask) {
            $bidTask->reject($reason);
        }
    }
}
