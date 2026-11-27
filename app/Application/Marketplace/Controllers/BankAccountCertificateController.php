<?php

namespace Application\Marketplace\Controllers;

use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BankAccountCertificateController extends Controller
{
    public function show(int $id): StreamedResponse
    {
        $merchantId = (int) resolve('_InitialMerchant');
        $request = MerchantBankAccountRequest::whereKey($id)
            ->where('merchant_id', $merchantId)
            ->firstOrFail();

        $disk = $request->iban_file_disk ?: 'local';
        if (!$request->iban_file || !Storage::disk($disk)->exists($request->iban_file)) {
            abort(404);
        }

        return Storage::disk($disk)->response($request->iban_file);
    }
}
