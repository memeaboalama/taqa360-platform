<?php

namespace App\Domain\AppBase\Models\Category;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class MainCategory extends BaseModel
{
    use HasFactory,HasTranslations;
    protected array $translatable = ['title','description'];

    protected $fillable = [
        'title',
        'description',
        'status',
        'type'

    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];


}
