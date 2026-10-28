<?php


namespace Domain\SalesAgent\Models;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\MarketPlace\Models\MerchantOrder;
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

class SalesAgent extends Model
{
    use HasFactory;
    use HasApiTokens, HasFactory;
    use HasGallery,Notifiable, HasOtp;

    protected $fillable=[
        'name',
        'email',
        'phone',
        'password',
        'dialcode',
        'country_name',
        'country_id',
        'region_id',
        'city_id',
        'district_id',
        'country_code',
        'last_logged_in_at',
        'phone_verified_at',
        'email_verified_at',
        'network_status',
        'status',
        'Id_number',
        'Id_expire_date',
        'iban',
        'is_complete',
        'taken_commission',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
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

    public function filesCompleted()  : bool
    {
        $check = $this->files()->count();
        return $check == 5 ? true : false;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(SalesAgentFile::class, 'fileable');
    }

    // public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(MerchantOrder::class, 'salse_agent_id','id');
    // }

    public function bank_info(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SalesAgentBankInfo::class, 'salse_agent_id');
    }
}
