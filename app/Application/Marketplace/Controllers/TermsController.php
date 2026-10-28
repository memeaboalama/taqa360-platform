<?php

namespace Application\Marketplace\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function index(FetchGeneralSettingsAction $fetchGeneralSettingsAction)
    {
        $terms = $fetchGeneralSettingsAction->handle(['terms_conditions_en', 'terms_conditions_ar', 'auth_page_content_en', 'auth_page_content_ar', 'auth_page_image']);
        return view('marketplace::pages.terms.index', get_defined_vars());
    }
}
