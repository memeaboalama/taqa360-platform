<?php

namespace Application\Admin\Controllers;

use App\Domain\Provider\Repositories\DriversRegisters\DriversRegistersRepository;
use App\Http\Controllers\Controller;

class DriverRegisterController extends Controller
{
    private $driverRepository;
    private $id;
    public function __construct(DriversRegistersRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function showDriver(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->id=$id;
        return view('admin::pages.drivers.registershow', get_defined_vars());
    }

    public function register()
    {
        return view('admin::pages.drivers.register');
    }

}
