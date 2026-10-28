<?php

namespace Domain\MarketPlace\Models;

use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantBranchWorkday extends Model
{
    use HasFactory,HasStatus;
    protected $table="merchant_branches_work_days";

    protected $fillable = ["merchant_branch_id", "day_id", 'status'];

    public function day(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Day::class, 'day_id', 'id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantBranch::class, 'merchant_branch_id', 'id');
    }

    public function workHours(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantBranchWorkdayHour::class, 'work_day_id', 'id');
    }
}
