<?php

namespace App\Domain\Provider\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProviderVehicle extends Model
{
    use HasFactory;


    protected $fillable = [
        'company_id',
        'provider_id',
        'vehicle_id',
        'drafted_at',
    ];

    protected $casts = [
        'drafted_at'    =>  'datetime'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

}
