<?php

namespace Domain\Project\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskNote extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'creatable_type',
        'creatable_id',
        'task_id',
        'content',
        'content_type',
        'sendt_at',
        'modified_at'
    ];

    protected $casts = [
        'content_type' => 'string',
        'sent_at' => 'datetime:Y-m-d H:i:s',
        'modified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function creatable()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphMany(TaskFile::class, 'fileable');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
