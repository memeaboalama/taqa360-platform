<?php

namespace Domain\Educator\Models;

use Domain\AppBase\Models\Edu\Service as EduService;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducatorService extends Model
{
    use SoftDeletes, HasUuid;

    protected $table = 'educator_services';

    protected $fillable = [
        'educator_id',
        'service_id',
        'custom_name',
        'custom_description',
        'is_custom',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }

    public function approvedService(): BelongsTo
    {
        return $this->belongsTo(EduService::class, 'service_id');
    }
}
