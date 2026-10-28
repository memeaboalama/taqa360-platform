<?php

namespace App\Domain\Admin\Actions\Dashboard\User;

use App\Domain\User\Repositories\UserRepository;

class UpdateBasicInfoAction
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(int $userId ,array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $user = $this->userRepository->findByCols(['id'=>$userId]);
        $user->update($data);
        return $user;
    }
}
