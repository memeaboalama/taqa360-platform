<?php

namespace App\Application\Api\Controllers;

use App\Application\Api\Requests\ContactUs\SendMessageRequest;
use App\Domain\AppBase\Actions\ContactUs\SendMessageAction;
use App\Domain\AppBase\Models\Bank;
use App\Domain\AppBase\Resources\BankResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class BankController  extends Controller
{
    use ApiResponseTrait;

    public function fetch(): JsonResponse
    {

        return $this->responseData([
            'bank' => BankResource::collection(Bank::query()->get())
        ]);
    }

}
