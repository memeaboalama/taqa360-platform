<?php

namespace App\Domain\Provider\Models;

use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class CompanyEmployee extends Authenticatable
{
    use HasFactory, HasRoles, HasStatus, Notifiable;

    protected $table = "company_employees";
    protected $guard_name = 'company_employee';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dialcode',
        'country_id',
        'country_name',
        'country_code',
        'status',
        'last_logged_in_at',
        'company_id',
    ];
    protected $hidden = [
        'password',

    ];
    protected $casts = [

        'last_logged_in_at' => 'datetime',
    ];

    public function getPhoneNumberAttribute(): string
    {
        return $this->dialcode . ltrim($this->phone, 0);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
