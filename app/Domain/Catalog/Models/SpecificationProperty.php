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
    ];

    protected $casts = [
        'status' => StatusEnum::class,
        'meta' => 'array'
    ];
    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class, 'specification_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }


}
