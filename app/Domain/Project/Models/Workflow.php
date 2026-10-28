<?php

namespace Domain\Project\Models;

use App\Application\Project\Enums\CreationType;
use App\Application\Project\Enums\WorkflowStatus;
use App\Application\Project\Enums\WorkflowType;
use Domain\Admin\Models\Admin;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Domain\EducationalRequest\Models\EducationalRequest;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Workflow extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'workable_type',
        'workable_id',
        'type',
        'academic_major_id',
        'requester_id',
        'creation_type',
        'status',
        'budget',
        'deadline',
        'created_by',
        'published_at',
        'completed_at',
    ];

    protected $casts = [
        'creation_type' => CreationType::class,
        'type' => WorkflowType::class,
        'status' => WorkflowStatus::class,
        'deadline' => 'datetime',
        'budget' => 'decimal:2',
        'published_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function academicMajor()
    {
        return $this->belongsTo(AcademicMajor::class, 'academic_major_id');
    }

    public function educationalRequest()
    {
        return $this->belongsTo(EducationalRequest::class, 'requester_id');
    }

    public function workable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function history()
    {
        return $this->hasMany(WorkflowHistory::class)->orderBy('created_at', 'desc');
    }

}
