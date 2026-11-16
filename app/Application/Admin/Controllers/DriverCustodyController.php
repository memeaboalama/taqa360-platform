<?php

namespace App\Application\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\OrderSettlement;
use Domain\Driver\Models\Driver;

class DriverCustodyController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();

        return view('admin::pages.driver-custody.index');
    }

    public function show(int $driverId)
    {
        $this->authorizeAccess();
        $driver = Driver::query()->findOrFail($driverId);

        return view('admin::pages.driver-custody.show', compact('driver'));
    }

    public function settlements()
    {
        $this->authorizeSettlementAccess();

        return view('admin::pages.settlement-review.index');
    }

    public function settlement(int $settlementId)
    {
        $this->authorizeSettlementAccess();
        $settlement = OrderSettlement::query()->findOrFail($settlementId);

        return view('admin::pages.settlement-review.show', compact('settlement'));
    }

    private function authorizeSettlementAccess(): void
    {
        abort_unless(
            auth('admin')->check()
                && (CollectionSetting::adminCan('review') || CollectionSetting::adminCan('approve')),
            403
        );
    }

    private function authorizeAccess(): void
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            abort(403);
        }

        setPermissionsTeamId(1);
        abort_unless(
            $admin->type === 'admin'
                || $admin->hasAnyPermission(['admin', 'show delivery fees_settings', 'edit delivery fees_settings'])
                || CollectionSetting::adminCan('view'),
            403
        );
    }
}
