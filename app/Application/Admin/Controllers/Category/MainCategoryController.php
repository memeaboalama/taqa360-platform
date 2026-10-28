<?php

namespace Application\Admin\Controllers\Category;

class MainCategoryController
{
    public function index()
    {
        return view('admin::pages.categories.main.index');
    }
}
