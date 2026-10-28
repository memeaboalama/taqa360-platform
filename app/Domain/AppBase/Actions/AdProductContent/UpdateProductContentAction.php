<?php

namespace App\Domain\AppBase\Actions\AdProductContent;

use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Support\Enum\ProductContentStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UpdateProductContentAction
{
    public function handle(int $id, array $data): AdProductContent
    {
        DB::beginTransaction();

        try {
            $repository = new AdProductContentRepository(new AdProductContent());
            $productContent = $repository->findById($id);

            if (!$productContent) {
                throw new \Exception('Product content not found');
            }

            $productContent->update($data);

            // Clear homepage cache
            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');

            DB::commit();

            return $productContent->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
