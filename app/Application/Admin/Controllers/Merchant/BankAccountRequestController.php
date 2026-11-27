<?php

namespace App\Application\Admin\Controllers\Merchant;

use App\Domain\MarketPlace\Models\MerchantBankAccountRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BankAccountRequestController extends Controller
{
    public function index()
    {
        return view('admin::pages.merchants.bank-accounts.index');
    }

    public function show(int $id)
    {
        $bankRequest = MerchantBankAccountRequest::with([
            'merchant',
            'previousIban',
            'reviewer',
            'logs',
        ])->findOrFail($id);

        return view('admin::pages.merchants.bank-accounts.show', compact('bankRequest'));
    }

    public function certificate(int $id): StreamedResponse
    {
        $bankRequest = MerchantBankAccountRequest::findOrFail($id);
        $disk = $bankRequest->iban_file_disk ?: 'local';

        if (!$bankRequest->iban_file || !Storage::disk($disk)->exists($bankRequest->iban_file)) {
            abort(404);
        }

        return Storage::disk($disk)->response($bankRequest->iban_file);
    }
}
