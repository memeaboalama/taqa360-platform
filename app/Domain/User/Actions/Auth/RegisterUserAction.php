<?php

namespace App\Domain\User\Actions\Auth;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use App\Domain\AppBase\Models\Offer;
use Carbon\Carbon;
use App\Domain\AppBase\Models\WalletTransaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterUserAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data): ?Model
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->store($data);

            event(new Registered($user));
            if ($user) {
                $offerFirst = Offer::where('id', '2')
                    ->where('status', '=', 'active')
                    ->where('usages_left', '>', 0)
                    ->where('expired_at', '>=', Carbon::now()->format('Y-m-d H:i:s'))->first();
                if ($offerFirst) {
                    Log::debug($offerFirst);
                    Log::debug($user);
                    $userWalletId = 'UID_' . $user->id . '_Main';
                    $wallet = $user->createWallet([
                        'name' => str_replace('_', ":", $userWalletId),
                        'description' => "User Main Wallet",
                        'slug' => $userWalletId,
                    ]);
                    // $wallet = $user->getWallet($userWalletId);

                    $mainWallet = $wallet->depositFloat($offerFirst->discount_value, [
                        'type' => "Wallet First Registered",
                        'source' => [
                            'order_code' => "",
                            'merchant_id' => "",
                            'merchant_name' => "",
                        ],
                    ], confirmed: true);

                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'type' => 'deposit',
                        'amount' => $offerFirst->discount_value,
                        'confirmed' =>  1,
                        'status' => 'confirmed',
                        'uuid' => $mainWallet?->uuid ?? Str::uuid(),
                        'meta' => [
                            'wallet' => $wallet->id,
                            'wallet_slug' => $wallet->slug,
                            'type' => "Wallet First Registered",
                            'order_code' => "",
                            'merchant_id' => "",
                            'merchant_name' => "",
                        ]
                    ]);
                }
            }
            DB::commit();
            $user->msgOffer = $offerFirst?->note ?? '';
            Log::debug($user);
            return $user;
        } catch (\Exception $exception) {
            dd($exception);
            Log::error($exception);
            DB::rollBack();
            return null;
        }
    }
}
