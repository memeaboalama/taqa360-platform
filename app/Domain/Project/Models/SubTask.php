<?php

namespace Domain\Project\Models;

use Domain\Educator\Models\Educator;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubTask extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'task_id',
        'title',
        'completed',
        'educator_id',
        'modified_at',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'modified_at' => 'datetime:Y-m-d H:i:s',
        'completed_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function attachments()
    {
        return $this->morphMany(TaskFile::class, 'fileable');
    }

    public function creatable()
    {
        return $this->morphTo();
    }
}
