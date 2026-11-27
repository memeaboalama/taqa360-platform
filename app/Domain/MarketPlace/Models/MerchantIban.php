<?php

namespace App\Domain\MarketPlace\Models;

use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MerchantIban extends Model
{
    use HasFactory,BelongsToMerchant,MerchantRelation;

    public $diskName = 'fileDisk';
    protected $fillable = [
        'merchant_id',
        'bank_name',
        'beneficiary_full_name',
        'iban',
        'is_temp',
        'is_active',
        'approved_at',
        'approved_by',
        'superseded_at',
        'request_id',
        'iban_file',
        'iban_file_disk',
    ];

    protected $casts = [
        'is_temp' => 'boolean',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'superseded_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_temp', 0)->where('is_active', true);
    }

    public function getMaskedIbanAttribute(): string
    {
        return MerchantBankAccountRequest::maskIban($this->iban);
    }

    public function getIbanFilePathAttribute()
    {
        if (!$this->iban_file) {
            return null;
        }
        $disk = $this->iban_file_disk ?: $this->diskName;
        if ($disk === 'local') {
            return null;
        }
        return Storage::disk($disk)->url($this->iban_file);
    }
}
