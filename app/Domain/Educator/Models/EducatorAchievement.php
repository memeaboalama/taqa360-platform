<?php

namespace Domain\Educator\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EducatorAchievement extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $fillable = [
        'educator_id',
        'title',
        'type',
        'description',
        'organization',
        'issue_date',
        'start_date',
        'end_date',
        'approval_document'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'issue_date' => 'date',
        'approval_document' => 'boolean',
    ];

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(EducatorFile::class, 'fileable');
    }
}
