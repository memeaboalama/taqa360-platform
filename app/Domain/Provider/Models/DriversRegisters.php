<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Models\HasOtp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Translatable\HasTranslations;

class DriversRegisters extends Authenticatable
{
    use HasFactory, HasTranslations, HasOtp;

    protected $translatable = [];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dialcode',
        'country_name',
        'region_id',
        'country_id',
        'city_id',
        'district_id',
        'country_code',
        'last_logged_in_at',
        'phone_verified_at',
        'email_verified_at',
        'network_status',
        'status',
        'IdentityTypeId',
        'Id_number',
        'Id_expire_date',
        'dateOfBirth',
        'car_type_id',
        'carNumber',
        'vehicleSequenceNumber',
        'iban',
    ];

    protected $appends = ['phone_number'];

    protected $casts = [
        'status' => \App\Support\Casts\SafeStatusEnum::class
    ];

    public function getPhoneNumberAttribute(): string
    {
        return $this->dialcode . ltrim($this->phone, 0);
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
    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(DriverRegisterFile::class, 'fileable');
    }

    public function filesCompleted()  : bool
    {
        $required = ['license_copy', 'car_copy', 'residence_permit_copy', 'iban_copy', 'car_registration_copy'];
        $existing = $this->files()->whereIn('collection_name', $required)->pluck('collection_name')->unique()->toArray();
        return count(array_intersect($required, $existing)) === count($required);
    }


}
