<?php

namespace App\Payment;

use Illuminate\Database\Eloquent\Model;

interface PaymentInterface
{


    public function data(array $data,Model $model): array;

    public function message(int $code): string;

    public function pay(array $data,Model $model);

    public function errors(): array;


    public function paymentStatus(array $postFields);

    public function applePay(array $postFields);

}
