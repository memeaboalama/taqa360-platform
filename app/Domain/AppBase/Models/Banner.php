<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;
    protected $fillable=[
        'image',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function getImagePathAttribute()
    {
        return ($this->image) ? Storage::disk('fileDisk')->url($this?->image) : "";
    }
}
