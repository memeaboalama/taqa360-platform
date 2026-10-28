<?php

namespace Application\Driver\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
//    public $id=1;
    public function getFilePage( )
    {
        $id=session('idRegisterDriver');
        return view('driver::pages.auth.file', get_defined_vars());
    }


}
