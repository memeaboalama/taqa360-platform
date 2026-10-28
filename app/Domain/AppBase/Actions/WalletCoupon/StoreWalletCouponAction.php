<?php

namespace App\Domain\AppBase\Actions\WalletCoupon;

use App\Domain\AppBase\Repositories\WalletCouponRepository;

class StoreWalletCouponAction
{
    protected WalletCouponRepository $walletCouponRepository;


    public function __construct(WalletCouponRepository $walletCouponRepository)
    {
        $this->walletCouponRepository = $walletCouponRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->walletCouponRepository->store($data);
    }
}
