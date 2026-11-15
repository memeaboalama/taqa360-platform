<?php

namespace App\Domain\MarketPlace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CollectionSetting extends Model
{
    protected $fillable = [
        'recipient',
        'enabled_methods',
        'methods',
        'review_role_ids',
        'approval_role_ids',
        'correction_role_ids',
    ];

    protected $casts = [
        'enabled_methods' => 'array',
        'methods' => 'array',
        'review_role_ids' => 'array',
        'approval_role_ids' => 'array',
        'correction_role_ids' => 'array',
    ];

    public static function current(): ?self
    {
        return static::query()->find(1) ?? static::query()->first();
    }

    public function snapshot(): array
    {
        $methods = collect($this->enabled_methods ?? [])
            ->filter(fn ($method) => in_array($method, ['cash', 'transfer', 'deposit'], true))
            ->mapWithKeys(fn ($method) => [$method => Arr::only($this->methods[$method] ?? [], [
                'recipient_name',
                'instructions',
                'account_data',
            ])])
            ->all();

        return [
            'recipient' => $this->recipient,
            'enabled_methods' => array_keys($methods),
            'methods' => $methods,
        ];
    }

    public static function adminCan(string $ability): bool
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return false;
        }

        setPermissionsTeamId(1);
        if ($admin->type === 'admin') {
            return true;
        }

        $setting = static::current();
        $roleIds = match ($ability) {
            'review' => $setting?->review_role_ids ?? [],
            'approve' => $setting?->approval_role_ids ?? [],
            'correct' => $setting?->correction_role_ids ?? [],
            default => [],
        };

        return count($roleIds) > 0
            && $admin->roles()->whereIn('roles.id', $roleIds)->exists();
    }
}
