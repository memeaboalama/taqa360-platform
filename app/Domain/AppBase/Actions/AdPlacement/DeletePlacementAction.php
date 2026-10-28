<?php

namespace App\Domain\AppBase\Actions\AdPlacement;

use App\Domain\AppBase\Repositories\AdPlacementRepository;

class DeletePlacementAction
{
    public function __construct(
        protected AdPlacementRepository $placementRepository
    ) {}

    public function handle(int $id): bool
    {
        return $this->placementRepository->destroy($id);
    }
}
