<?php

namespace Application\Educator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('educator::pages.auth.login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('educator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('educator:login');
    }
}
