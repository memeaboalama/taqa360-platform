<?php

namespace App\Domain\User\Models;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Distract;
use App\Support\Traits\Models\HasNewFactory;


use Faker\Generator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasNewFactory;
    use \Spatie\Translatable\HasTranslations;

    public $translatable = ['title'];

    protected $fillable = [
        'user_id',
        'country_id',
        'city_id',
        'distract_id',
        'title',
        'address',
        'latitude',
        'longitude',
        'is_default'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function distract(): BelongsTo
    {
        return $this->belongsTo(Distract::class);
    }

    public function definition(Generator $faker)
    {
        $users = User::count();
        return [
            'user_id' => $faker->numberBetween(1,$users),
            'country_id' => 1,
            'city_id' => 1,
            'distract_id' => 1,
            'title' => $faker->title(),
            'address' => $faker->address(),
            'latitude' => $faker->latitude(),
            'longitude' => $faker->longitude(),
            'is_default' => $faker->boolean(),
        ];
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($model) {
            $model->country_id = 1;
        });
    }

}
