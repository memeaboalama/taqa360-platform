<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    public $translatable = ['name', 'description'];

    protected $fillable = ['name', 'description', 'image', 'tags', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'tags' => 'array'
    ];

    public function getImageAttribute($value)
    {
        return $value ? Storage::disk('fileDisk')->url($value) : null;
    }
}
