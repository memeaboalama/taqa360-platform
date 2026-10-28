<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    public function sendMessage()
    {
        return view('marketplace::pages.profile.support.send');
    }
}
