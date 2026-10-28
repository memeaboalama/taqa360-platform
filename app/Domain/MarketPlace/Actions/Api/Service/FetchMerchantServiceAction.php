<?php

namespace App\Domain\MarketPlace\Actions\Api\Service;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

class FetchMerchantServiceAction
{
    private MerchantServiceRepository $merchantServiceRepository;


    public function __construct(MerchantServiceRepository $merchantServiceRepository)
    {
        $this->merchantServiceRepository = $merchantServiceRepository;


    }

    /**
     * @param array $data
     * @return CursorPaginator
     */
    public function handle(array $data = [])
    {
        $page = isset($data["per_page"]) ? $data["per_page"] : 10;
        return $this->merchantServiceRepository->fetch_merchant_service
        (
            cols: ['id', 'currency_id', 'merchant_id', 'title', 'description', 'default_price', 'status', 'working_minutes'],
            data: $data,
            relations: ['currency:id,iso'],
            condition: ['merchant_id' => $data["merchant"]],
            order: 'DESC', paginate: $page
        );


    }
}
