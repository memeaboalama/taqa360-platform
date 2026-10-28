<?php

namespace App\Domain\AppBase\Actions\AdPlacement;

use App\Domain\AppBase\Repositories\AdPlacementRepository;

class UpdatePlacementAction
{
    public function __construct(
        protected AdPlacementRepository $placementRepository
    ) {}

    public function handle(int $id, array $data): bool
    {
        $result = $this->placementRepository->update($id, $data);
        return $result ? true : false;
    }
}
