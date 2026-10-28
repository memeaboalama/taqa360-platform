<?php

namespace Domain\Project\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function scopeByUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    public function getExternalIdAttribute(): string
    {
        return $this->uuid;
    }

    public function getInternalIdAttribute(): int
    {
        return $this->id;
    }
}
