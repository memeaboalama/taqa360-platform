<?php

namespace App\Domain\Towing\Models;

use App\Support\Enum\StatusEnum;
use App\Support\Enum\TowingTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TowingType extends Model
{
    use HasFactory,HasTranslations;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'slug',
        'status',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class,
        'slug'      =>  TowingTypeEnum::class,
    ];
}
