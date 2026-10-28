<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class MarketPlaceCategory extends Model
{
    use HasFactory, HasTranslations,HasStatus;

    public $translatable = ['title'];
    protected $fillable = ["title", "status", "sort", "image",'color'];


    public function merchants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Merchant::class,'market_place_category_merchants','market_category_id','merchant_id');
    }


    public function getImagePathAttribute()
    {
        return ($this->image) ? Storage::disk('fileDisk')->url($this?->image) : "";
    }
}
