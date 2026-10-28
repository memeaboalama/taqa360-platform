<?php

namespace Application\Company\Controllers;

use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('company::pages.employees.index');
    }
}
