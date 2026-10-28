<?php

namespace App\Domain\AppBase\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Brand extends BaseModel
{
    use HasFactory,HasTranslations;
    protected array $translatable = ['title'];

    protected $fillable = [
        'title',
        'code',

    ];

    protected $casts = [];

    public function models(): HasMany
    {
        return $this->hasMany(Model::Class);
    }
}
