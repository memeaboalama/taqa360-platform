<?php

namespace App\Domain\AppBase\Actions\AdMerchantContent;

use App\Domain\AppBase\Models\AdMerchantContent;
use App\Support\Enum\ProductContentStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StoreMerchantContentAction
{
    public function handle(array $data): AdMerchantContent
    {
        DB::beginTransaction();

        try {
            if (!isset($data['created_by'])) {
                $data['created_by'] = Auth::id();
            }

            if (!isset($data['status'])) {
                $data['status'] = ProductContentStatusEnum::ACTIVE;
            }

            $merchantContent = AdMerchantContent::create($data);

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');

            DB::commit();

            return $merchantContent;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
