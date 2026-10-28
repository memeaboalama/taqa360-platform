<?php

namespace App\Domain\AppBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Bank extends Model
{
    use HasFactory,HasTranslations;
    protected array $translatable = ['title'];
    protected $fillable=[
        'title',

    ];
}
