<?php

namespace Domain\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    use HasFactory;
    protected $fillable=[
        'file_name',
        'original_name',
        'mime_type',
        'collection_name',
        'type',
        'storage',
        'url',
        'path',
        'order_column',
        'size',

    ];

    public function fileable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
