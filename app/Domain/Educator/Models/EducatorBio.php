<?php

namespace Domain\Educator\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EducatorBio extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'educator_id',
        'title',
        'date',
        'description',
        'organization',
    ];

    protected $casts = [
        'date' => 'date',
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
