<?php

namespace Application\Admin\Controllers;
use App\Domain\SalesAgent\Repositories\SalesAgentRepository;
use App\Http\Controllers\Controller;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Http\Request;

class SalesAgentController extends Controller
{
    private $salesAgentRepository;
    public function __construct(SalesAgentRepository $salesAgentRepository)
    {
        $this->salesAgentRepository = $salesAgentRepository;
    }

    public function index()
    {
        return view('admin::pages.salesagent.index');
    }

    public function show(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $stats = $this->salesAgentRepository->getSalesAgentTotalOrderReport($id);
        $salesAgent = $this->salesAgentRepository->findById($id);
        $hasFiles = $salesAgent?->files()->exists() ?? false;
        return view('admin::pages.salesagent.show', get_defined_vars());
    }


    public function getPending(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.salesagent.pending.index');
    }
}
