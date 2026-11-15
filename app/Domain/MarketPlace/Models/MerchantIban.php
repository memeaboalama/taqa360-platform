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
        'iban_file',
    ];

    public function getIbanFilePathAttribute()
    {
        return $this->iban_file ? Storage::disk($this->diskName)->url($this->iban_file) : null;
//        return ($this->iban_file) ? Storage::disk('s3')->temporaryUrl(
//            $this?->iban_file,
//            now()->addMinutes(5)) : "";
    }
}
