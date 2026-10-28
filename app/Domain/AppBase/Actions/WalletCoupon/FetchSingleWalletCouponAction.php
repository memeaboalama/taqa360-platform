<?php

namespace App\Domain\AppBase\Actions\WalletCoupon;

use App\Domain\AppBase\Repositories\WalletCouponRepository;

class FetchSingleWalletCouponAction
{
    protected WalletCouponRepository $walletCouponRepository;


    public function __construct(WalletCouponRepository $walletCouponRepository)
    {
        $this->walletCouponRepository = $walletCouponRepository;
    }
    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->walletCouponRepository->findByCols(['id' => $id]);
    }
}
