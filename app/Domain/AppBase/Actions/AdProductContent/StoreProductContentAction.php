<?php

namespace App\Domain\AppBase\Actions\AdProductContent;

use App\Domain\AppBase\Models\AdProductContent;
use App\Support\Enum\ProductContentStatusEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StoreProductContentAction
{
    public function handle(array $data): AdProductContent
    {
        DB::beginTransaction();

        try {
            if (!isset($data['created_by'])) {
                $data['created_by'] = Auth::id();
            }

            if (!isset($data['status'])) {
                $data['status'] = ProductContentStatusEnum::ACTIVE;
            }

            $productContent = AdProductContent::create($data);

            Cache::forget('home_page_data_ar');
            Cache::forget('home_page_data_en');

            DB::commit();

            return $productContent;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
