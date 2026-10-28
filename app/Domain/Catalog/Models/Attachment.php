<?php

namespace App\Domain\Catalog\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\Traits\Models\HasStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Attachment extends Model
{
    use HasFactory;
    // use HasTranslations;
    use HasStatus;
    protected $table = 'attachments';
    // protected $translatable = ['name', 'description'];
    protected $fillable = [
        'type',
        'storage',
        'path',
        'webp_path',
        'thumbnail_path',
        'title',
        'description',
        'collection_name',
        'file_name',
        'original_name',
        'mime_type',
        'url',
        'meta',
    ];

    protected $casts = [
        // 'status' => StatusEnum::class,
        'meta' => 'array'
    ];


    public function attachables(): MorphToMany {
        return $this->morphedByMany(null, 'attachable', 'attachables', 'attachment_id', 'attachable_id');
    }

}
