<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Models\Setting\Language;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    use ApiResponseTrait;

    public function getLanguages(Request $request): JsonResponse
    {
        $languages = Language::where('status',1)->get()->map(function($language){
            return [
                'id'    =>  $language->uuid,
                'title' =>  $language->title
            ];
        });

        return $this->responseData([
            'languages' => $languages
        ]);
    }
}
