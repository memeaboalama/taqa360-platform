<?php

namespace Application\Admin\Controllers\Category;

use App\Http\Controllers\Controller;

class MarketplaceCategoryController extends Controller
{
    public function index()
    {
        return view('admin::pages.categories.marketplace.index');
    }
}
