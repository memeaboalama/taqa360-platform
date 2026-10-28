<?php

namespace App\Domain\User\Actions\Auth;


use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;

class DetectUserAction
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
        return  $this->userRepository->findByCols(cols:$data,select:((new User)->getAvailableData()));
    }
}
