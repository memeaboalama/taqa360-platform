<?php

namespace Domain\Educator\Models;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducatorSocialLink extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'educator_id',
        'platform',
        'url',
    ];

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }
}
