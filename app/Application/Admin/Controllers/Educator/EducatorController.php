<?php

namespace Application\Admin\Controllers\Educator;

use Domain\Educator\Repositories\EducatorRepository;
use App\Http\Controllers\Controller;

class EducatorController extends Controller
{
    private $educatorRepository;
    public function __construct(EducatorRepository $educatorRepository)
    {
        $this->educatorRepository = $educatorRepository;
    }

    public function index()
    {
        return view('admin::pages.educators.index');
    }

    public function show(int $id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $stats = $this->educatorRepository->getEducatorTotalOrderReport($id);
        $educator = $this->educatorRepository->findById($id);
        return view('admin::pages.educators.show', get_defined_vars());
    }

    public function trackDrivers()
    {
        return view('admin::pages.educators.track');
    }

    public function getPending(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::pages.educators.pending.index');
    }
}
