<?php

namespace Domain\Educator\Models;

use App\Application\Educator\Enums\DocumentStatusEnum;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\AppBase\Models\Nationality\Nationality;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducatorProfile extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'educator_id',
        'identity_id',
        'identity_type',
        'identity_status',
        'rejection_reason',
        'gender',
        'birth_date',
        'nationality_id',
        'country_id',
        'region_id',
        'city_id',
        'education_level',
        'experience_years',
        'specialization',
        'employment_status',
        'completion_step',
        'is_complete',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'completion_step' => 'integer',
        'is_complete' => 'boolean',
        'identity_status' => DocumentStatusEnum::class,
    ];

    public function getExperienceLevelAttribute(): ?string
    {
        $years = $this->experience_years;
        if ($years === null) return null;
        if ($years <= 2) return 'BEGINNER';
        if ($years <= 7) return 'INTERMEDIATE';
        return 'EXPERT';
    }

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class, 'nationality_id', 'id');
    }

}
