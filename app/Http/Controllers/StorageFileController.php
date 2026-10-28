<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageFileController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $segments = explode('/', $path);
        if (str_contains($path, '\\') || in_array('.', $segments, true) || in_array('..', $segments, true)) {
            abort(404);
        }

        $disk = Storage::disk('fileDisk');
        abort_unless($disk->exists($path), 404);

        return $disk->response($path, null, ['X-Content-Type-Options' => 'nosniff']);
    }
}
