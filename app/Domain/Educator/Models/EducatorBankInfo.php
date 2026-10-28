<?php

namespace Domain\Educator\Models;

use App\Domain\AppBase\Models\Bank;
use Domain\Project\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducatorBankInfo extends Model
{
    use HasFactory, HasUuid;

    protected $table='educator_bank_info';
    protected $fillable = [
        'educator_id',
        'iban',
        'name',
        'address',
        'bank_country',
        'bank_code',
        'bank_name',
        'is_approved'
    ];

    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class);
    }
}
