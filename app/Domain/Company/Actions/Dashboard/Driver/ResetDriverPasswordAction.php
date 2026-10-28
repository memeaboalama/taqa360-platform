<?php

namespace App\Domain\Company\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\Hash;

class ResetDriverPasswordAction
{
    protected DriverRepository $driverRepository;

    public function __construct()
    {
        $this->driverRepository = new DriverRepository(new Driver());
    }

    public function handle(int $id, array $data): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->driverRepository->update($id, ['password' => Hash::make($data['password'])]);
    }
}