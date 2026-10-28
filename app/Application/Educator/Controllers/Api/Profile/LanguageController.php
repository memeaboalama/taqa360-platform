<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\LanguageRequest;
use App\Domain\Educator\Resource\Profile\LanguageResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateLanguagesAction;
use Domain\Educator\Models\EducatorAcademicMajor;
use Domain\Educator\Models\EducatorLanguage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request)
    {
        $educator = $request->user();
        $language = $educator->languages()->with('language')->get();
        return $this->responseData(['language' => LanguageResource::collection($language)]);
    }

    public function store(LanguageRequest $request, UpdateLanguagesAction $action)
    {
        $educator = $request->user();
        $language = $action->handle($educator, $request->validated(), null);
        return $this->responseData(['language' => LanguageResource::make($language)]);
    }

    public function update(LanguageRequest $request, EducatorLanguage $language, UpdateLanguagesAction $action)
    {
        $educator = $request->user();
        $language = $action->handle($educator, $request->validated(), (int)$language->id);
        return $this->responseData(['language' => LanguageResource::make($language)]);
    }

    public function destroy(Request $request, EducatorLanguage $language, UpdateLanguagesAction $action)
    {
        $educator = $request->user();
        $action->delete($educator, (int)$language->id);

        return $this->responseData(msg:'Language deleted successfully');
    }
}
