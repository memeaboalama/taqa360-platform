<?php

namespace App\Application\Api\Controllers;

use App\Application\Api\Requests\ContactUs\SendMessageRequest;
use App\Domain\AppBase\Actions\ContactUs\SendMessageAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ContactUsController  extends Controller
{
    use ApiResponseTrait;

    public function send(SendMessageRequest $request, SendMessageAction $sendMessageAction): JsonResponse
    {
        $data = $request->validated();
        $message = $sendMessageAction->handle($data);
        return $this->responseData([
            'message' => "Message sent successfully"
        ]);
    }

}
