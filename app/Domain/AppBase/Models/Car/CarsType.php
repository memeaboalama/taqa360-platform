<?php

namespace App\Domain\AppBase\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class CarsType extends Model
{
    use HasFactory,HasTranslations;
    protected array $translatable = ['title'];

    protected $fillable = [
        'title',
        'key',
        'elm_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
