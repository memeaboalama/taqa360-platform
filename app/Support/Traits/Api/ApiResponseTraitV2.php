<?php

namespace App\Support\Traits\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

trait ApiResponseTraitV2
{
    /**
     * @param array $data
     * @param int $code
     * @param null $msg
     * @param bool $status
     * @return JsonResponse
     */
    public function responseData(array $data = [], int $code = 200, $msg = null, bool $status = true): JsonResponse
    {
        $resourceCollection = reset($data);

        if ($resourceCollection instanceof AnonymousResourceCollection && $resourceCollection->resource instanceof LengthAwarePaginator) {
            $paginator = $resourceCollection->resource;
            return $this->handlePaginatedResponse($data, $paginator, $status, $code, $msg);
        }

        if ($resourceCollection instanceof AnonymousResourceCollection && $resourceCollection->resource instanceof CursorPaginator) {
            $paginator = $resourceCollection->resource;
            return $this->handleCursorPaginatedResponse($data, $paginator, $status, $code, $msg);
        }

        return $this->handleResponse($data, $status, $code, $msg);
    }

    /**
     * @param array $data
     * @param int $code
     * @param $msg
     * @return JsonResponse
     */
    public function responseError(array $data = [], int $code = 404, $msg = null): JsonResponse
    {
        return $this->handleResponse($data, false, $code, $msg);
    }

    /**
     * @param array $data
     * @param bool $status
     * @param int $code
     * @param null $msg
     * @param array $headers
     * @return JsonResponse
     */
    public function handleResponse(array $data, bool $status, int $code, $msg = null, array $headers = []): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ], $code, $headers);
    }

    protected function errorResponse(int $code, $msg = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $msg,
            'data' => null
        ], $code);
    }

    public function returnValidationError($validator, ValidationException $exception): JsonResponse
    {
        return $this->responseError($validator->errors()->toArray(), 422, $exception->getMessage());
    }

    public function handlePaginatedResponse(array $originalData, LengthAwarePaginator $paginator, bool $status, int $code, $msg = null, array $headers = []): JsonResponse
    {
        $paginationData = [
            'pagination' => [
                'meta' => [
                    'page' => [
                        "current" => $paginator->currentPage(),
                        "first" => 1,
                        "last" => $paginator->lastPage(),
                        "next" => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
                        "previous" => $paginator->currentPage() > 1 ? $paginator->currentPage() - 1 : null,
                        "per" => $paginator->perPage(),
                        "from" => $paginator->firstItem(),
                        "to" => $paginator->lastItem(),
                        "count" => $paginator->count(),
                        "total" => $paginator->total(),
                        "isFirst" => $paginator->onFirstPage(),
                        "isLast" => !$paginator->hasMorePages(),
                        "isNext" => $paginator->hasMorePages(),
                        "isPrevious" => $paginator->currentPage() > 1,
                    ],
                ],
                "links" => [
                    "path" => $paginator->path(),
                    "first" => $paginator->url(1),
                    "next" => $paginator->nextPageUrl(),
                    "previous" => $paginator->previousPageUrl(),
                    "last" => $paginator->url($paginator->lastPage()),
                ],
            ],
        ];

        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $originalData,
        ];

        $response = array_merge($response, $paginationData);

        return response()->json($response, $code, $headers);
    }

    public function handleCursorPaginatedResponse(array $originalData, CursorPaginator $paginator, bool $status, int $code, $msg = null, array $headers = []): JsonResponse
    {
        $paginationData = [
            'pagination' => [
                'meta' => [
                    'page' => [
                        "per" => $paginator->perPage(),
                        "count" => $paginator->count(),
                        "isFirst" => $paginator->onFirstPage(),
                    ],
                ],
                "links" => [
                    "path" => $paginator->path(),
                    "next" => $paginator->nextPageUrl(),
                    "previous" => $paginator->previousPageUrl(),
                ],
            ],
        ];

        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $originalData,
        ];

        $response = array_merge($response, $paginationData);

        return response()->json($response, $code, $headers);
    }
}
