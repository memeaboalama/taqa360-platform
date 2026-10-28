<?php

namespace Application\Admin\Controllers;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Http\Controllers\Controller;

class DriverController extends Controller
{
    private $driverRepository;
    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function index()
    {
        return view('admin::pages.drivers.index');
    }

    public function show(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $stats = $this->driverRepository->getDriverTotalOrderReport($id);
        return view('admin::pages.drivers.show', get_defined_vars());
    }

    public function trackDrivers()
    {
        return view('admin::pages.drivers.track');
    }

    public function getPending(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.drivers.pending.index');
    }
}
