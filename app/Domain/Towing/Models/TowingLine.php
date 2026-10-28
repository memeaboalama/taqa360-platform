<?php

namespace App\Domain\Towing\Models;

use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TowingLine extends Model
{
    use HasFactory,HasTranslations,HasStatus;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'from_towing_city_id',
        'to_towing_city_id',
        'towing_type_id',
        'price',
        'status',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class,
    ];

    public function from_city()
    {
        return $this->belongsTo(TowingCity::class,'from_towing_city_id','id');
    }

    public function to_city()
    {
        return $this->belongsTo(TowingCity::class,'to_towing_city_id','id');
    }
}
