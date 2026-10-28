<?php

namespace App\Domain\Provider\Actions\Dashboard\Auth;

use App\Domain\Provider\Repositories\DriversRegisters\DriversRegistersRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterDriverAction
{
    protected DriversRegistersRepository $driversRegistersRepository;


    public function __construct(DriversRegistersRepository $driversRegistersRepository)
    {
        $this->driversRegistersRepository = $driversRegistersRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['country_id'] = 1;
        $driversRegisters = $this->driversRegistersRepository->store($data);
        return $driversRegisters;
    }

}
