<?php

namespace Application\Marketplace\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploads(Request $request)
    {
     if($request->hasFile('image')){
        $files = $request->file('image');
           foreach ($files as $file) {
                    $filename = $file->getClientOriginalName();
                    $folder = uniqid();
                    $file->move("storage/upload/file/" . $folder,$filename);
                    $url = "storage/upload/file/" . $folder . "/" . $filename;
                    return $url;
            }
     }
     return '';
    }
}
