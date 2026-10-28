<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function update(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.terms.update');
    }
}
