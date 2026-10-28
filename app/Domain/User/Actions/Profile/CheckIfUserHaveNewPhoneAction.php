<?php

namespace App\Domain\User\Actions\Profile;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CheckIfUserHaveNewPhoneAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(array $data): Model|null
    {
        $user = $this->userRepository->auth_user();
        return $this->userRepository->fetch_phone_data($user,[
            'phone' =>  $data['phone'],
            'is_default' => 'false',
            'verified_at' => null,
        ]);

    }
}
