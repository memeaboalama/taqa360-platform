<?php

namespace App\Domain\MarketPlace\Actions\Api\V2\Branch;

use App\Domain\AppBase\Repositories\DaysRepository;
use App\Domain\MarketPlace\Repositories\Branch\BranchRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Enum\StatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Session;

class FetchBranchAction
{
    private BranchRepository $branchRepository;
    private MerchantRepository $merchantRepository;

    private DaysRepository $daysRepository;


    public function __construct(BranchRepository $branchRepository, MerchantRepository $merchantRepository, DaysRepository $daysRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->merchantRepository = $merchantRepository;
        $this->daysRepository = $daysRepository;
    }

    /**
     * @param array $data
     * @return CursorPaginator|Collection|bool
     */
    public function handle(array $data = [])
    {
        $per_page = isset($data['per_page']) ? $data['per_page'] : 20;
        $location = [$data['longitude'], $data['latitude']];
        if (isset($data['keyword']))
            $this->recordSearch($data['keyword']);

        $merchants = $this->merchantRepository->searchBranch(
            cols: ['id', 'merchant_id', 'address_name', 'merchant_service_id', 'distract_id', 'status', 'cover_image'],
            data: $data,
            geo: $location,
            sorting: isset($data['sorting']) ? $data['sorting'] : [],
            paginate: $per_page
        );
        // return $merchants;
        return $this->checkAvailabilty($merchants);
    }

    /**
     * @param array $data
     * @return CursorPaginator|Collection|bool
     */
    public function handlePackUp(array $data = [])
    {
        $per_page = isset($data['per_page']) ? $data['per_page'] : 20;
        $location = [$data['longitude'], $data['latitude']];
        if (isset($data['keyword']))
            $this->recordSearch($data['keyword']);

        $merchants = $this->merchantRepository->searchPackUpBranch(
            cols: ['id', 'merchant_id', 'address_name', 'merchant_service_id', 'distract_id', 'status', 'cover_image'],
            data: $data,
            geo: $location,
            sorting: isset($data['sorting']) ? $data['sorting'] : [],
            paginate: $per_page
        );
        return $merchants;
        return $this->checkAvailabilty($merchants);
    }

    /**
     * @param array $data
     * @return CursorPaginator|Collection|bool
     */
    public function handleFavourite(array $data = [])
    {
        $per_page = isset($data['per_page']) ? $data['per_page'] : 20;
        $location = [$data['longitude'], $data['latitude']];
        if (isset($data['keyword']))
            $this->recordSearch($data['keyword']);

        $merchants = $this->merchantRepository->searchByNearestFavouriteBranch(
            cols: ['id', 'merchant_id', 'address_name', 'merchant_service_id', 'distract_id', 'status', 'cover_image'],
            data: $data,
            geo: $location,
            sorting: isset($data['sorting']) ? $data['sorting'] : [],
            paginate: $per_page
        );

        return $this->checkAvailabilty($merchants);
    }

    public function details(string $user_name, int $branchId, array $data = []): Model|null
    {
        return $this->merchantRepository->getByUserName($user_name, $branchId, $data);
    }


    private function changeAvailabilty(): void
    {
        $merchants = $this->merchantRepository->all();
    }

    private function checkAvailabilty($merchants)
    {
        $dayID = $this->daysRepository->findByCols(['name->en' => date('l')])->id;
        $currentTime = date('His.u');
        foreach ($merchants as $merchant) {
            if(!isset($merchant['branch']))
            continue;
            $branch = $merchant['branch'];
            if(!$branch)
            continue;
            $workingDay = $branch->workDays()->where('day_id', $dayID)->first();

            if (!$workingDay || $workingDay->status === StatusEnum::INACTIVE->value) {
                $branch->update([
                    'status' => 0,
                    'to' => "",
                    'meridiem' => ""
                ]);
                continue;
            }

            // $maxTo = $workingDay->workHours()->where('merchant_branch_id', $branch->id)->max('to');

            // if (!$workingDay->workHours->count()<=0) {
            //     $branch->update([
            //         'status' => 0,
            //         'to' => "",
            //         'meridiem' => ""
            //     ]);
            //     // continue;
            // }

            $status = 0;
            foreach ($workingDay->workHours()->where('merchant_branch_id', $branch->id)->get() as $period) {
                if ($period->status === StatusEnum::ACTIVE->value) {
                    $from = date_create_from_format('H:i:s', $period->from)->format('His.u');
                    $to = date_create_from_format('H:i:s', $period->to)->format('His.u');

                    if ($currentTime >= $from && $currentTime <= $to) {
                        // $status = 1;
                        $branch->update([
                            'status' => 1,
                            'to' =>  isset($period->to) ? date_create_from_format('H:i:s', $period->to)->format('g:i a') : "",
                            'meridiem' => isset($period->to) ? date_create_from_format('H:i:s', $period->to)->format('A') : ""
                        ]);
                        break;
                    }   else if ($currentTime < $from && $currentTime <= $to) {
                        $branch->update([
                            'status' => 0,
                            'to' => isset($period->from) ? date_create_from_format('H:i:s', $period->from)->format('g:i a') : "",
                            'meridiem' => isset($period->from) ? date_create_from_format('H:i:s', $period->from)->format('A') : ""
                        ]);
                        // break;
                    }
                }
            }

            // if ($status === 0) {
            //     $branch->update([
            //         'status' => 0,
            //         'to' => date_create_from_format('H:i:s', $maxTo)->format('g:i a'),
            //         'meridiem' => date_create_from_format('H:i:s', $maxTo)->format('A')
            //     ]);
            // }
        }

        return $merchants;
    }

    private function recordSearch($keyword): void
    {
        $user = auth()->guard('user-api')->user();
        if ($user && $user instanceof \App\Domain\User\Models\User) {
            if ($user->searchHistory()->count() <= 10) {
                if (!$user->searchHistory()->where('keyword', $keyword)->first())
                    $user->searchHistory()->create(['keyword' => $keyword]);
            }
        }
    }
}
