<?php

namespace Domain\Admin\Models;

use App\Domain\MarketPlace\Models\MerchantOrderHistory;
use App\Support\Traits\Models\HasOtp;
use Domain\Notification\Models\DeviceToken;
use Domain\Project\Models\SubTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use HasApiTokens ,HasFactory, HasRoles;
    use Notifiable, HasOtp;
    protected $guard = 'admin-api';
    protected $fillable = ['name', 'email', 'phone', 'password', 'type', 'status'];

    public function device_tokens(): MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }

    public function logOrderHistory(): MorphMany
    {
        return $this->morphMany(MerchantOrderHistory::class, 'changeable');
    }

    public function subTasks()
    {
        return $this->morphMany(SubTask::class, 'creatable');
    }
}
