<?php

namespace App\Application\Admin\Controllers;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettlementProofController extends Controller
{
    public function show(OrderSettlement $settlement)
    {
        abort_unless(
            CollectionSetting::adminCan('review')
                || CollectionSetting::adminCan('approve')
                || CollectionSetting::adminCan('correct'),
            403
        );
        abort_unless($settlement->proof_path, 404);
        abort_unless(Storage::disk('settlement_proofs')->exists($settlement->proof_path), 404);

        return Storage::disk('settlement_proofs')->download($settlement->proof_path);
    }
}
