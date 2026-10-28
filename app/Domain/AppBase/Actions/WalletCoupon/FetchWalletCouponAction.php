<?php

namespace App\Domain\AppBase\Actions\WalletCoupon;

use App\Domain\AppBase\Repositories\WalletCouponRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchWalletCouponAction
{
    protected WalletCouponRepository $walletCouponRepository;


    public function __construct(WalletCouponRepository $walletCouponRepository)
    {
        $this->walletCouponRepository = $walletCouponRepository;
    }

    public function handle(): Collection
    {
        return $this->walletCouponRepository->all();
    }
}
