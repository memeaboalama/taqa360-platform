<?php

namespace App\Domain\User\Actions\Setting;

use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;

class UpdateUserSettings
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(User $user,array $data = [])
    {
        return $this->userRepository->update_user_settings($user,$data);
    }
}
