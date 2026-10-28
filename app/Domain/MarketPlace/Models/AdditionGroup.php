<?php

namespace Domain\MarketPlace\Models;


use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AdditionGroup extends Model
{
    use HasFactory, HasTranslations, BelongsToMerchant, MerchantRelation, HasStatus;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'status',
        'merchant_id',
        'min_value',
        'max_value',
        'order',
        'multi',
    ];


    public function choices()
    {
        return $this->belongsToMany(Choice::class, 'addition_group_choice', 'addition_group_id', 'choice_id')->withPivot('price', 'order');
    }
}
