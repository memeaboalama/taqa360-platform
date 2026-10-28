<?php

namespace App\Application\Educator\Controllers\Api\Profile;

use App\Application\Educator\Requests\Profile\BankInfoRequest;
use App\Domain\Educator\Resource\Profile\BankInfoResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Domain\Educator\Actions\Profile\UpdateBankInfoAction;
use Illuminate\Http\Request;

class BankInfoController extends Controller
{
    use ApiResponseTraitV2;

    public function fetch(Request $request)
    {
        $educator = $request->user();
        return $this->responseData(['bankInfo'=> $educator->bankInfo ? BankInfoResource::make($educator->bankInfo) : [] ]);
    }

    public function createOrUpdate(BankInfoRequest $request, UpdateBankInfoAction $action)
    {
        $educator = $request->user();
        $action->handle($educator, $request->validated());
        return $this->responseData(['bankInfo'=> BankInfoResource::make($educator->bankInfo)], msg:['message'=>'Bank info updated successfully']);
    }


    public function destroy(UpdateBankInfoAction $action)
    {
        $educator = auth()->user();
        $action->delete($educator);
        return $this->responseData(msg:'Bank info deleted successfully');
    }
}
