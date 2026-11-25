<?php

namespace App\Domain\Catalog\Models;

use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecificationProperty extends Model
{
    use HasFactory;
    protected $table = 'specification_properties';
    protected $fillable=[
        'code',
        'name',
        'value',
        'status',
        'meta',
        'specification_id',
        'item_id',
        'sort_order',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'meta' => 'array'
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->meta['name_' . app()->getLocale()] ?? $this->name;
    }

    public function getDisplayValueAttribute(): string
    {
        $type = $this->meta['input_type'] ?? 'short_text';
        $value = $this->value;
        if (in_array($type, ['multiselect', 'number_range'], true)) {
            $decoded = json_decode((string) $value, true);
            if (is_array($decoded)) {
                $value = $type === 'number_range'
                    ? ($decoded['min'] ?? '') . ' – ' . ($decoded['max'] ?? '')
                    : implode('، ', $decoded);
            }
        } elseif ($type === 'boolean' && $value !== null && $value !== '') {
            $value = (string) $value === '1' ? __('marketplace::products.yes') : __('marketplace::products.no');
        }

        $unit = $this->meta['unit'] ?? '';
        return trim((string) $value . ($unit !== '' && $value !== null && $value !== '' ? ' ' . $unit : ''));
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class, 'specification_id', 'id');
    }

    public function getMasterPropertyIdAttribute(): ?int
    {
        $id = $this->meta['master_property_id'] ?? null;

        return $id ? (int) $id : null;
    }

    public function masterProperty(): BelongsTo
    {
        return $this->belongsTo(self::class, 'master_property_id')
            ->whereNull('item_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }


}
