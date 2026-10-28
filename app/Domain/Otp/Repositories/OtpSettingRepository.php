<?php

namespace App\Domain\Otp\Repositories;

use App\Domain\Otp\Models\Otp;
use App\Domain\Otp\Models\OtpSetting;
use App\Support\Enum\OTPStatus;
use App\Support\Enum\OTPTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;

class OtpSettingRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(OtpSetting $model)
    {
        parent::__construct($model);
    }

    public function getDefaultOtpSetting()
    {
        return $this->modal::where('default',true)->first();
    }
}
