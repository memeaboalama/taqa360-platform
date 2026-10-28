<?php

namespace App\Domain\Admin\Actions\Dashboard\Driver;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Model;

class RejectDriverAction
{
    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    /**
     * Reject a pending driver, keeping the record with its reason.
     *
     * Only drivers pending approval (is_complete = 0) that are not already
     * rejected can be rejected. Returns null when the operation is not allowed.
     */
    public function handle(int $id, string $reason): ?Model
    {
        $driver = $this->driverRepository->findByCols(['id' => $id]);

        if (!$driver) {
            return null;
        }

        if ((int) $driver->is_complete === 1) {
            return null;
        }

        if (($driver->getAttributes()['status'] ?? null) === StatusEnum::REJECTED->value) {
            return null;
        }

        return $this->driverRepository->update($id, [
            'status' => StatusEnum::REJECTED->value,
            'rejection_reason' => $reason,
            'is_complete' => 0,
        ]);
    }
}
