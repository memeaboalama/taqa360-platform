<?php

namespace App\Domain\Ecommerce\Auth;

use App\Domain\User\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetectUserAction
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function detectEducatorApi(array $data): Model|bool|null
    {
        if (!Auth::guard('web')->attempt(request()->only('email', 'password'))) {
            return false;
        }

        return $this->educatorRepository->findByCols(cols: ['email' => $data["email"]],with:[]);
    }
}
