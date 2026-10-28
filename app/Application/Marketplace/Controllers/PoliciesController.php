<?php

namespace Application\Marketplace\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Http\Controllers\Controller;

class PoliciesController extends Controller
{
    public function index(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $policies = $fetchGeneralSettingsAction->handle(['policies_en', 'policies_ar', 'auth_page_content_en', 'auth_page_content_ar', 'auth_page_image']);
        return view('marketplace::pages.policies.index', get_defined_vars());
    }
}
