<?php

namespace Domain\Project\Models;

use App\Application\Project\Enums\CreationType;
use App\Application\Project\Enums\ProjectStatus;
use Domain\Admin\Models\Admin;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Educator\Models\Educator;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'title',
        'description',
        'status',
        'tags',
        'budget',
        'is_published',
        'creation_type',
        'created_by',
        'deadline',
        'educational_request_id',
        'requester_name'
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'creation_type' => CreationType::class,
        'tags' => 'array',
        'budget' => 'decimal:2',
        'is_published' => 'boolean',
        'deadline' => 'date:Y-m-d',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function workflow()
    {
        return $this->morphOne(Workflow::class, 'workable');
    }

    public function educationalRequest()
    {
        return $this->belongsTo(EducationalRequest::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function attachments()
    {
        return $this->morphMany(ProjectFile::class, 'fileable');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function activityLog()
    {
        return $this->hasMany(ProjectActivityLog::class);
    }

    public function teamEducators()
    {
        return $this->belongsToMany(Educator::class, 'assignments', 'task_id', 'assigned_to')
            ->join('tasks','assignments.task_id','=','tasks.id')
            ->where('tasks.project_id',$this->id)
            ->where('assignments.status','accepted')
            ->distinct();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'IN_PROGRESS');
    }

    public function getProgressPercentageAttribute(): float
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->tasks()->where('status', 'COMPLETED')->count();
        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    public function getTotalAssignedCostAttribute(): float
    {
        return $this->tasks()->join('assignments', 'tasks.id', '=', 'assignments.task_id')
            ->where('assignments.status', 'ACCEPTED')
            ->sum('assignments.amount') ?? 0;
    }
}
