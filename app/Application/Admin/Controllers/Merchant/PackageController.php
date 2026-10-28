<?php

namespace App\Application\Admin\Controllers\Merchant;

use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.packages.index');
    }

    public function update(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.packages.update', get_defined_vars());
    }
}
