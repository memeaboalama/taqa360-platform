<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Car\Model as CarModel;
use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\Towing\Models\TowingType;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'model_id',
        'vehicleable_type',
        'vehicleable_id',
        'plate_number',
        'is_towing',
        'towing_type_id',
        'year',
        'status',
    ];

    protected $casts = [
        'status'    =>  StatusEnum::class
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function towing_type(): BelongsTo
    {
        return $this->belongsTo(TowingType::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function vehicleable(): MorphTo
    {
        return $this->morphTo();
    }

    public function companyVehicle() : HasOne
    {
        return $this->hasOne(CompanyProviderVehicle::class, 'vehicle_id');
    }

}
