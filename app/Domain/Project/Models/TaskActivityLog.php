<?php

namespace Domain\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Domain\Project\Traits\HasUuid;

class TaskActivityLog extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'task_id',
        'message_key',
        'message_payload',
        'actor_name',
        'timestamp',
    ];

    protected $casts = [
        'message_payload' => 'array',
        'timestamp' => 'datetime:Y-m-d H:i:s',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
