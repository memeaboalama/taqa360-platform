<?php

namespace App\Domain\MarketPlace\Actions\Api\Wallet;


use App\Domain\AppBase\Models\WalletTransaction;
use App\Domain\MarketPlace\Repositories\Wallet\WalletTransactionRepository;
use App\Domain\User\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
class WalletDepositAction
{

    private WalletTransactionRepository $walletTransactionRepository;


    public function __construct(WalletTransactionRepository $walletTransactionRepository)
    {
        $this->walletTransactionRepository = $walletTransactionRepository;
    }

    public function handle(int $token): \Illuminate\Database\Eloquent\Model|null
    {
        $cols = ['id', 'user_id', 'amount' ,'confirmed' , 'status'];
        $walletDeposit = $this->walletTransactionRepository->find(cols: $cols, conditions: ['id' => $token]);

        if ($walletDeposit) {
            Session::put('payment_merchant_iframe', [
                'deposit_id' => $walletDeposit?->id,
            ]);
            $walletDeposit->PriceAtSmallestUnit = $walletDeposit->amount * 100;
            $walletDeposit->key = $walletDeposit->id;
            return $walletDeposit;
        }
        return null;

    }
    public function create(array $data, User $user){

        return WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $data['amount'],
            'confirmed' => 0,
            'status' => 'created',
            'uuid' => Str::uuid(),
        ]);
    }


    public function update($id, array $status)
    {
       return $this->walletTransactionRepository->update($id, $status);
    }
}   