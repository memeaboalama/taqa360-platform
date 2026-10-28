<?php

namespace Domain\Project\Models;

use App\Application\Project\Enums\ProjectStatus;
use Domain\Project\Traits\HasUuid;
use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'budget',
        'deadline',
        'is_public',
        'bidding_status',
        'tags',
        'created_by',
        'modified_at',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'budget' => 'decimal:2',
        'is_public' => 'boolean',
        'deadline' => 'date:Y-m-d',
        'tags' => 'array',
        'modified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'creation_date'
    ];


    public function workflow()
    {
        return $this->morphOne(Workflow::class, 'workable');
    }

    public function getCreationDateAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function attachments()
    {
        return $this->morphMany(TaskFile::class, 'fileable');
    }

    public function deliverables()
    {
        return null;
        // return $this->morphMany(Attachment::class, 'attachable')->where('type', 'deliverable');
    }

    public function notes()
    {
        return $this->hasMany(TaskNote::class);
    }

    public function activityLog()
    {
        return $this->hasMany(TaskActivityLog::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function bidTasks()
    {
        return $this->hasMany(BidTask::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_public', true);
    }

    public function getEducatorBidTask(Educator $educator = null)
    {
        if (!$educator) {
            return null;
        }

        return $this->bidTasks()
            ->whereHas('bid', function ($query) use ($educator) {
                $query->where('educator_id', $educator->id);
            })
            ->with(['bid'])
            ->first();
    }

    public function acceptedAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->where('status', 'accepted');
    }

    /**
     * الحصول على جميع التعيينات المقبولة
     */
    public function acceptedAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->where('status', 'accepted');
    }

    /**
     * scope للمهام النشطة
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'IN_PROGRESS');
    }

    /**
     * scope للمهام المكتملة
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'COMPLETED');
    }

    /**
     * scope للمهام المتأخرة
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->active()->where('deadline', '<', now()->toDateString());
    }

    /**
     * scope للمهام التي لديها تعيينات مقبولة
     */
    public function scopeHasAcceptedAssignments(Builder $query): Builder
    {
        return $query->whereHas('assignments', function ($q) {
            $q->where('status', 'accepted');
        });
    }


    public function scopeAssignedToEducator(Builder $query, int $educatorId): Builder
    {
        return $query->whereHas('assignments', function ($q) use ($educatorId) {
            $q->where('assigned_to', $educatorId)
            ->where('status', 'accepted');
        });
    }
}
