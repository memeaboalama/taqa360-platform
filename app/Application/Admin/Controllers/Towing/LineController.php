<?php

namespace Application\Admin\Controllers\Towing;

use App\Http\Controllers\Controller;

class LineController extends Controller
{
    public function index()
    {
        return view('admin::pages.towing.lines.index');
    }
}
