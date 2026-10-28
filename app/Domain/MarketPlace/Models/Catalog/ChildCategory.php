<?php

namespace Domain\MarketPlace\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class ChildCategory extends Model
{
    use HasFactory, HasTranslations;
    public $translatable = ['title', 'description'];

    protected $fillable = ['subcategory_id', 'title', 'slug', 'description', 'image', 'is_active'];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function items()
    {
        return $this->hasMany(\Domain\MarketPlace\Models\Item::class, 'child_category_id');
    }

    public function getImageAttribute($value)
    {
        return $value ? Storage::disk('fileDisk')->url($value) : null;
    }
}
