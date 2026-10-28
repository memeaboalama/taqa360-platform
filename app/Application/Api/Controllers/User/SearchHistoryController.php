<?php

namespace App\Application\Api\Controllers\User;

use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class SearchHistoryController extends Controller
{
    use ApiResponseTrait;
    public function fetch(): JsonResponse
    {
        $this->formatData();
        return $this->responseData([
            'history' => $this->formatData()
        ]);
    }

    public function remove($id): JsonResponse
    {
        $search = auth()->guard('user-api')->user()->searchHistory()->where('id', $id)->first();
        if ($search)
            $search->delete();
        return $this->responseData([
            'history' => $this->formatData()
        ],200);
    }

    public function clear(): JsonResponse
    {
        auth()->guard('user-api')->user()->searchHistory()->delete();
        return $this->responseData([
            'history' => $this->formatData()
        ],200 );
    }

    public function formatData()
    {
        $data = [];
        foreach (auth()->guard('user-api')->user()->searchHistory()->groupBy('keyword')->orderBy('id', 'DESC')->take(10)->get() as $key => $keyword)
        {
            $data[$key]['id'] = $keyword->id;
            $data[$key]['keyword'] = $keyword->keyword;
        }
        return $data;
    }
}
