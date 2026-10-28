<?php

namespace Application\Educator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerifyController extends Controller
{
    public function showVerifyForm(): View|RedirectResponse
    {
        if (!session()->has('educator_id_for_verification')) {
            return redirect()->route('educator:register');
        }
        return view('educator::pages.auth.verify');
    }
}
