<?php

namespace App\Domain\AppBase\Models\Setting;

use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasFactory,HasTranslations, HasUuid;

    protected $translatable = ['title'];

    protected $fillable = ['title','icon','code','status'];
}
