<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecificationsProperties extends Model
{
    use HasFactory;
    protected $table = 'specification_properties';
    protected $fillable=[
        'specification_id',
        'name',
        'value',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function desc():Attribute
    {
        return new  Attribute(
            get: fn()=>$this->value,
            set: fn($v)=>$this->value=$v,
        );
    }
    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }


}
