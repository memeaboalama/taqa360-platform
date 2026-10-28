<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin::pages.roles.index');
    }

    public function create()
    {
        return view('admin::pages.roles.create');
    }

    public function update($roleId)
    {
        return view('admin::pages.roles.update', get_defined_vars());
    }
}
