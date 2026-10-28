<?php

namespace Application\Driver\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Http\Controllers\Controller;

class PoliciesController extends Controller
{
    public function index(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $policies = $fetchGeneralSettingsAction->handle(['policies_en', 'policies_ar', 'auth_page_content_en', 'auth_page_content_ar', 'auth_page_image']);
        return view('driver::pages.policies.index', get_defined_vars() + ['page' => 'privacy']);
    }

    public function terms(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $policies = $fetchGeneralSettingsAction->handle(['terms_conditions_en', 'terms_conditions_ar', 'auth_page_content_en', 'auth_page_content_ar', 'auth_page_image']);
        return view('driver::pages.policies.index', get_defined_vars() + ['page' => 'terms']);
    }
}
