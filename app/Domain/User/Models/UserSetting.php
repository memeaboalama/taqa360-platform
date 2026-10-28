<?php

namespace App\Domain\User\Models;

use App\Domain\AppBase\Models\Setting\Language;
use App\Support\Traits\Models\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory,BelongsToUser;

    protected $fillable = ['language_id','notifications'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $with = ['language'];

    protected $casts = [];

    // Relations
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
