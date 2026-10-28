<?php

namespace App\Abstract;

use Illuminate\Support\Facades\Pipeline;

abstract class AbstractOrderProcess
{
    public array $tasks;

    public function handle(object $payload): mixed
    {
        return Pipeline::send(
            passable: $payload,
        )->through(
            pipes: $this->tasks,
        )->thenReturn();
    }

}
