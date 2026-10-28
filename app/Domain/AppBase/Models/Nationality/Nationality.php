<?php

namespace App\Domain\AppBase\Models\Nationality;

use App\Support\Enum\StatusEnum;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Nationality extends BaseModel
{
    use HasFactory,HasTranslations, HasUuid;
    protected array $translatable = ['title'];

    protected $fillable = [
        'title',
        'status',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class
    ];

}
