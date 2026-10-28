<?php

namespace App\Domain\Merchant\Actions\Profile;


use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileMerchantAction
{
    private MerchantEmployeeRepository $merchantEmployeeRepository;

    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data): Model|bool|null
    {
        return $this->merchantEmployeeRepository->update(auth()->id(), $data);
    }

    public function change_password(array $data)
    {
        $data_pass["password"] = Hash::make($data["new_password"]);
        return $this->merchantEmployeeRepository->update(auth()->id(), $data_pass);
    }
}
