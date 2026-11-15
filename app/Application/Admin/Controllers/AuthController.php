<?php

namespace Application\Admin\Controllers;

use Domain\Admin\Actions\Dashboard\Auth\LoginAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {

        return view('admin::pages.auth.login');
    }

    public function login(Request $request, LoginAction $loginAction)
    {
        $data = $request->validate([
            'email' => 'required|email|min:3',
            'password' => 'required|string|min:4',
        ]);

        if (!$loginAction->handle($data)) {
            return back()
                ->withErrors(['email' => 'Oops! Your username or password is incorrect. Please try again'])
                ->withInput($request->only('email'));
        }

        $user = auth('admin')->user();
        session()->flash('login_welcome', __('admin::home.welcome_back_msg', [
            'name' => $user->name ?? $data['email'],
        ]));

        return redirect()->route('admin:home');
    }
}
