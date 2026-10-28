<?php

namespace Application\Educator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:educator');
    }

    public function index(Request $request)
    {
        return view('educator::pages.home');
    }

    public function getHomePage()
    {
        return view('educator::pages.home.index');
    }
}
