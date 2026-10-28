<?php

namespace Domain\AppBase\Models\Edu;

use App\Support\Enum\StatusEnum;
use Domain\Educator\Models\Educator;
use Domain\Educator\Models\EducatorService;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use SoftDeletes, HasTranslations, HasUuid;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function educators()
    {
        return $this->belongsToMany(Educator::class, 'educator_services');
    }

    public function educatorServices()
    {
        return $this->hasMany(EducatorService::class);
    }
}
