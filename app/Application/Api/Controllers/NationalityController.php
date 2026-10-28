<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class NationalityController extends Controller
{
    use ApiResponseTrait;

    public function getNationalities() :JsonResponse
    {
        $nationalities = Nationality::where('status',1)->get()->map(function($nationality){
            return [
                'id'    =>  $nationality->uuid,
                'title' =>  $nationality->title
            ];
        });

        return $this->responseData([
            'nationalities' => $nationalities
        ]);
    }
}
