<?php

namespace Domain\Project\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowHistory extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'workflow_history';

    public $timestamps = false;

    protected $fillable = [
        'workflow_id',
        'event_type',
        'message',
        'actor_id',
        'actor_type',
        'properties',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function actor()
    {
        return $this->morphTo();
    }
}
