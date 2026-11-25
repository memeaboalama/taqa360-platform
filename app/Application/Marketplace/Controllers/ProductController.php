<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function edit(int $id, \App\Domain\MarketPlace\Actions\Dashboard\Item\FetchSingleItemAction $action)
    {
        abort_unless($action->handle($id, (int) auth('merchant')->id()), 404);

        return view('marketplace::pages.products.edit', ['productId' => $id]);
    }

    public function index()
    {
        return view('marketplace::pages.products.index');
    }
}
