<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\HasOtp;
use Domain\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Translatable\HasTranslations;

class Company extends Authenticatable
{
    use HasFactory, HasTranslations, HasOtp;

    protected $translatable = [];

    protected $fillable = [
        'company_name',
        'name',
        'email',
        'type',
        'description',
        'phone',
        'dialcode',
        'country_name',
        'country_code',
        'last_logged_in_at',
        'phone_verified_at',
        'password',
        'email_verified_at',
        'country_id',
        'region_id',
        'city_id',
        'district_id',
        'status',
    ];

    protected $appends = ['phone_number'];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function getPhoneNumberAttribute(): string
    {
        return $this->country_code . ltrim($this->phone, 0);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distract::class, 'district_id', 'id');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CompanyEmployee::class, 'company_id', 'id');
    }

    public function vehicles()
    {
        return $this->morphMany(Vehicle::class, 'vehicleable');
    }

}
