<?php

namespace App\Domain\AppBase\Actions\AdProductContent;

use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Domain\AppBase\Models\AdProductContent;
use Illuminate\Support\Facades\DB;

class ReorderProductContentsAction
{
    public function handle(int $placementId, array $order = []): void
    {
        DB::beginTransaction();

        try {
            $repository = new AdProductContentRepository(new AdProductContent());

            if (empty($order)) {
                $contents = $repository->builder(
                        order: 'asc',
                        orderCol: 'priority',
                        condition: ['placement_id' => $placementId]
                    )
                    ->orderBy('updated_at', 'desc')
                    ->get();

                foreach ($contents as $index => $content) {
                    $content->update(['priority' => $index + 1]);
                }

            } else {
                $priority = 1;
                foreach ($order as $item) {
                    if (isset($item['id'])) {
                        $productContent = $repository->findById($item['id']);
                        if ($productContent && $productContent->placement_id == $placementId) {
                            $productContent->update(['priority' => $priority]);
                            $priority++;
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
