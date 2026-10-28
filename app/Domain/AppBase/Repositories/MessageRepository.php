<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Message;
use App\Support\Repositories\Main\EloquentMainRepository;

class MessageRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Message $model)
    {
        parent::__construct($model);
    }

    public function getAllMessagesAsBuilder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }
}
