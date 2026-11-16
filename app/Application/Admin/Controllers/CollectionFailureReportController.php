<?php

namespace App\Application\Admin\Controllers;

use App\Domain\MarketPlace\Models\CollectionSetting;
use App\Domain\MarketPlace\Models\DriverCollectionFailureReport;
use App\Http\Controllers\Controller;

class CollectionFailureReportController extends Controller
{
    public function index()
    {
        $this->authorizeAccess();

        return view('admin::pages.collection-failure-reports.index');
    }

    public function show(int $reportId)
    {
        $this->authorizeAccess();
        $report = DriverCollectionFailureReport::query()->findOrFail($reportId);

        return view('admin::pages.collection-failure-reports.show', compact('report'));
    }

    private function authorizeAccess(): void
    {
        abort_unless(
            auth('admin')->check()
                && (CollectionSetting::adminCan('review') || CollectionSetting::adminCan('approve')),
            403
        );
    }
}
