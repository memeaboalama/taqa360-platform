<?php

namespace App\Application\Api\Controllers\User\Wallet;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Http\Request;

class MerchantsWalletsController extends Controller
{
    use ApiResponseTrait;

    public function fetch(Request $request)
    {
        $data = [];
        $user = request()->user();
        $merchantsBalance = $user->wallets()
        ->where('name', '!=', 'UID:'.$user->id.'Main')
        ->where('description', 'User Merchant Wallet')
        ->where('balance', '>', 0)
        ->get();
        
        foreach($merchantsBalance as $merchant){
            $data[] = [
                'merchant_id' => $merchant->meta['merchant_id'],
                'merchant_name' => Merchant::where('id',$merchant->meta['merchant_id'])->value('company_name'),
                'balance' => $merchant->balanceFloatNum,
            ];
        } 
        return $this->responseData([
            'MerchantsWallets' => $data,
        ], 200);

    }
    
}
