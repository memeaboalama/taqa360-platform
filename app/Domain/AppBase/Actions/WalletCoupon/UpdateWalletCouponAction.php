<?php

namespace App\Domain\AppBase\Actions\WalletCoupon;

use App\Domain\AppBase\Repositories\WalletCouponRepository;

class UpdateWalletCouponAction
{
    protected WalletCouponRepository $walletCouponRepository;


    public function __construct(WalletCouponRepository $walletCouponRepository)
    {
        $this->walletCouponRepository = $walletCouponRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->walletCouponRepository->update($id, $data);
    }
}
