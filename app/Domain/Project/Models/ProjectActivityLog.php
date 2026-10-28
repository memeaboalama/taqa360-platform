<?php

namespace Domain\Project\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivityLog extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'project_id',
        'message_key',
        'message_payload',
        'actor',
        'timestamp',
    ];

    protected $casts = [
        'message_payload' => 'array',
        'timestamp' => 'datetime:Y-m-d H:i:s',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
