<?php

namespace Domain\Driver\Models;

use App\Domain\AppBase\Models\Car\CarsType;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use App\Domain\Provider\Models\Company;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Image\HasGallery;
use App\Support\Traits\Models\HasOtp;
use Bavix\Wallet\Traits\HasWalletFloat;
use Domain\MarketPlace\Models\MerchantFile;
use Domain\Notification\Models\DeviceToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use Spatie\Translatable\HasTranslations;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory;
    use HasGallery,Notifiable, HasOtp;

    protected $fillable=[
        'elm_id',
        'company_id',
        'name',
        'email',
        'phone',
        'password',
        'dialcode',
        'country_name',
        'country_id',
        'city_id',
        'district_id',
        'country_code',
        'last_logged_in_at',
        'phone_verified_at',
        'email_verified_at',
        'location',
        'network_status',
        'status',
        'rejection_reason',
        'elm_upload_status',
        'Id_number',
        'Id_expire_date',
        'iban',
        'is_complete',
        'taken_commission',
        'IdentityTypeId',
        'dateOfBirth',
        'region_id',
        'car_type_id',
        'carNumber',
        'vehicleSequenceNumber',
        'elm_errors'
    ];

    protected $casts = [
        'status' => \App\Support\Casts\SafeStatusEnum::class,
        'location' => Point::class,
        'elm_errors' => 'array'
    ];

    protected $hidden = [
        'password',
    ];

    public function getPhoneNumberAttribute(): string
    {
        return $this->dialcode . ltrim($this->phone, 0);
    }

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }
    public function device_tokens(): MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }


    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distract::class, 'district_id', 'id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function logOrderHistory(): MorphMany
    {
        return $this->morphMany(MerchantOrderHistory::class, 'changeable');
    }
    
    public function filesCompleted()  : bool
    {
        $required = ['license_copy', 'car_copy', 'residence_permit_copy', 'iban_copy', 'car_registration_copy'];
        $existing = $this->files()->whereIn('collection_name', $required)->pluck('collection_name')->unique()->toArray();
        return count(array_intersect($required, $existing)) === count($required);
    }

    public function driverActive(): bool
    {
        return (bool) $this->is_complete;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(DriverFile::class, 'fileable');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrder::class, 'driver_id','id');
    }

    public function bank_info(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DriverBankInfo::class, 'driver_id');
    }
    public function region(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function carType(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(CarsType::class, 'car_type_id');
    }
}
