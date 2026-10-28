<?php

namespace App\Domain\User\listeners;

use App\Domain\User\Models\UserEmail;
use App\Domain\User\Models\UserPhone;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;

class SetDefaultEmailAndPhone
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        $desired_phone_values = $this->getOnlyNeededValues($event,(new UserPhone()));
        $desired_email_values = $this->getOnlyNeededValues($event,(new UserEmail()));

        $event->user->phones()->create(
            array_merge($desired_phone_values,
            [
                'verified_at'   =>  now(),
                'is_default'    =>  'true'
            ])
        );

//        $event->user->emails()->create(
//            array_merge($desired_email_values,
//            [
//                'verified_at'   =>  now(),
//                'is_default'    =>  'true'
//            ])
//        );
    }

    /**
     * @param Registered $event
     * @param Model $model
     * @return array
     */
    public function getOnlyNeededValues(Registered $event, Model $model): array
    {
        $desired_phone_keys = array_intersect($model->getFillable(), $event->user->getFillable());

        $desired_phone_values = array_filter($event->user->toArray(), fn($value) => in_array($value, $desired_phone_keys), ARRAY_FILTER_USE_KEY);

        return $desired_phone_values;
    }
}
