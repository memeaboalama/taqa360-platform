<?php

namespace Application\Driver\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function getWelcomePage()
    {
         return view('driver::pages.auth.welcome');
    }

    
}
