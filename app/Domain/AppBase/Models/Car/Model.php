<?php

namespace App\Domain\AppBase\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Spatie\Translatable\HasTranslations;

class Model extends BaseModel
{
    use HasFactory,HasTranslations;
    protected array $translatable = ['title'];

    protected $fillable = [
        'title',
        'code',
        'brand_id',
    ];

    protected $casts = [];
}
