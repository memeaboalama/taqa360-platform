<?php

namespace Application\Admin\Controllers;

use App\Domain\Driver\Repositories\HistoryDriverRepository;
use App\Http\Controllers\Controller;

class HistoryDriverController extends Controller
{
    private $driverRepository;
    public function __construct(HistoryDriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function history()
    {
        return view('admin::pages.drivers.history');
    }
}
