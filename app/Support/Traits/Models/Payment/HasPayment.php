<?php

namespace App\Support\Traits\Models\Payment;



use Domain\AppBase\Models\Payment;

trait HasPayment
{
    public function payment(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Payment::class, 'payable');
    }
}
