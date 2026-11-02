<?php

namespace App\Domain\AppBase\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CustomerTestimonial extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'position',
        'content',
        'image',
        'is_active',
        'sort_order',
    ];

    public array $translatable = [
        'position',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
