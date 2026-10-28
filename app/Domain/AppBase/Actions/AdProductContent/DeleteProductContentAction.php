<?php

namespace App\Domain\AppBase\Actions\AdProductContent;

use App\Domain\AppBase\Repositories\AdProductContentRepository;
use App\Support\Logger\AppLogger;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DeleteProductContentAction
{
    public function handle(int $id): bool
    {
        DB::beginTransaction();

        try {
            $repository = new AdProductContentRepository(new \App\Domain\AppBase\Models\AdProductContent());
            $productContent = $repository->findById($id);

            if ($productContent) {
                $productContent->delete();

                Cache::forget('home_page_data_ar');
                Cache::forget('home_page_data_en');
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
