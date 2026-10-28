<?php

namespace Domain\Educator\Models;

use Domain\AppBase\Models\Edu\Skill;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducatorSkill extends Model
{
    use SoftDeletes, HasUuid;

    protected $table = 'educator_skills';

    protected $fillable = [
        'educator_id',
        'skill_id',
        'custom_name',
        'is_custom',
        'level'
    ];

    protected $casts = [
        'is_custom' => 'boolean',
    ];

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }

    public function approvedSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
