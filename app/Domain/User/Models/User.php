<?php

namespace App\Domain\User\Models;


use App\Domain\AppBase\Models\WalletCouponsRecharges;
use App\Domain\AppBase\Models\WalletTransaction;
use App\Domain\Ecommerce\Models\WebCart;
use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use App\Support\Enum\ActiveEnum;
use App\Support\Enum\FileTypesEnum;
use App\Support\Traits\Image\HasGallery;
use App\Support\Traits\Models\HasFiles;
use App\Support\Traits\Models\HasNewFactory;
use App\Support\Traits\Models\HasOtp;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Database\Factories\UserFactory;
use Domain\MarketPlace\Models\MerchantUserBalance;
use Domain\Notification\Models\DeviceToken;
use Domain\User\Models\UserFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Faker\Generator;
use Bavix\Wallet\Traits\HasWallets;
use Domain\Project\Traits\HasUuid;

class User extends Authenticatable implements Wallet ,WalletFloat
{
    use HasApiTokens,
        HasNewFactory,
        Notifiable,
        SoftDeletes,
        HasOtp,
        HasGallery,
        HasWalletFloat,
        HasWallets,
        HasUuid;


    const COLLECTION = "profile";
    const DEFAULT = "";
    const PROFILE_PATH = 'public/users/profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dialcode',
        'country_name',
        'country_code',
        'gender',
        'nationality',
        'status',
        'city',
        'address',
        'email_verified_at',
        'phone_verified_at',
        'last_logged_in_at',
    ];

    protected $appends = ['phone_number'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_logged_in_at' => 'datetime',
        'status' => ActiveEnum::class,
    ];



    public function getPhoneNumberAttribute(): string
    {
        return $this->dialcode . ltrim($this->phone, 0);
    }

    public function getBalanceAttribute(): string
    {
        $wallet = $this->getWallet('UID_'.$this->id.'_Main');
        return (string) ($wallet ? $wallet->balanceFloatNum : 0);
    }

    // relations

    public function logins(): HasMany
    {
        return $this->hasMany(UserLogin::class);
    }

    public function orders() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrder::class,'user_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(UserCar::class);
    }

    public function searchHistory(): HasMany
    {
        return $this->hasMany(UserSearch::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(UserPhone::class);
    }

    public function cart():HasOne
    {
        return $this->hasOne(Cart::class,'user_id','id');
    }

    public function webCart():HasOne
    {
        return $this->hasOne(WebCart::class,'user_id','id');
    }

    public function favs(): HasMany
    {
        return $this->hasMany(Favorite::class,'user_id','id');
    }

    public function favorites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'favorites','user_id','merchant_id')
            ->withPivot('merchant_branch_id');
    }
    public function emails(): HasMany
    {
        return $this->hasMany(UserEmail::class);
    }

    public function userSetting(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }
    public function logOrderHistory(): MorphMany
    {
        return $this->morphMany(MerchantOrderHistory::class, 'changeable');
    }

    public function profileImage()
    {
        return $this->file()->where('type', FileTypesEnum::PROFILE);
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
            'gender',
            'nationality',
            'status',
            'country_code',
            'city',
            'address',
            'last_logged_in_at',
        ];
    }

    public function getDefaultAddressAttribute()
    {
        if($this->addresses()->count()==1){

            return $this->addresses()?->first();
        }
        return $this->addresses()->where('is_default','=',true)?->latest()?->first();
    }

    public function definition(Generator $faker)
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => $faker->phoneNumber(),
            'dialcode' => 966,
            'country_name' => $faker->country(),
            'country_code' => 'sa',
            'last_logged_in_at' => $faker->dateTimeBetween('-30 days','now'),
            'remember_token' => Str::random(10),
        ];
    }
    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(UserFile::class, 'fileable');
    }

    public function device_tokens()
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }

    public function walletTransaction() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletTransaction::class,'user_id');
    }
    public function walletCouponsRecharges() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletCouponsRecharges::class,'user_id');
    }
    public function merchantUserBalance() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantUserBalance::class,'user_id');
    }
}
