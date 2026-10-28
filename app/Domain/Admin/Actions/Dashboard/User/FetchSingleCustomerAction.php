<?php

namespace App\Domain\Admin\Actions\Dashboard\User;

use App\Domain\User\Repositories\UserRepository;

class FetchSingleCustomerAction
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->userRepository->findByCols(['id' => $id]);
    }
}
