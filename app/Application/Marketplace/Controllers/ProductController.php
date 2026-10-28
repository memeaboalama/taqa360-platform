<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('marketplace::pages.products.index');
    }
}
