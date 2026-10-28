<?php

namespace Domain\Educator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EducatorInvitation extends Model
{
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'completed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public function isValid(): bool
    {
        return $this->completed_at === null && $this->expires_at > now();
    }

    public function markAsCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }
}
