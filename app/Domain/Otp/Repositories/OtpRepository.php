<?php

namespace Domain\Otp\Repositories;

use App\Domain\Otp\Models\Otp;
use App\Support\Enum\OTPStatus;
use App\Support\Enum\OTPTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Support\Repositories\Main\EloquentMainRepository;

class OtpRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Otp $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Model $model
     * @param array $payload
     * @return mixed
     */
    public function createForModel(Model $model , array $payload): mixed
    {
        return $model->otps()->create($payload);
    }

    /**
     * @param Model $model
     * @param OTPTypes $OTPTypes
     * @return mixed
     */
    public function mark_previous_codes_as_expired(Model $model, OTPTypes $OTPTypes): mixed
    {
        return $model->otps()->where('type',$OTPTypes)->update(['expired_at' => now(),'status'=>OTPStatus::EXPIRED]);
    }

    public function delete_expired_codes(): mixed
    {
        return $this->modal::where('expired_at' ,'<', now())->delete();
    }

    public function check_otp_is_valid(Model $model, OTPTypes $OTPType, $otp = null)
    {
        return $model->otps()->where('type',$OTPType->value)->where('expired_at','>',now())->where('code', $otp)->select('id','code')->first();
    }

    public function get_valid_opt_of_same_type(Model $model, OTPTypes $OTPType)
    {
        return $model->otps()->where('type',$OTPType)->where('expired_at','>',now())->where('status',OTPStatus::ACTIVE)->select('id','code')->first();
    }
}
