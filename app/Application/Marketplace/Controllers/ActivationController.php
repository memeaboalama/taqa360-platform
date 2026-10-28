<?php

namespace Application\Marketplace\Controllers;

use App\Application\Marketplace\requests\Order\UploadFilesRequest;
use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateFilesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ActivationController extends Controller
{
    use LivewireAlert;
    public function getProfile()
    {
        return view('marketplace::pages.profile.index');
    }

    public function forgetPassword()
    {
        return view('marketplace::pages.profile.password');
    }

    public function verifyEmail()
    {
        return view('marketplace::pages.profile.verify.email');
    }

}
