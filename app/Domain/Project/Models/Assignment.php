<?php

namespace Domain\Project\Models;

use App\Application\Project\Enums\AssignmentStatus;
use App\Application\Project\Enums\AssignmentType;
use Domain\Project\Traits\HasUuid;
use Domain\Admin\Models\Admin;
use Domain\Educator\Models\Educator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Assignment extends Model
{
    use HasFactory, SoftDeletes, HasUuid;
    protected $fillable = [
        'task_id',
        'assigned_to',
        'assigned_by',
        'assigned_type',
        'bid_id',
        'status',
          'amount',
          'agreement_notes',
          'rejection_reason',
        'assigned_at',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => AssignmentStatus::class,
        'assigned_type' => AssignmentType::class,
        'amount' => 'decimal:2',
        'assigned_at' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function educator()
    {
        return $this->belongsTo(Educator::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(Admin::class, 'assigned_by');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function bid()
    {
        return $this->belongsTo(Bid::class);
    }
}
