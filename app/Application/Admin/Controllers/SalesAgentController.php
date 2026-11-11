<?php

namespace Application\Admin\Controllers;

use App\Domain\SalesAgent\Repositories\SalesAgentRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SalesAgentController extends Controller
{
    use LivewireAlert;

    private $salesAgentRepository;

    public function __construct(SalesAgentRepository $salesAgentRepository)
    {
        $this->salesAgentRepository = $salesAgentRepository;
    }

    public function index()
    {
        return view('admin::pages.salesagent.index');
    }

    public function show(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $salesAgent = $this->salesAgentRepository->findById($id);
        if (!$salesAgent) {
            return $this->flash(
                'warning',
                __('admin::home.sales_agent_not_found'),
                [],
                LaravelLocalization::getLocalizedURL(app()->getLocale(), url('admin/salesagents'))
            );
        }

        $stats = $this->salesAgentRepository->getSalesAgentTotalOrderReport($id);
        $hasFiles = $salesAgent->files()->exists();

        return view('admin::pages.salesagent.show', get_defined_vars());
    }


    public function getPending(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.salesagent.pending.index');
    }
}
