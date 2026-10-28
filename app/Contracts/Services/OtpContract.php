<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;

interface OtpContract
{
    /**
     * @param $phone_number
     * @param $message
     */
    public function send( $phone_number, $message);
}
