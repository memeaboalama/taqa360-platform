<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CustomerTestimonialsController extends Controller
{
    public function index(): View
    {
        return view('admin::pages.customer-testimonials.index');
    }
}
