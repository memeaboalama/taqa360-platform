<?php

namespace App\Domain\AppBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GeneralSetting extends Model
{
    use HasFactory;
    protected $fillable=[
        'terms_conditions_en',
        'terms_conditions_ar',
        'policies_en',
        'policies_ar',
        'auth_page_content_en',
        'auth_page_content_ar',
        'user_page_content_en',
        'user_page_content_ar',
        'auth_page_image',
        'vat',
        'restaurant_search_capacity',
        'driver_search_capacity',
        'delivery_km',
        'delivery_fees',
        'extra_km_fees',
        'min_order_price',
        'pending_order_expiry',
        'confirmed_order_expiry',
        'resend_order_to_drivers',
        'visa_commission',
        'master_commission',
        'mada_commission',
        'apple_commission',
        'stc_commission',
    ];

    public function getAuthPageImagePathAttribute()
    {
        // return ($this->auth_page_image) ? Storage::disk('s3')->temporaryUrl(
        //     $this->auth_page_image,
        //     now()->addMinutes(5)) : "";
        return ($this->auth_page_image) ? Storage::disk('fileDisk')->url($this->auth_page_image) : "";
    }
}
