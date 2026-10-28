<?php

namespace Domain\MarketPlace\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Subcategory extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'sub_categories';
    public $translatable = ['title', 'description'];

    protected $fillable = ['category_id', 'title', 'slug', 'description', 'image', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class);
    }

    public function items()
    {
        return $this->hasMany(\Domain\MarketPlace\Models\Item::class, 'sub_category_id');
    }

    public function getImageAttribute($value)
    {
        return $value ? Storage::disk('fileDisk')->url($value) : null;
    }
}
