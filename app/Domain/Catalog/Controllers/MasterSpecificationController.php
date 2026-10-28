<?php

// namespace App\Domain\Catalog\Controllers\Admin\Catalog;
namespace App\Domain\Catalog\Controllers;

use App\Domain\Catalog\Models\Specification;
use App\Http\Controllers\Controller;
use App\Domain\Catalog\Resources\SpecificationResource;
use App\Domain\Catalog\Actions\Specification\AttachMasterSpecificationToItemAction;
use App\Domain\Catalog\Data\Specification\AttachMasterSpecificationData;
use App\Domain\Catalog\Data\Specification\CreateSpecificationData;
use App\Domain\Catalog\Data\Specification\UpdateSpecificationRequest;
use App\Domain\Catalog\Actions\Specification\CreateSpecificationAction;
use App\Domain\Catalog\Actions\Specification\UpdateSpecificationAction;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MasterSpecificationController extends Controller
{
    use ApiResponseTrait;
    
    /**
     * Search master specifications for autocomplete functionality.
     */
    public function index(Request $request): JsonResponse
    {
        $term = $request->query('term');
        $item_id = $request->query('item_id');

        // $masters = Specification::isMaster()
        $masters = Specification::isMasterOrNotInItem($item_id)
            ->where('name', 'LIKE', "%{$term}%")
            ->take(10)
            ->get();

        // return response()->json(SpecificationResource::collection($masters));
        return $this->responseSuccessData(['data'=>SpecificationResource::collection($masters)]);
    }


    public function store(CreateSpecificationData $data, CreateSpecificationAction $action): JsonResponse
    {
        $specification = $action->execute($data);
        // return response()->json(new SpecificationResource($specification->load('properties')), 201);
        return $this->responseSuccessData(new SpecificationResource($specification->load('properties')), 201);
    }


    public function show(Specification $specification): JsonResponse
    {
        return  $this->responseSuccessData(new  SpecificationResource($specification->load('properties')));
        // return response()->json(new SpecificationResource($specification->load('properties')));
    }

    public function update(Specification $specification, UpdateSpecificationRequest $data, UpdateSpecificationAction $action): JsonResponse
    {
        $specification = $action->execute($specification, $data);
        // return response()->json(new SpecificationResource($specification));
        return $this->responseSuccessData(new SpecificationResource($specification));
    }

    public function destroy(Specification $specification): JsonResponse
    {
        $specification->delete();
        // return response()->json(null, 204);
        return  $this->responseData([]);
    }

    /**
     * Attach a master specification to a given item.
     */
    public function attachToItem(AttachMasterSpecificationData $data, AttachMasterSpecificationToItemAction $action): JsonResponse
    {
        $itemSpecification = $action->execute($data);
        // return response()->json(new SpecificationResource($itemSpecification->load('properties')), 201);
        return $this->responseSuccessData(new SpecificationResource($itemSpecification->load('properties')), 201);
    }
}