<?php

namespace App\Domain\AppBase\Actions\AdPlacement;

use App\Domain\AppBase\Models\AdPlacement;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use Illuminate\Support\Str;

class StorePlacementAction
{
    public function __construct(
        protected AdPlacementRepository $placementRepository
    ) {}

    public function handle(array $data): AdPlacement
    {
        if (!isset($data['key']) || empty($data['key'])) {
            $data['key'] = $this->generateKey($data);
        }

        return $this->placementRepository->store($data);
    }

    protected function generateKey(array $data): string
    {
        $pageId = $data['page_id'];
        $name = $data['name']['en'] ?? 'placement';

        $baseKey = Str::slug($name, '_');
        $key = "page_{$pageId}_{$baseKey}";

        $counter = 1;
        while ($this->placementRepository->keyExistsForPage($pageId, $key)) {
            $key = "page_{$pageId}_{$baseKey}_{$counter}";
            $counter++;
        }

        return $key;
    }
}
