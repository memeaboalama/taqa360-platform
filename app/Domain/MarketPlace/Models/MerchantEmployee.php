<?php

namespace Domain\MarketPlace\Models;

use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasOtp;
use App\Support\Traits\Models\HasStatus;
use Domain\Notification\Models\DeviceToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class MerchantEmployee extends Authenticatable
{
    use HasFactory, SoftDeletes, HasStatus, BelongsToMerchant, HasApiTokens, HasRoles, Notifiable, HasOtp;

    protected $table = "merchant_employees";
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dialcode',
        'country_name',
        'country_code',
        'status',
        'last_logged_in_at',
        'country_id',
        'merchant_id',
    ];
    protected $hidden = [
        'password',

    ];
    protected $casts = [

        'last_logged_in_at' => 'datetime',
    ];
//    protected $appends = ['phone_number'];

    public function getPhoneNumberAttribute(): string
    {
        return $this->dialcode . ltrim($this->phone, 0);
    }

    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function device_tokens(): MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MerchantBranch::class, 'merchant_branch_employees', 'merchant_employee_id', 'merchant_branch_id');
    }


    public function branchManager(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantBranch::class, 'manager_id', 'id');
    }

    public function logOrderHistory(): MorphMany
    {
        return $this->morphMany(MerchantOrderHistory::class, 'changeable');
    }

}
