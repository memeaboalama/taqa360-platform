<?php

namespace App\Domain\User\Actions\Profile;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;

class SaveNewPhoneToUserListAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(array $data): User|Authenticatable|null
    {
        $user = $this->userRepository->auth_user();

        $user->phones()->create([
           'phone'   =>  $data['phone'],
        ]);

        return $user;
    }
}
