<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::pages.choices.index');
    }
}
