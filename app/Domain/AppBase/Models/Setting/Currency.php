<?php

namespace App\Domain\AppBase\Models\Setting;

use Domain\MarketPlace\Models\MerchantService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Spatie\Translatable\HasTranslations;

class Currency extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['title'];
    protected $fillable = ['title','iso'];

    public static function storeIso(): string
    {
        $iso = Cache::remember('store_currency_iso', 3600, static function () {
            return static::query()->orderBy('id')->value('iso');
        });

        return (string) ($iso ?: 'sar');
    }

    public static function symbol(): string
    {
        $iso = static::storeIso();
        $key = 'ecommerce::ecommerce.currency_symbol';

        if (strtolower($iso) === 'sar' && Lang::has($key)) {
            $symbol = __($key);
            if (is_string($symbol) && $symbol !== '' && $symbol !== $key) {
                return $symbol;
            }
        }

        return strtoupper($iso);
    }

    public static function format($amount): string
    {
        return number_format((float) $amount, 2) . ' ' . static::symbol();
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantService::class, 'currency_id');
    }

}
