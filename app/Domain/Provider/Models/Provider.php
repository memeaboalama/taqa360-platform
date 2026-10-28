<?php

namespace App\Domain\Provider\Models;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Support\Enum\NetworkStatusEnum;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Image\HasGallery;
use App\Support\Traits\Models\HasOtp;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Domain\MarketPlace\Models\MerchantFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use Spatie\Translatable\HasTranslations;

class Provider extends Authenticatable implements Wallet, WalletFloat
{
    use HasApiTokens, HasFactory, HasTranslations, HasWalletFloat, HasOtp;
    use HasGallery;

    protected $translatable = [];

    protected $fillable = [
        'company_id',
        'nationality_id',
        'country_id',
        'city_id',
        'name',
        'email',
        'phone',
        'provider_id_num',
        'dialcode',
        'country_name',
        'country_code',
        'last_logged_in_at',
        'phone_verified_at',
        'email_verified_at',
        'location',
        'network_status',
        'status',
        'password',
        'type',
    ];

    protected $casts = [
        'network_status' => NetworkStatusEnum::class,
        'status' => StatusEnum::class,
        'location' => Point::class,
    ];

    protected $hidden = [
        'password',
    ];


    public function getPhoneNumberAttribute(): string
    {
        return $this->country_code . ltrim($this->phone, 0);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }


    public function vehicles()
    {
        return $this->morphMany(Vehicle::class, 'vehicleable');
    }

    public function provider_orders(): HasMany
    {
        return $this->hasMany(ProviderOrder::class, 'provider_id', 'id');
    }


    public function order_actions(): HasMany
    {
        return $this->hasMany(OrderAction::class,'provider_id','id');
    }


    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }


    // Methods
    public function getAvailableData(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'dialcode',
            'country_name',
            'country_code',
            'last_logged_in_at',
            'company_id',
            'nationality_id',
            'location',
            'network_status',
            'status',
        ];
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(ProviderFile::class, 'fileable');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->country_id = Country::first()?->id;
        });
    }

}
