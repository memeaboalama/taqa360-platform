<?php

namespace Application\Driver\Controllers;

use App\Domain\MarketPlace\Models\OrderSettlement;
use App\Domain\MarketPlace\Models\OrderSettlementHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettlementProofController extends Controller
{
    public function show(OrderSettlement $settlement)
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);
        abort_unless((int) $settlement->driver_id === (int) $driverId, 404);
        abort_unless($settlement->proof_path, 404);
        abort_unless(Storage::disk('settlement_proofs')->exists($settlement->proof_path), 404);

        return Storage::disk('settlement_proofs')->download($settlement->proof_path);
    }

    public function showHistory(OrderSettlement $settlement, OrderSettlementHistory $history)
    {
        $driverId = auth('driver')->id();
        abort_unless($driverId, 403);
        abort_unless((int) $settlement->driver_id === (int) $driverId, 404);
        abort_unless((int) $history->settlement_id === (int) $settlement->id, 404);

        $path = $history->snapshot['proof_path'] ?? null;
        abort_unless($path && Storage::disk('settlement_proofs')->exists($path), 404);

        return Storage::disk('settlement_proofs')->download($path);
    }
}
