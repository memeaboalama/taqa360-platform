<?php

namespace App\Domain\Company\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\Hash;

class StoreDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['company_id'] = auth()->guard('company')->user()->id;
        $data['password'] = Hash::make($data['password']);
        return $this->driverRepository->store($data);
    }
}
