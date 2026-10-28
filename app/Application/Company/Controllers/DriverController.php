<?php

namespace Application\Company\Controllers;

use App\Domain\Driver\Repositories\DriverRepository;
use App\Http\Controllers\Controller;

class DriverController extends Controller
{
    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function index()
    {
        return view('company::pages.drivers.index');
    }

    public function show(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $stats = $this->driverRepository->getDriverTotalOrderReport($id);
        return view('company::pages.drivers.show', get_defined_vars());
    }

    public function trackDrivers()
    {
        return view('company::pages.drivers.track');
    }
}
