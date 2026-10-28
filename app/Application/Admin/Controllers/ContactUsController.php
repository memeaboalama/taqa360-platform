<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    public function index()
    {
        return view('admin::pages.messages.index');
    }
}
