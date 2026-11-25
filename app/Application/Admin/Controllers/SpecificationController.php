<?php

namespace Application\Admin\Controllers;

use App\Http\Controllers\Controller;

class SpecificationController extends Controller
{
    public function index()
    {
        return view('admin::pages.specifications.index');
    }

    public function create()
    {
        return $this->editorPage(null, 'create');
    }

    public function edit(int $specification)
    {
        return $this->editorPage($specification, 'edit');
    }

    public function copy(int $specification)
    {
        return $this->editorPage($specification, 'copy');
    }

    private function editorPage(?int $specificationId, string $mode)
    {
        return view('admin::pages.specifications.create', compact('specificationId', 'mode'));
    }
}
