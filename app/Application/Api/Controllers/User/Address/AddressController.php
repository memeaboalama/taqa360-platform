<?php

namespace App\Application\Api\Controllers\User\Address;

use App\Application\Api\Requests\User\Address\StoreAddressRequest;
use App\Application\Api\Requests\User\Address\UpdateAddressRequest;
use App\Domain\AppBase\Actions\Location\GetCitiesStateAction;
use App\Domain\AppBase\Jobs\TransformLocationToCity;
use App\Domain\User\Actions\Address\DeleteUserAddressAction;
use App\Domain\User\Actions\Address\FetchUserAddressAction;
use App\Domain\User\Actions\Address\ShowUserAddressAction;
use App\Domain\User\Actions\Address\StoreUserAddressAction;
use App\Domain\User\Actions\Address\UpdateUserAddressAction;
use App\Domain\User\resources\UserAddressResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponseTrait;

    /**
     * @param FetchUserAddressAction $fetchUserAddress
     * @return JsonResponse
     */
    public function fetch(Request $request, FetchUserAddressAction $fetchUserAddress): JsonResponse
    {
        $addresses = $fetchUserAddress->handle(auth()->user(), $request->all());
        return $this->responseData([
            'addresses' => UserAddressResource::collection($addresses)
        ]);
    }

    public function show(int $address_id,ShowUserAddressAction $showUserAddressAction): JsonResponse
    {

       $address= $showUserAddressAction->handle($address_id);
       if(!$address)  return $this->errorResponse(400, 'data not found');
        return $this->responseData([
            'addresses' =>new  UserAddressResource($address)
        ]);
    }

    /**
     * @param StoreAddressRequest $request
     * @param StoreUserAddressAction $storeUserAction
     * @param GetCitiesStateAction $getCitiesStateAction
     * @return JsonResponse
     */
    public function store(StoreAddressRequest $request, StoreUserAddressAction $storeUserAction, GetCitiesStateAction $getCitiesStateAction): JsonResponse
    {
        $data = $request->validated();

        $names = $getCitiesStateAction->handle($data["latitude"], $data["longitude"]);

        if (!$names) return $this->errorResponse(500, 'Unexpected Exception. Try later');

        dispatch(new TransformLocationToCity(names: $names));

        $address = $storeUserAction->handle(auth()->user(), $data, $names);

        if (!$address) return $this->errorResponse(500, 'Unexpected Exception. Try later');
        return $this->responseData([
            'addresses' => new UserAddressResource($address->load('city', 'distract'))
        ]);
    }

    /**
     * @param UpdateAddressRequest $request
     * @param int $address_id
     * @param UpdateUserAddressAction $updateUserAddressAction
     * @param GetCitiesStateAction $getCitiesStateAction
     * @return JsonResponse
     */
    public function update(UpdateAddressRequest $request, int $address_id, UpdateUserAddressAction $updateUserAddressAction, GetCitiesStateAction $getCitiesStateAction): JsonResponse
    {
        $data = $request->validated();
        $names = $getCitiesStateAction->handle($data["latitude"], $data["longitude"]);
        if (!$names)
            return $this->errorResponse(500, 'Unexpected Exception. Try later');

        dispatch(new TransformLocationToCity(names: $names));

        $address = $updateUserAddressAction->handle(auth()->user(), $data, $address_id, $names);
        if (!$address) return $this->errorResponse(500, 'Unexpected Exception. Try later');
        return $this->responseData([
            'addresses' => new UserAddressResource($address->load('city', 'distract'))
        ]);
    }

    public function delete(int $address_id, DeleteUserAddressAction $deleteUserAddressAction): JsonResponse
    {

        $address = $deleteUserAddressAction->handle(auth()->user(), $address_id);

        return $this->responseData(msg: __(' Record deleted.'), status: 201);
    }
}
