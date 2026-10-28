<?php

namespace App\Application\Admin\Controllers\User;

use App\Domain\User\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.users.index');
    }

    public function show(int $id, UserRepository $userRepository): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = $userRepository->findByCols(['id' => $id]);
        return view('admin::pages.users.show', get_defined_vars());
    }

    public function show_orders(int $user_id, int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.users.orders.marketplace.show', get_defined_vars());
    }

    public function showTowingOrders(int $user_id, int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.users.orders.towing.show', get_defined_vars());
    }
}
