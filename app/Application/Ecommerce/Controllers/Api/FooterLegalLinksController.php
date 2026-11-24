<?php

namespace App\Application\Ecommerce\Controllers\Api;

use App\Domain\AppBase\Actions\Home\FetchHomeDataAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Illuminate\Http\JsonResponse;

class FooterLegalLinksController extends Controller
{
    use ApiResponseTraitV2;

    public function index(FetchHomeDataAction $fetchHomeDataAction): JsonResponse
    {
        $footerData = $fetchHomeDataAction->getStoreFooterData() ?? [];

        return $this->responseData([
            'terms_url' => data_get($footerData, 'links.terms'),
            'privacy_url' => data_get($footerData, 'links.privacy'),
        ]);
    }
}
