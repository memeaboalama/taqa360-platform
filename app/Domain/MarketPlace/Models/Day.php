<?php

namespace Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Day extends Model
{
    use HasFactory ,HasTranslations;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
    ];
}
