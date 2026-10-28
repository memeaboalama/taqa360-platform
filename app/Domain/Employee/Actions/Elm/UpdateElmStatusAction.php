<?php

namespace App\Domain\Employee\Actions\Elm;

use App\Domain\MarketPlace\Repositories\Order\ElmOrderRepository;
use Illuminate\Database\Eloquent\Model;

class UpdateElmStatusAction
{
    private ElmOrderRepository $elmOrderRepository;

    public function __construct(ElmOrderRepository $elmOrderRepository)
    {
        $this->elmOrderRepository = $elmOrderRepository;
    }

    public function update($data , $status): ?Model
    {
        return $this->elmOrderRepository->update($data->elm_order_id, ["elm_upload_status" => $status]);
    }
}
