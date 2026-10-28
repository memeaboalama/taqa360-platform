<?php

namespace App\Domain\Company\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\Hash;

class UpdateDriverAction
{
    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        if (isset($data['password']))
            $data['password'] = Hash::make($data['password']);
        return $this->driverRepository->update($id, $data);
    }
}
