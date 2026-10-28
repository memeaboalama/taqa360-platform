<?php

namespace Domain\MarketPlace\Models;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\AppBase\Models\PromocodeMerchantsChoice;
use App\Domain\AppBase\Repositories\DaysRepository;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\Models\MerchantIban;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\Package;
use App\Domain\User\Models\User;
use App\Support\Enum\StatusEnum;
use App\Support\Services\Auth\Guard;
use App\Support\Traits\Image\HasGallery;
use App\Support\Traits\Models\HasOtp;
use App\Support\Traits\Models\HasStatus;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Domain\MarketPlace\Models\Catalog\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Translatable\HasTranslations;
use tiagomichaelsousa\LaravelFilters\Traits\Filterable;

class Merchant extends Authenticatable implements Wallet, WalletFloat
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        HasOtp,
        HasWalletFloat,
        HasStatus,
        HasGallery,
        Filterable,
        HasTranslations;

    const COLLECTION = "logo";
    const DEFAULT = "";


    public array $translatable = ['company_name', 'description'];
    protected $fillable = [
        'company_name',
        'user_name',
        'description',
        'name',
        'email',
        'phone',
        'dialcode',
        'country_name',
        'country_code',
        'status',
        'last_logged_in_at',
        'phone_verified_at',
        'email_verified_at',
        'country_id',
        'region_id',
        'city_id',
        'distract_id',
        'password',
        'main_categories',
        'tax_number',

    ];

    protected $appends = ['phone_number', 'merchant_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_logged_in_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'commissionPercentage' => 'float',
        'packages' => Package::class,
    ];

    public function getPhoneNumberAttribute(): string
    {
        return !is_null($this->phone) ? $this->dialcode . ltrim($this->phone, 0) : '';
    }

    // relations

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Distract::class, 'distract_id', 'id');
    }

    public function merchantCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantCategory::class, 'merchant_id', 'id');
    }
    public function promocodeMerchantsChoice(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PromocodeMerchantsChoice::class, 'merchant_id', 'id');
    }

    public function branches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantBranch::class, 'merchant_id', 'id');
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantEmployee::class, 'merchant_id', 'id');
    }

    public function activeBranch(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MerchantBranch::class, 'merchant_id', 'id')->where('status', 1);
    }

    public function inactiveBranch(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MerchantBranch::class, 'merchant_id', 'id')->where('status', 0);
    }

    public function checkBranchesAvailability(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        $daysRepository = new DaysRepository(new Day());
        $dayID = $daysRepository->findByCols(['name->en' => date('l')])?->id;
        $currentTime = date('His.u');
        $inActiveBranches = $this->hasMany(MerchantBranch::class, 'merchant_id', 'id')
            ->where('status', 0)
            ->get();

        foreach ($inActiveBranches as $inActiveBranch) {
            $workingDay = $inActiveBranch->workDays()->where('day_id', $dayID)->first();

            if ($workingDay) {
                if ($workingDay->status != StatusEnum::INACTIVE->value) {
                    if ($workingDay->workHours->count() > 0) {
                        $maxTo = $workingDay->workHours()->where('merchant_branch_id', $inActiveBranch->id)?->max('to');
                        foreach ($workingDay->workHours()->where('merchant_branch_id', $inActiveBranch->id)->get() as $period) {
                            if ($period->status == StatusEnum::ACTIVE->value) {
                                $from = date_create_from_format('H:i:s', $period->from)->format('His.u');
                                $to = date_create_from_format('H:i:s', $period->to)->format('His.u');
                                if ($currentTime >= $from && $currentTime <= $to) {
                                    $inActiveBranch->update([
                                        'status' => 1,
                                        'to' => isset($period->to) ? date_create_from_format('H:i:s', $period->to)->format('g:i a') : ""
                                    ]);
                                    break;
                                } else if ($currentTime < $from && $currentTime < $to) {
                                    $inActiveBranch->update([
                                        'status' => 0,
                                        'to' => isset($period->from) ? date_create_from_format('H:i:s', $period->from)->format('g:i a') : ""
                                    ]);
                                    // break;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $this->hasMany(MerchantBranch::class, 'merchant_id', 'id')
            ->where('status', 0);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Item::class, 'merchant_id', 'id');
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MarketPlaceCategory::class, 'market_place_category_merchants', 'merchant_id', 'market_category_id');
    }

    public function productCategories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'items', 'merchant_id', 'main_category_id')->distinct();
    }

    public function packages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'merchant_packages', 'merchant_id', 'package_id');
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantService::class, 'merchant_id', 'id');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrder::class, 'merchant_id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(MerchantFile::class, 'fileable');
    }

    public function iban(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantIban::class, 'merchant_id', 'id');
    }

    public function favs(): HasMany
    {
        return $this->hasMany(Favorite::class, 'merchant_id', 'id');
    }

    public function favorites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'merchant_id', 'user_id')
            ->withPivot('merchant_branch_id');
    }
    public function merchantActive(): bool
    {
        $status = $this->status;
        return $status == "active" ? true : false;
    }

    public function merchantCompleteFiles(): bool
    {
        $check = $this->files()->count();
        return $check > 7 ? true : false;
    }

    protected function commissionPercentage(): Attribute
    {
        return new Attribute(
            get: fn() => $this->packages()->first()?->commission ?? 0
        );
    }


    protected function rate(): Attribute
    {
        $orders = $this->orders()->where('rate', "!=", 0);
        if ($orders->exists()) {
            $total = round($orders->avg('rate'), 2);
        } else {
            $total = 1;
        }

        return new Attribute(
            get: fn() => $total
        );
    }


    protected function avgPrice(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->items()->avg('default_price'), 2)
        );
    }

    protected function avgPreparingTime(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->items()->avg('preparing_time'), 2)
        );
    }

    public function getMerchantIdAttribute()
    {
        return $this->id;
    }
    public function getLogoAttribute()
    {
        $file = $this
            ->files()
            ->where(["collection_name" => 'app_logo', 'fileable_id' => $this->id])
            ?->first();

        // MerchantFile::public_url prefers the local fileDisk copy and falls
        // back to the url stored at upload time.
        return $file?->public_url ?: "";
    }

    public function getAppLogoPathAttribute()
    {
        $file = $this
            ->files()
            ->where(["collection_name" => 'app_logo', 'fileable_id' => $this->id])
            ?->first();

        return $file?->public_url ?: "";
    }
}
