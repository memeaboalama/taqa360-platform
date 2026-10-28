<?php

namespace App\Domain\AppBase\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Specification extends Model
{
    use HasFactory;
    protected $table = 'specifications';
    protected $fillable=[
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class
    ];

    public function desc():Attribute
    {
        return new  Attribute(
            get: fn()=>$this->description,
            set: fn($v)=>$this->description=$v,
        );
    }

    public function specifications_properties()
    {
        return $this->hasMany(\App\Domain\AppBase\Models\SpecificationsProperties::class);
    }
}
