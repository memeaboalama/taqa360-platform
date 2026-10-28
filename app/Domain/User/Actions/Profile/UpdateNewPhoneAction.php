<?php

namespace App\Domain\User\Actions\Profile;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateNewPhoneAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(array $data): User|Authenticatable|null
    {
        $user = $this->userRepository->auth_user();
        try {

            DB::beginTransaction();

            $this->userRepository->update_phone_data($user ,['phone' => $user->phone], ['default' => false]);

            $this->userRepository->update_phone_data($user ,[
                'phone' => $data['phone']],
                [
                    'is_default'       => true,
                    'verified_at'    =>  now(),
                ]
            );

            $user->update([
               'phone'   =>  $data['phone'],
            ]);
            DB::commit();
        }catch (\Exception $exception)
        {
            Db::rollBack();
            Log::error($exception);
        }

        return $user;
    }
}
