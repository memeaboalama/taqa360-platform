<?php

namespace App\Domain\User\Actions\Address;

use App\Domain\AppBase\Repositories\CityRepository;
use App\Domain\AppBase\Repositories\DistractRepository;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use App\Support\Enum\StatusEnum;

class UpdateUserAddressAction
{
    private UserRepository $userRepository;
    private CityRepository $cityRepository;
    private DistractRepository $distractRepository;

    public function __construct(UserRepository $userRepository,CityRepository $cityRepository, DistractRepository $distractRepository)
    {
        $this->userRepository = $userRepository;
        $this->cityRepository = $cityRepository;
        $this->distractRepository = $distractRepository;
    }


    public function handle(User $user, array $data , int $address_id,array $names=[])
    {
        if (isset($names["city"]) && isset($names["state"])) {
            $data["city_id"] = $this->cityRepository->findByKey($names["city"])?->id;
            $data["distract_id"] = $this->distractRepository->findByKey($names["state"])?->id;
        }

//        if (is_null($data["city_id"]) || is_null($data["distract_id"])) return false;
        return $this->userRepository->update_user_addresses($user,$data,$address_id);
    }
}
