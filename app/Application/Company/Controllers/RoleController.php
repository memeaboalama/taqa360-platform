<?php

namespace Application\Company\Controllers;

use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return view('company::pages.roles.index');
    }
}
