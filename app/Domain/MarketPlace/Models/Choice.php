<?php

namespace Domain\MarketPlace\Models;


use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Choice extends Model
{
    use HasFactory, HasTranslations, BelongsToMerchant, MerchantRelation, HasStatus;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'status',
        'merchant_id'
    ];



    public function additionGroups()
    {
        return $this->belongsToMany(AdditionGroup::class, 'addition_group_choice', 'choice_id', 'addition_group_id');
    }
}
