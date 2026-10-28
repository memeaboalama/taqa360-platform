<?php

namespace App\Domain\MarketPlace\Actions\Api\Branch;


use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\Branch\BranchStatusService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;

class FetchAvailableTimeAction
{
    private BranchRepository $branchRepository;


    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data = []): ?Model
    {
        return $this->branchRepository->findWithWorkDay(cols: ['id','accept_multi_requests_at_same_time','requests_per_hour_for_same_service', 'slot','has_scheduled_services'], relations: ["workDays"], conditions: ["merchant_id" => $data["merchant"], 'id' => $data["branch"]]);
    }

    public function slots($branch, $data): bool|array
    {
        return (new BranchStatusService())->slots($branch, $data);
    }


}
