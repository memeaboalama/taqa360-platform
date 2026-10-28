<?php

namespace App\Domain\AppBase\Actions\AdMerchantContent;

use App\Domain\AppBase\Models\AdMerchantContent;
use App\Domain\AppBase\Repositories\AdMerchantContentRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UpdateMerchantContentAction
{
    public function handle(int $id, array $data): AdMerchantContent
    {
        DB::beginTransaction();

        try {
            $repository = new AdMerchantContentRepository(new AdMerchantContent());
            $merchantContent = $repository->findById($id);

            if (!$merchantContent) {
                throw new \Exception('Merchant content not found');
            }

            $merchantContent->update($data);

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');

            DB::commit();

            return $merchantContent->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
