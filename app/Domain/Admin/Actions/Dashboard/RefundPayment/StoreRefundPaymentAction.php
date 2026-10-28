<?php

namespace App\Domain\Admin\Actions\Dashboard\RefundPayment;

use App\Domain\RefundPayment\Repositories\RefundPaymentRepository;
use Illuminate\Support\Facades\Hash;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StoreRefundPaymentAction
{
    protected RefundPaymentRepository $refundpaymentRepository;


    public function __construct(RefundPaymentRepository $refundpaymentRepository)
    {
        $this->refundpaymentRepository = $refundpaymentRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->refundpaymentRepository->store($data);
    }
}
