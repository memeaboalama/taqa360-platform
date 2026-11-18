<?php

namespace App\Support\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasConfiguredCartVat
{
    protected function vatRate(): Attribute
    {
        return new Attribute(
            get: fn() => (float) ($this->cartItems->first()?->item?->merchant?->country?->vat ?? 0)
        );
    }

    protected function tax(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->subtotal * ($this->vat_rate / 100), 2)
        );
    }
}
