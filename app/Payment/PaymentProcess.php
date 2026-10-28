<?php

namespace App\Payment;

use Illuminate\Database\Eloquent\Model;

class PaymentProcess
{
    public $payment;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }


    public function pay(array $data, Model $model)
    {

        return $this->payment->pay($data, $model);

    }

    public function paymentStatus(array $postFields)
    {
        return $this->payment->paymentStatus($postFields);
    }

    public function applePay(array $postFields)
    {
        return $this->payment->applePay($postFields);
    }


}
