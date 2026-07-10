<?php

namespace App\Traits;

use App\Constants\ApiMessages;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponses
{

    public static function read(JsonResource $resource): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => ApiMessages::READ->success(),
            'data' => $resource
        ];

        if ($resource->resource instanceof Paginator) {
            $response['pagination'] = [
                'current_page' => $resource->resource->currentPage(),
                'per_page' => $resource->resource->perPage(),
                'total' => $resource->resource->total(),
                'last_page' => $resource->resource->lastPage(),
            ];
        }
        return response()->json($response, 200);
    }

    public static function failedRead(): JsonResponse
    {
        return response()->json([
            "status" => true,
            'message' => ApiMessages::READ->error(),
        ], 404);
    }
}
