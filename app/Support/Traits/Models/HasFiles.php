<?php

namespace App\Support\Traits\Models;

use App\Domain\Otp\Models\File;

trait HasFiles
{
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
