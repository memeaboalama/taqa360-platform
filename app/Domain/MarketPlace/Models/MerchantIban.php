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
        $file_name = str_replace('upload/file/', '', $this->iban_file);
        if(in_array(substr($this->iban_file, strpos($this->iban_file, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
        {
            return $this->iban_file ? Storage::disk($this->diskName)->url($this->iban_file. '/' . $file_name) : null;
        }else{
            return $this->iban_file ? Storage::disk($this->diskName)->url($this->iban_file) : null;
        }
//        return ($this->iban_file) ? Storage::disk('s3')->temporaryUrl(
//            $this?->iban_file,
//            now()->addMinutes(5)) : "";
    }
}
