<?php

namespace Application\Admin\Controllers\Catalog;

use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    public function getCategoriesPage()
    {
        return view('admin::pages.catalogs.categories');
    }
    public function getSubcategoriesPage()
    {
        return view('admin::pages.catalogs.subcategories');
    }

    public function getChaildCategoriesPage()
    {
        return view('admin::pages.catalogs.childCategories');
    }
}
