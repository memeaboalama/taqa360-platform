<?php

namespace App\Domain\ElmApp\Actions\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Eloquent\Model;

class FetchOrdersReportAction
{
    private MerchantOrderRepository $merchantOrderRepository;

    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }

    public function handle(array $data)
    {
        $paginate = $data["per_page"] ?? 10;
        return $this->merchantOrderRepository->getAllMerchantOrder($data['merchant_id'])->get()->paginate($paginate);
    }
}
