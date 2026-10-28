<?php

namespace App\Application\Api\Controllers\ElmApp;

use App\Application\Api\Requests\ElmApp\Order\UpdateOrderRequest;
use App\Domain\Admin\Actions\Dashboard\Home\StatsAction;
use App\Domain\Admin\Actions\Dashboard\Merchant\FetchTopSalesMerchantsAction;
use App\Domain\ElmApp\resources\OrderResource;
use App\Domain\ElmApp\resources\ReportsResource;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Http\Controllers\Controller;
use App\Application\Api\Requests\ElmApp\Order\FetchOrdersRequest;
use App\Support\Traits\Api\ApiResponseTrait;
use App\Domain\ElmApp\Actions\Order\FetchOrderAction;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;


class ElmAppController extends Controller
{
    use ApiResponseTrait;
    protected MerchantOrderRepository $orderRepository;
    protected MerchantRepository $merchantRepository;


    public function __construct()
    {
        $this->orderRepository = new MerchantOrderRepository(new MerchantOrder());
        $this->merchantRepository = new MerchantRepository(new Merchant());
    }

    public function index(StatsAction $statsAction, FetchTopSalesMerchantsAction $fetchTopSalesMerchantsAction)
    {
        $stats = $statsAction->handle();
        $ordersByMonth = json_encode($stats['ordersByMonth']);
        $salesByMonth = json_encode($stats['ordersByMonth']);
        $bestSellers = $fetchTopSalesMerchantsAction->getTop(10);
        return $this->responseData([
            'statistics' => $stats
        ]);
    }
}
