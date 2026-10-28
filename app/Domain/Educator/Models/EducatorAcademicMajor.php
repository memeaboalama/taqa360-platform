<?php

namespace Domain\Educator\Models;

use App\Application\Educator\Enums\AcademicDegreeEnum;
// use App\Support\Enum\Edu\EducationLevelEnum;
use App\Support\Traits\Models\HasUuid;
use Domain\AppBase\Models\Edu\AcademicMajor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducatorAcademicMajor extends Model
{
    use SoftDeletes, HasUuid;

    protected $table = 'educator_academic_majors';

    protected $fillable = [
        'educator_id',
        'academic_major_id',
        'custom_name',
        'is_custom',
        'graduation_institution',
        'graduation_year',
        'degree',
        'approval_document',
        'rejection_reason'
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'approval_document' => 'boolean',
        'degree' => AcademicDegreeEnum::class,
    ];

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }

    public function approvedMajor(): BelongsTo
    {
        return $this->belongsTo(AcademicMajor::class, 'academic_major_id');
    }

    public function certificateFile(): MorphOne
    {
        return $this->morphOne(EducatorFile::class, 'fileable');
    }
}
